<?php
    require("includes/connection.php");
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
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">1</td>
                        <td>Apple</td>
                        <td>$0.3</td>
                        <td><a href="#">Add to Cart</a></td>
                    </tr>
                    <tr>
                        <td scope="row">2</td>
                        <td>beer</td>
                        <td>$2</td>
                        <td><a href="#">Add to Cart</a></td>
                    </tr>
                    <tr>
                        <td scope="row">3</td>
                        <td>water</td>
                        <td>$1</td>
                        <td><a href="#">Add to Cart</a></td>
                    </tr>
                    <tr>
                        <td scope="row">4</td>
                        <td>cheese</td>
                        <td>$3.74</td>
                        <td><a href="#">Add to Cart</a></td>
                    </tr>
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