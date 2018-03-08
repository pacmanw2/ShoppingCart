
/**
 * Created by IntelliJ IDEA.
 * User: marco
 * Date: 2/22/18
 * Time: 10:12 AM
 */
 
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html;charsetutf-8" http-equiv="Content-Type">
        <meta charset="utf-8" />
    <title> PDO </title>
</head>
<body>
<br>
<br><h3>Simple examples - connecting to MySQL with PDO...</h3><br>

<form action=""MySQLPDO.php" method="post">

    <input type="submit" name="getPDOForEach" value="ForEach PDO select">
    <input type="submit" name="getPDOWhile" value="While PDO select">
    <input type="submit" name="getWithParm" value="Get a record - with parm">
    <input type="text" name="getID" value="<?php echo $_POST['getID'];?>">

</form>




<?php
// $p_ini = parse_ini_file("config.ini", true);
// $serverName = $p_ini['Database']['marcogallegosnet.ipagemysql.com'];
// $userName = $p_ini['Database']['marco'];
// $password = $p_ini['Database']['password'];
// $databse = "tester";
$serverName = "marcogallegosnet.ipagemysql.com";
$userName = "marco";
$password = "password";
$database = "tester";

//print_r(parse_ini_file("config.ini",true));
try {
   $conn = new PDO("mysql:host=$serverName;dbname=$database", $userName, $password);
   //set the pdo error mode to exception
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   echo "Connected successfully <br>";

   if (isset($_POST['getPDOForEach']))
   {
       forEachSelect($conn);
   }
   elseif (isset($_POST['getPDOWhile']))
   {
       whileSelect($conn);
   }
   elseif (isset($_POST['getWithParm']))
   {
   	   //echo "POST: " . intval($_POST['getID']);
       getWithParm($conn, intval($_POST['getID']));
   }
   else 
   {
   	echo"Missed all if/else <br>";
   }
}
catch(PDOException $e)
{
   echo "Connection failed: " . $e->getMessage();
}

function forEachSelect($conn) {
	echo "For loop:<br>";
	$stmt = $conn->query('SELECT * FROM Customer');
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($results as $row) {
		echo "<br>" . $row['CustNum'] . "-" . $row['CustName'];
	}
}

function whileSelect($conn) {
	echo "While loop:<br>";
	$stmt = $conn->query('Select * From Customer');
	//not difference in the fetch vs fetchAll...
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		echo '<br>' . $row['CustNum'].' '.$row['CustName'].' '.$row['City'];
	}
}

function getWithParm($conn, $id) {
	echo "Get a single record with a prepared parameter:<br>";
	
	$stmt = $conn->prepare("SELECT * FROM Customer WHERE CustNum=?");
	$stmt->bindValue(1, $id, PDO::PARAM_INT);
	$stmt->execute();
	if ($stmt->rowCount() == 1) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		echo "<br>-should be only one record (primary key)- " . $row['CustNum']." ".$row['CustName'];
	}
	else {
		echo "Record not found for " . $id;
	}
}

?>

</body>
</html>