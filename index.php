<?php
    session_start();
    require_once("CartController.php");
    $db_result = new CartController();
    if(!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                if(!empty($_POST["quantity"])) {
                    $productByid = $db_result->runQuery("SELECT * FROM product WHERE id='" . $_GET["id"] . "'");
                    $itemArray = array($productByid[0]["id"]=>array('name'=>$productByid[0]["name"], 'id'=>$productByid[0]["id"], 'quantity'=>$_POST["quantity"], 'price'=>$productByid[0]["price"], 'image'=>$productByid[0]["image"]));

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
</head>
<body>

    <!--Begin container-->
    <div class="container">

        <div class="row">
            <!--begin main-->
            <div class="col-9" id="main">
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
                            <form method="post" action="index.php?action=add&id=<?php echo $product_array[$key]["id"]; ?>">
                            <td><?php echo $product_array[$key]["id"] ?></td>
                            <td><?php echo $product_array[$key]["name"] ?></td>
                            <td><?php echo $product_array[$key]["price"] ?></td>
                            <td><input type="text" class="text-center" name="quantity" value="1" size="2" /></td>
                            <td><input type="submit" value="Add to Cart" /></td>
                            </form>
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
            <div class="col" id="sidebar">
                <h1>Cart</h1>
                <?php
                if(isset($_SESSION["cart_item"])){
                    $total_quantity = 0;
                    $total_price = 0;
                ?>
                <table class="tbl-cart" cellpadding="10" cellspacing="1">
                    <tbody>
                        <tr>
                            <th style="text-align:left;">Name</th>
                            <th style="text-align:left;">id</th>
                            <th style="text-align:right;" width="5%">Quantity</th>
                            <th style="text-align:right;" width="10%">Unit Price</th>
                            <th style="text-align:right;" width="10%">Price</th>
                            <th style="text-align:center;" width="5%">Remove</th>
                        </tr>
                        <?php
                            foreach ($_SESSION["cart_item"] as $item){
                                $item_price = $item["quantity"]*$item["price"];
                        ?>
                        <tr>
                            <td><?php echo $item["name"]; ?></td>
                            <td><?php echo $item["id"]; ?></td>
                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                            <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
                            <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                            <td style="text-align:center;"><a href="index.php?action=remove&id=<?php echo $item["id"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
                        </tr>
                        <?php
                                $total_quantity += $item["quantity"];
                                $total_price += ($item["price"]*$item["quantity"]);
                            }
                        ?>
                        <tr>
                            <td colspan="2" align="right">Total:</td>
                            <td align="right"><?php echo $total_quantity; ?></td>
                            <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
                            <td></td>
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
            <!--end sidebar-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://id.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>