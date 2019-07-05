<?php
    session_start();
    require_once("CartController.php");
    $db_result = new CartController();
    if(!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                if(!empty($_POST["quantity"])) {
                    $productByid = $db_result->runQuery("SELECT * FROM product WHERE id='" . $_GET["id"] . "'");
                    $itemArray = array($productByid[0]["id"]=>array('name'=>$productByid[0]["name"], 'id'=>$productByid[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$productByid[0]["price"]));

                    if(!empty($_SESSION["cart_item"])) {
                        if(in_array($productByid[0]["id"],array_keys($_SESSION["cart_item"]))) {
                            foreach($_SESSION["cart_item"] as $k => $v) {
                                    if($productByid[0]["id"] == $k) {
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
                            if($_GET["id"] == $k)
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
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5a820aa652.js"></script>

</head>
<body>

    <!--Begin container-->
    <div class="container">
        <div class="row">
            <div class="container">
                <h1>List Products</h1>
                <div class="row">
                        <?php
                            $product_array = $db_result->runQuery("SELECT * FROM product ORDER BY id ASC");
                            if (!empty($product_array)) {
                                foreach($product_array as $key=>$value){
                        ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100">
                            <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
                        <div class="card-body">
                            <h4 class="card-title">
                            <form method="post" action="index.php?action=add&id=<?php echo $product_array[$key]["id"]; ?>">
                                <span><?php echo $product_array[$key]["name"] ?></span>
                                </h4>
                                <h5>$<?php echo $product_array[$key]["price"] ?></h5>
                                <input type="text" class="text-center" name="quantity" value="1" size="2" />
                                <input type="submit" value="Add to Cart" />
                            </form>

                        </div>
                        <div class="card-footer">
                            <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                        </div>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <?php
                if(isset($_SESSION["cart_item"])){
                    $total_quantity = 0;
                    $total_price = 0;
            ?>
            <a href="index.php?action=empty">Empty Cart <i class="fas fa-trash-alt"></i></a>
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        var_dump($_SESSION["cart_item"]);
                        foreach ($_SESSION["cart_item"] as $item){
                            $item_price = $item["quantity"]*$item["price"];
                    ?>
                    <tr>
                        <td scope="col"><?php echo $item["name"]; ?></td>
                        <td scope="col"><?php echo "$ ".$item["price"]; ?></td>
                        <td scope="col"><?php echo $item["quantity"]; ?></td>
                        <td scope="col"><?php echo "$ ". number_format($item_price,2); ?></td>
                        <td scope="col"><a href="index.php?action=remove&id=<?php echo $item["id"]; ?>" class="btnRemoveAction" ><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                    <?php
                            $total_quantity += $item["quantity"];
                            $total_price += ($item["price"]*$item["quantity"]);
                        }
                    ?>
                    <tr>
                        <td colspan="2" align="right">Total:</td>
                        <td align="lelf"><?php echo $total_quantity; ?></td>
                        <td align="lelf" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
                        <!-- <td></td> -->
                    </tr>
                </tbody>
            </table>
            <?php
                } else {
                ?>
                <div class="no-records">Your Cart is Empty</div>
                <?php
                }
                ?>
        </div>
    </div>
        </div>
        <!--end row-->
    </div>
    <!--end container-->
</body>
</html>