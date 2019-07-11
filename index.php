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
                $cash = $cartList->payment();
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
        <!-- Card Products -->
        <div class="row">
            <?php
                $product_array = $cartList->list();
                if (!empty($product_array)) {
                    foreach($product_array as $key=>$value){
            ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <a href="#"><img class="card-img-top"  src="<?php echo $HTTP_HOST.'/'.$product_array[$key]["image"]; ?>" alt=""></a>
                    <div class="card-body">
                        <form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                            <h4 class="card-title">
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

        <!-- Cash Amount and Empty Cart -->
        <div class="row">
            <div class="col-6">
                    <p> Your cash: $<?php

                        if($_SESSION['cash'] == null){
                            $_SESSION['cash'] = 100;
                            echo $_SESSION['cash'];
                        }else {
                            echo $_SESSION['cash'];
                            if (!empty($_SESSION['warning'] )) {
                                echo $_SESSION['warning'];
                            }
                        }
                    ?></p>
            </div>
            <div class="col-6 text-right">
                <span class="text-right" id="EmpytCart"><a href="index.php?action=empty">Empty Cart <i class="fas fa-trash-alt"></i></a></span>       
            </div>
        </div>

        <!-- table wiht product in the cart -->
        <div class="row">
            <?php
                if(isset($_SESSION["cart_item"])){
                    $total_quantity = 0;
                    $total_price = 0; 
            ?>
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
                            <td scope="col">
                            
                                <input 
                                class="input-update-item" 
                                type="number"
                                data-href="index.php?action=add&code="
                                name="quantity"
                                min="1" 
                                max="100"
                                id="product_<?php echo $item["code"]; ?>"
                                value="<?php echo $item["quantity"]; ?>"
                                data-value="<?php echo $item["quantity"]; ?>"
                                data-id="<?php echo $item["code"]; ?>"
                                >

                            </td>
                            <td scope="col"><?php echo "$ ". number_format($item_price,2); ?></td>
                            <td scope="col"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction" ><i class="fas fa-trash-alt"></i></a></td>
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
                            </tr>
                    </tbody>
                </table>
            </div>
            <?php
            } else {
            ?>
        </div>
        <div class="row">
            <div class="col text-center">
                <span>Your Cart is Empty</span>
            </div>    
            <?php
            }
            ?>
        </div>

        <!-- Shipping and payment -->
        <div class="row">
            <div class="col">
                <form method="post" action="index.php?action=pay">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Select shipping</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="shipping" required>
                        <option value="">None</option>
                        <option value="0"><a href="index.php"> up - free</a></option>
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
    <!--end container-->
<script>
    // cambio input-update-item
    $(".input-update-item").on('change', function(e){
		e.preventDefault();
		var id = $(this).data('id');
        var href = $(this).data('href');
        console.log(href + id)
		var quantity =  $('#product_' + id ).val();

		if (quantity == 0) {
			alert('la cantidad no puede ser 0');
			$(this).val('1');
		}else{
            // $.post( href , {
            //     quantity : quantity
            // },function(resp,estado,jqXHR) {
            //     console.log(resp);
            //     console.log(estado);
            //     console.log(jqXHR);  
            // });
			if (true) {}

			window.location.href = href + id + "/" + quantity;
		}
	});
</Script>
</body>
</html>