
/**
 * Created by IntelliJ IDEA.
 * User: marco
 * Date: 2/26/2018
 * Time: 12:00 AM
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

<form action=""SalesReport.php" method="post">

<input type="submit" name="getSalesReport" value="Get Sales Report">

</form>




<?php
$serverName = "marcogallegosnet.ipagemysql.com";
$userName = "marco";
$password = "password";
$database = "sales";

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$database", $userName, $password);
    //set the pdo error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully <br>";

    if (isset($_POST['getSalesReport']))
    {
        //forEachSelect($conn);
        getSalesReport($conn);
       //echo "in the if";
    }
    else
    {
        //echo"Missed the if <br>";
    }
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

function getSalesReport($conn) {
    echo "For loop:<br>";
    $stmt = $conn->query('SELECT CustNum, CustName, OrderNum, OrderDate,Status,
                            Street, City, State, Zip 
                            FROM Customer Natural Join Invoice');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "<br>" . $row['CustNum'] . "-" . $row['CustName'] . "-" . $row["OrderNum"]. "-" . $row["OrderDate"];
    }
}
/*
    SELECT CustNum, CustName, OrderNum, OrderDate,Status,
    Street, City, State, Zip
    FROM Customer Natural Join Invoice
*/


?>

</body>
</html>
