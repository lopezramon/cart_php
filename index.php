<?php
    require_once("CartController.php");
        
    $listado = new Crud();
    if(!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                $listado->addCart();
                break;
            case "remove":
                $listado->removeCart();
                break;
            case "pay":
                $listado->payment();
                break;
            case "empty":
                $listado->emptyCart();
                break;
            default:
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
<style>
.checked {
  color: orange;
}
</style>
<body>

    <!--Begin container-->
    <div class="container">
        <div class="row">
            <div class="container">
                <!-- <h1>List Products</h1> -->
                <div class="row">
                        <?php
                            $product_array = $listado->list();
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
                                <input type="number" class="text-center" name="quantity" min="1" max="100" value="1" size="2" />
                                <input type="submit" class="btn btn-success" value="Add to Cart" />
                            </form>

                        </div>
                        <div class="card-footer">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <p> Your cash: <?php
                if($_SESSION['cash'] == null){
                    $_SESSION['cash'] = 100;
                }
            echo $_SESSION['cash']; ?></p>
            <?php
                if(isset($_SESSION["cart_item"])){
                    $total_quantity = 0;
                    $total_price = 0;
                    
                    
            ?>
            <span class="break"><a href="index.php?action=empty">Empty Cart <i class="fas fa-trash-alt"></i></a></span>       
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
                    <form method="post" action="index.php?action=pay">
                        <tr>
                            <td colspan="2" align="right">Total:</td>
                            <td align="lelf"><?php echo $total_quantity; ?></td>
                            <td align="lelf" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <div>  
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select shipping</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="shipping" required>
                            <option value="">None</option>
                            <option value="0">Pick up - free</option>
                            <option value="5">UPS - $5</option>
                        </select>
                    </div> 
                    <input type="hidden" class="text-center" name="mont" value="<?php echo $total_price; ?>" size="2" />
                    <span class="break">  
                        <button type="submit" class="btn btn-success">Pay</button>
                    </span>
                </div>
            </form>
            <?php
                } else {
                    ?>
                <div class="no-records"><span class="break">Your Cart is Empty</span></div>
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