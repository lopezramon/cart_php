<?php
    require_once("CartController.php");
    $db_result = new CartController();
    if(!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                if(!empty($_POST["quantity"])) {
                    $productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
                    $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
                    
                    if(!empty($_SESSION["cart_item"])) {
                        if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
                            foreach($_SESSION["cart_item"] as $k => $v) {
                                    if($productByCode[0]["code"] == $k) {
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
            break;
            case "remove":
                if(!empty($_SESSION["cart_item"])) {
                    foreach($_SESSION["cart_item"] as $k => $v) {
                            if($_GET["code"] == $k)
                                unset($_SESSION["cart_item"][$k]);				
                            if(empty($_SESSION["cart_item"]))
                                unset($_SESSION["cart_item"]);
                    }
                }
            break;
            case "empty":
                unset($_SESSION["cart_item"]);
            break;	
        }
        }
    

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Task 4- Example</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/style.css'>
    <!-- Bootstrap 4 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

    <!--Begin container-->
    <div id="container"> 
        <!--begin main-->
        <div id="main"> 
            <h1>List Products</h1>
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $product_array = $db_result->runQuery("SELECT * FROM product ORDER BY id ASC");
                        if (!empty($product_array)) { 
                            foreach($product_array as $key=>$value){
                    ?>
                    <tr>
                        <td><?php echo $product_array[$key]["id"] ?></td>
                        <td><?php echo $product_array[$key]["name"] ?></td>
                        <td><?php echo $product_array[$key]["price"] ?></td>
                        <td><input type="text" class="text-center" name="quantity" value="1" size="2" /></td>
                        <td>
                            <form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["id"]; ?>">
                                <input type="submit" value="Add to Cart" />
                            </form>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    ?>        
                </tbody>
            </table>
        </div>
        <!--end main-->
        
        <!--begin sidebar-->    
        <div id="sidebar"> 
            <h1>Cart</h1>
            <p>Your cart is empty</p>
        </div>
        <!--end sidebar-->

    </div>
    <!--end container-->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>