<?php
    
    require_once("includes/ConnectionDB.php");

	$db_result = new DBController();
	
    class Cart extends DBController 
    {	
        /**
         * list Product
         */
		public function list()
		{
            try {
                $product_array = $this->runQuery("SELECT * FROM product ORDER BY id ASC");
            } catch (TypeError $th) {
                echo 'Error: '.$th->getMessage();
            }
            
            return $product_array;
        }

        /**
         * Add Product Cart
         * @return array 
         */
        public function addCart()
        {
            
            $code = explode("/", $_GET["code"]);
            
            if(!empty($_POST["quantity"]) || !empty($code[1])) {
                $productById = $this->runQuery("SELECT * FROM product WHERE code='" . $code[0] . "'");
                
                $cartQuantity = !empty($_POST["quantity"]) ? $_POST["quantity"] : $code[1];
                
                $itemArray = array($productById[0]["code"]=>array(
                    'name'=>$productById[0]["name"], 
                    'id'=>$productById[0]["id"], 
                    'quantity'=> $cartQuantity, 
                    'price'=>$productById[0]["price"], 
                    'code'=>$productById[0]["code"], 
                    'image'=>$productById[0]["image"])
                );
                //valid that the car is not empty
                if(!empty($_SESSION["cart_item"])) {
                    //valid that the product code is in the cart
                    if(in_array($productById[0]["code"],array_keys($_SESSION["cart_item"]))) {
                        //I go through the entire cart to add the amounts per podruct
                        foreach($_SESSION["cart_item"] as $key => $value) {
                            if(($productById[0]["code"]) == $key) {
                                if(empty($_SESSION["cart_item"][$key]["quantity"])) {
                                    $_SESSION["cart_item"][$key]["quantity"] = 0;
                                }
                                if(!empty($_POST["quantity"])){
                                    $_SESSION["cart_item"][$key]["quantity"] += $cartQuantity;
                                }else {
                                    $_SESSION["cart_item"][$key]["quantity"] = $cartQuantity;
                                }
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
        }

        /**
         * Remove Product Cart
         */
        public function removeCart()
        {
                foreach($_SESSION["cart_item"] as $key => $value) {
                    if($_GET["code"] == $key )
                        unset($_SESSION["cart_item"][$key]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }

                // delete message from the warning session if there is
                if (!empty($_SESSION['warning']) ) {
                     unset($_SESSION['warning']);
                }
        }
        
        /**
         * Clear Cart
         */
        public function emptyCart()
        {
            unset($_SESSION["cart_item"]);
            session_destroy($_SESSION['cart_item']);
            
            //option varible empty
            unset($_SESSION['option']);

            //shipping varible empty
            unset($_SESSION['shipping']);

            //remove message the rate
            unset( $_SESSION['voteMessger']);

            //remove warning message
            unset($_SESSION['warning']);
        }

        /**
         * Payment Cart
         */
        public function payment()
        {
            if ($_POST["mont"]) {
                
                if( $_POST["mont"] + $_POST["shipping"] > 100 ){
                    $_SESSION['warning'] = ', The purchase amount exceeds ' .$_SESSION['cash'];
                }elseif($_SESSION['cash'] <= 0){
                    $_SESSION['warning']  = ', You do not have enough balance to perform this operation
                        <a href="index.php?action=dest" class="btn btn-warning">Reset</a>';
                }elseif($_POST["mont"] + $_POST["shipping"] > $_SESSION['cash']){
                    $_SESSION['warning']  = ', Does not have enough cash to make the purchase, please reload <a href="index.php?action=dest" class="btn btn-warning">Reset</a>';
                }else {
                    $_SESSION['cash'] = $_SESSION['cash'] - $_POST["mont"] - $_POST["shipping"] ;
                    $this->emptyCart();
                }

            }
        }

        /**
         * Payment Cart
         */
        public function reset()
        {
            //cash varible empty
            unset($_SESSION['cash']);
            //remove warning message
            unset($_SESSION['warning']);
        }

        /**
         * Rating by stars of product
         */
        public function shipping()
        {
            if ($_GET['option'] == 5) {
                $_SESSION['option'] = 'UPS';
                $_SESSION['shipping'] = $_GET['option'];
            } elseif ($_GET['option'] == 0) {
                $_SESSION['option'] = 'Pick up - Free';
                $_SESSION['shipping'] = $_GET['option'];
            }else{
                unset($_SESSION['option']);
                unset($_SESSION['shipping']);
            }
        }

        /**
         * Vote by product
         */
        public function voteProduct()
        {    
            //Valid if you already voted for this product  
            if(in_array($_POST[id],$_SESSION['voteProduct'])){
                $_SESSION['voteMessger'] = 'Remember that you can only vote once for this product';
            }else {
                //Insert vote for this product in BD
                $reusltsql = $this->runQuery("INSERT INTO qualification (id_product, $_POST[start]) 
                                              VALUES ($_POST[id],1)");
                
                //limpia la variable del mensaje
                unset( $_SESSION['voteMessger']); 

                if (isset($_SESSION['voteProduct'])) {
                    //The product for which you vote is added
                    array_push($_SESSION['voteProduct'], $_POST[id]);
                }else {
                    $_SESSION['voteProduct'] = [];
                    //The product for which you vote is added
                    array_push($_SESSION['voteProduct'], $_POST[id]);
                }
            }
        }

        /**
         * 
         * Change User
         */
        public function changeUser()
        {
            //clear cart
            $this->emptyCart();

            //Clean votes
            unset( $_SESSION['voteProduct']);

            //starts the session variable for voting
            $_SESSION['voteProduct'] = [];

            //cash varible empty
            unset($_SESSION['cash']);
        }

        /**
         * Average votes per product
         * @param int
         * @return array
         */
        public function voteAVG($code)
        {
            //Consult qualifications the vote
            $qualifications = $this->runQuery("SELECT p.name,p.code, SUM(q.fisrt) as fisrt,SUM(q.secund) as secund,sum(q.third) as third,SUM(q.quarter) as quarter,SUM(q.fifth) as fifth 
                FROM qualification q 
                INNER JOIN product p ON q.id_product=p.id 
                where p.code = '$code'
                GROUP BY p.name,p.code");

            $promProduct = 0;
            foreach ($qualifications as $productQ) {
                //calculation of the average per product vote
                $promProduct = (($productQ[fisrt] * 1) + ($productQ[secund] * 2) + ($productQ[third] * 3) + ($productQ[quarter] * 4) + ($productQ[fifth] * 5)) / ($productQ[fisrt] + $productQ[secund] + $productQ[third]+ $productQ[quarter] + $productQ[fifth]);
                //Adding to an array
                $result [] = [$productQ[code] => number_format($promProduct, 2, ',', '')];
            }            

            return $result;
        }
    }
?>