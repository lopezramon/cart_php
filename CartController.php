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
         */
        public function addCart()
        {
            var_dump($_POST["quantity"]);
            var_dump($_GET);
            if(!empty($_POST["quantity"])) {
                $productById = $this->runQuery("SELECT * FROM product WHERE code='" . $_GET["code"] . "'");
                $itemArray = array($productById[0]["code"]=>array('name'=>$productById[0]["name"], 'id'=>$productById[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$productById[0]["price"], 'code'=>$productById[0]["code"], 'image'=>$productById[0]["image"]));

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productById[0]["code"],array_keys($_SESSION["cart_item"]))) {
                        foreach($_SESSION["cart_item"] as $key => $value) {
                            if(($productById[0]["code"]) == $key) {
                                if(empty($_SESSION["cart_item"][$key]["quantity"])) {
                                    $_SESSION["cart_item"][$key]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$key]["quantity"] += $_POST["quantity"];
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }

            return $_SESSION["cart_item"];
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
            if( $_POST["mont"] + $_POST["shipping"] > 100 ){
                $_SESSION['warning'] = ', The purchase amount exceeds $100';
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
        public function startProduct()
        {
            # code...
            
        }
    }
?>