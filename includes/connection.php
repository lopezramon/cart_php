<?php

    function connectDB() {
        $server = "localhost";
        $user   = "root";
        $pswd   = "123456";
        $db     = "cart";
        
        //connect to mysql
        $this->conn = mysqli_connect($server,$user,$pswd,$db);
        
        if (!$conn) {
            echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
            echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
            echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }   

    
		// $conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		// $conn = mysqli_connect($this->connection);
        return $conn;
	}

    
    mysqli_close($enlace);

?>