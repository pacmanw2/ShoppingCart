<?php

	$serverName = "marcogallegosnet.ipagemysql.com";
	//$serverName = "127.0.0.1";
	$userName = "marco";
	$password = "password";
	$database = "tester";

	//create connection...
	$conn  = new mysqli($serverName, $userName, $password, $database);

	//check connection
	if($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	echo "Connection succefully<br>";
	$sql = "SELECT * FROM Customer";
	$result = $conn->query($sql);

	//get some data...
	if($result->num_rows > 0) {
		echo "<h2>Customer records:</h2>";
		echo "<table><tr><td>Number</td><td>Name</td><td>Street</td><td>City</td><td>State</td><td>Zip</td></tr>";
		//output data of each row...
		while($row = $result->fetch_assoc()) {
			echo "<tr><td>" . $row["CustNum"]."</td><td>".$row["CustName"]."</td><td>".$row["Street"]."</td><td>".$row["City"]."</td><td>".$row["State"]."</td><td>".$row["Zip"]."</td></tr>";
		}
		echo"</table>";
	} else {
		echo "0 results";
	}
	//Cleanup...
	$conn = null;

?>