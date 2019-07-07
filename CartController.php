<?php
    
    require_once("includes/ConnectionDB.php");
    require_once("Product.php");
    
    session_start();
	$db_result = new DBController();
	
    class Crud extends DBController 
    {	

		public function list()
		{
            try {
                $product_array = $this->runQuery("SELECT * FROM product ORDER BY id ASC");
                // var_dump($product_array);
            } catch (TypeError $th) {
                //throw $th;
                echo 'Error: '.$th->getMessage();
            }
            
            return $product_array;
        }
        
        public function addCart()
        {
            # code..
            if(!empty($_POST["quantity"])) {
                $productById = $this->runQuery("SELECT * FROM product WHERE id='" . $_GET["id"] . "'");
                $itemArray = array($productById[0]["id"]=>array('name'=>$productById[0]["name"], 'id'=>$productById[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$productById[0]["price"]));

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productById[0]["id"],array_keys($_SESSION["cart_item"]))) {
                        foreach($_SESSION["cart_item"] as $k => $v) {
                            if($productById[0]["id"] == $k) {
                                if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
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

        public function removeCart()
        {
            # code...
            if(!empty($_SESSION["cart_item"])) {
                // var_dump($_SESSION["cart_item"]);
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["id"] == $k)
                    unset($_SESSION["cart_item"][$k]);
                    if(empty($_SESSION["cart_item"]))
                    unset($_SESSION["cart_item"]);
                }

            }

            return $_SESSION["cart_item"];
        }
        
        public function emptyCart()
        {
            # code...
            unset($_SESSION["cart_item"]);
            return $_SESSION["cart_item"];

        }

        public function payment()
        {
            # code...
            if(!empty($_POST["mont"])){
                $_SESSION['cash'] = $_SESSION['cash'] - $_POST["mont"] - $_POST["shipping"] ;
                unset($_SESSION["cart_item"]);
            }
            if($_SESSION['cash'] < 0 ){
                $_SESSION['cash'] = 100;
            }
        }

        public function startProduct(Type $var = null)
        {
            # code...
            
        }
    }
?>