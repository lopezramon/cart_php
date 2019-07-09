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
            # code..
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
            # code...
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $key => $value) {
                    if($_GET["code"] == $key )
                        unset($_SESSION["cart_item"][$key]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            
                // $_SESSION["cart_item"] = array_values($_SESSION["cart_item"]);
            }

            return $_SESSION["cart_item"];
        }
        
        /**
         * Empty Cart
         */
        public function emptyCart()
        {
            # code...
            unset($_SESSION["cart_item"]);
            return $_SESSION["cart_item"];

        }

        /**
         * Payment Cart
         */
        public function payment()
        {
            # code...
            if(!empty($_POST["mont"])){
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