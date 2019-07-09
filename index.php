<?php
    require_once("CartController.php");
    session_start();
    $cartList = new Cart();
    if(!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                $cartList->addCart();
                break;
            case "remove":
                $cartList->removeCart();
                break;
            case "pay":
                $cartList->payment();
                break;
            case "empty":
                $cartList->emptyCart();
                break;
            case "dest":
                $cartList->reset();
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
    
    <!-- Bootstrap 4 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <!-- css -->
    <link rel='stylesheet' type='text/css' href='css/style.css'>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/5a820aa652.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.rateit/1.1.2/jquery.rateit.min.js"></script>

</head>
<body>
    <!--Begin container-->
    <div class="container mb-5">
        <div class="row" >
            <div class="container">
                <!-- <h1>List Products</h1> -->
                <div class="row">
                        <?php
                            $product_array = $cartList->list();
                            if (!empty($product_array)) {
                                foreach($product_array as $key=>$value){
                        ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100" style="background-color: white;">
                            <a href="#"><img class="card-img-top"  src="<?php echo $HTTP_HOST.'/'.$product_array[$key]["image"]; ?>" alt=""></a>
                        <div class="card-body">
                            <h4 class="card-title">
                            <form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                                <span><?php echo $product_array[$key]["name"] ?></span>
                                </h4>
                                <h5>$<?php echo $product_array[$key]["price"] ?></h5>
                                <input type="number" class="text-center" name="quantity" min="1" max="100" value="1" size="2" />
                                <input type="submit" class="btn btn-success" value="Add to Cart" />
                            </form>

                        </div>
                        <div class="card-footer">
                            <div class="rateit">
                            </div>

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
            
        </div>

        <!-- CART AMOUNT -->
        <div class="row">
            <div class="col-6">
                    <p> Your cash: <?php
                        if($_SESSION['cash'] == null){
                            $_SESSION['cash'] = 100;
                            echo $_SESSION['cash'];
                        }elseif($_SESSION['cash'] <= 0){
                            echo 'You do not have enough balance to perform this operation
                            <a href="index.php?action=dest" class="btn btn-success">Reset</a>';
                        }else {
                            echo $_SESSION['cash'];
                        }
                    ?></p>
            </div>
            <div class="col-6 text-right">
                <span class="text-right"><a href="index.php?action=empty">Empty Cart <i class="fas fa-trash-alt"></i></a></span>       
            </div>
        </div>

        <!-- TABLA -->
        <?php
            if(isset($_SESSION["cart_item"])){
                $total_quantity = 0;
                $total_price = 0; 
        ?>
        <div class="row">
            <div class="col">
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
                        <td scope="col"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction" ><i class="fas fa-trash-alt"></i></a></td>
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
            </div>
        </div>
        <?php
            
                } else {
                    ?>
                <div class="row">
                    <div class="col text-center">
                        <span>Your Cart is Empty</span>
                    </div>    
                </div>
                <?php
                }
            ?>

        <!-- SHIPPING -->
        <div class="row">
            <div class="col">
      
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

                </form>
            </div>
        </div>
    </div>
        </div>
        <!--end row-->
    </div>
    <!--end container-->


<script>
// Try edit msg
var textExample = 'something'

function makeSpace(){
    arrayTextExample = textExample.split('').join(' ')
    
    setTimeout(function(){
      alert(arrayTextExample)  
    }, 3000)
}

//smakeSpace();
</script>

</body>
</html>