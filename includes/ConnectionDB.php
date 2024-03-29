<?php

class DBController 
{
	private $host = "localhost";
	private $user = "root";
	private $password = "123456";
	private $database = "cart";
	public $conn;
	
	function __construct() {
		$this->conn = $this->connectDB();
	}
	/**
	 * 
	 * Connection data base
	 * @return array
	 */
	public function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	/**
	 * 
	 * execute query
	 * @return array
	 */
	public function runQuery($query) {
		$result = mysqli_query($this->conn,$query) or die("Problemas en el select".mysqli_error($this->conn));
		while($row=mysqli_fetch_assoc($result)) 
		{
			$resultset[] = $row;
		}		
		if(!empty($resultset))
		return $resultset;
	}

	public function disableConextion(Type $var = null)
	{
		mysqli_close($this->conn);
	}
}
?>