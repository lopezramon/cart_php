<?php
    
    require_once("includes/ConnectionDB.php");
    require_once("Product.php");
    
    
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

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productById[0]["code"],array_keys($_SESSION["cart_item"]))) {
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
         * Empty Cart
         */
        public function emptyCart()
        {
            unset($_SESSION["cart_item"]);
            session_destroy($_SESSION['cart_item']);
            unset($_SESSION['shipping']);
            unset($_SESSION['option']);

            //delete message from the warning session if there is
            if ( !empty($_SESSION['warning']) ) {
                unset($_SESSION['warning']);
            }
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
                    unset($_SESSION["cart_item"]);
                }
            }
        }

        /**
         * Payment Cart
         */
        public function reset()
        {
            session_unset();
            session_destroy();
        }

        /**
         * Rating by stars of product
         */
        public function shipping()
        {
            $_SESSION['option']   = $_GET['option'] > 0 ? 'UPS' : 'Pick up - Free';
            $_SESSION['shipping'] = $_GET['option'];
        }

        /**
         * Rating by stars of product
         */
        public function startProduct()
        {
            # code...
            
        }
    }
?>