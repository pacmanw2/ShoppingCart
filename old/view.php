<!DOCTYPE html>
<html>
<head>
    <style>

        th{
            background-color: white;
            color: red;
        }
        body {
            background-color: black;
            color: white;
            font-family: sans-serif;
        }



    </style>
</head>
<body>
<img src="root/img/milpaBanner.jpg" width=728 height="128" border="0" >

<form action="view.php", method="post">
    <input type="submit" name="invoice" value="Generate Invoice">
</form>

</body>
</html>



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

    if (isset($_POST['invoice']))
    {

        //forEachSelect($conn);
        invoice($conn);
        //echo "in the if";
    }
//    else
//    {
//        echo"<h1>Missed the if </h1>";
//    }
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

function invoice($conn) {
    $stmt = $conn->query('SELECT CustNum, CustName, OrderNum, OrderDate,Status,
                            Street, City, State, Zip 
                            FROM Customer Natural Join Invoice');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $totalWeight = 0.0;
    $totalSales = 0.0;
    $totalItems = 0;
    $totalOrders = 0;

    foreach ($results as $row) {
        //echo "<br>" . $row['CustNum'] . "-" . $row["OrderNum"]. "-" . $row["OrderDate"];
        $firstRowContent = array($row['CustNum'], $row['CustName'], $row['OrderNum'], $row['OrderDate']);
        buildCustomerTable($firstRowContent);

        $address = array($row['Street'],$row['City'],$row['State'],$row['Zip']);
        buildAddress($address);

        buildItemTable();

        $orderCall = $conn->query(' SELECT SKU, Description, OrdQty,UnitWeight,UnitPrice 
                                FROM InvoiceLineItem Natural Join Customer Natural Join Inventory
                                WHERE CustNum = ' . $row['CustNum'] . ' AND OrderNum = ' . $row['OrderNum']);
        $orderResults = $orderCall->fetchAll(PDO::FETCH_ASSOC);

        $orderTotal = 0.0;
        /*Build the item, one per row*/
        foreach ($orderResults as $orderRow) {
            $rowContents = array($orderRow['SKU'], $orderRow['Description'], $orderRow['UnitPrice'], $orderRow['OrdQty'], $orderRow['UnitWeight']);
            /*returns extended price/weight*/
            $totalFields = buildItem($rowContents);
            $totalSales += $totalFields[0];
            $totalWeight += $totalFields[1];
            $totalItems += 1;
            $orderTotal += $totalFields[0];
        }
        $totalOrders += 1;
        $orderTotal = number_format($orderTotal,2);
        buildOrderTotal($orderTotal);


    }
    buildInvoiceTotal(array($totalOrders, $totalItems, $totalWeight, $totalSales));
}

/*
    SELECT CustNum, CustName, OrderNum, OrderDate,Status,
    Street, City, State, Zip
    FROM Customer Natural Join Invoice
*/

//function buildItemDetails($conn) {
//    echo "For loop:<br>";
//    $stmt = $conn->query('SELECT SKU, Description, QOH,UnitWeight,UnitPrice
//                          FROM InvoiceLineItem Natural Join Customer Natural Join Inventory
//                          WHERE CustNum = 502');
//    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//    $extendedWeight = 0;
//    $extendedPrice = 0;
//    foreach ($results as $row) {
//        //echo "<br>" . $row['CustNum'] . " " . $row["OrderNum"]. "-" . $row["OrderDate"];
//        $rowContents = array($row['SKU'], $row['Description'], $row['QOH'], $row['UnitWeight'], $row['UnitPrice']);
//        $items = buildItem($rowContents);
//        $extendedPrice += $items[0];
//        $extendedWeight += $items[1];
//    }
//    return array($extendedPrice, $extendedWeight);
//}

function buildCustomerTable($info) {
    echo"<table style=\"width:90%\">";
    echo"<tr>";
    echo"<th align=\"left\">Customer: $info[0] - $info[1]</th>";
    echo"<th align=\"center\">Order Number: $info[2]</th>";
    echo"<th>Order Date: $info[3]</th>";
    echo"</tr>";
    //echo"</table>";
}

function buildAddress($address) {
    echo"<tr>";
    echo"<td align=\"left\">$address[0]</td>";
    echo"</tr>";

    echo"<tr>";
    echo"<td align=\"left\">$address[1], $address[2] $address[3]</td>";
    echo"</tr>";
}

function buildItemTable() {
    echo "<table style=\"width:90%\">";/* open table */
    echo "<tr>";
    echo "<th align=\"right\">Item</th>";
    echo "<th align=\"left\">Description</th>";
    echo "<th align=\"right\">Price</th>";
    echo "<th align=\"right\">Quantity</th>";
    echo "</tr>";
}

function buildItem($rowContents) {
    $rowContents[2] = number_format($rowContents[2],2);
    $extendedPrice = $rowContents[2] * $rowContents[3];
    $extendedPrice = number_format($extendedPrice,2);
    $extendedWeight = $rowContents[3] * $rowContents[4];
    echo"<tr>";
    echo"<td align=\"right\">$rowContents[0]</td>";
    echo"<td>$rowContents[1]</td>";
    echo"<td align=\"right\">$rowContents[2]</td>";
    echo"<td align=\"right\">$rowContents[3]</td>";//----------------------
    echo"<td align=\"right\">$rowContents[4]</td>";
    echo"<td align=\"right\">$extendedPrice</td>";
    echo"<td align=\"right\">$extendedWeight</td>";
    echo"</tr>";
    return array($extendedPrice, $extendedWeight);
}

function buildOrderTotal($total) {
    echo"<tr>";
    echo"<td align=\"right\"></td>";
    echo"<td></td>";
    echo"<td align=\"right\"></td>";
    echo"<td align=\"right\"></td>";
    echo"<td align=\"right\"></td>";
    echo"<td align=\"right\"></td>";
    echo"<td align=\"right\"></td>";
    echo"<td align=\"right\">$$total</td>";
    echo"</tr>";
    echo "</table>";/* close table */
    echo "<br><br><br><br>";
}

function buildInvoiceTotal($totals) {
    $sales = number_format($totals[3],2);
    echo"<table style=\"width:90%\">";
    echo"<tr>";
    echo"<th align=\"center\">Totals</th>";
    echo"<th align=\"center\">Orders</th>";
    echo"<th align='right'>Items</th>";
    echo"<th align='left'>Weight</th>";
    echo"<th align='right'>Sales</th>";
    echo"</tr>";

    echo"<tr>";
    echo"<td ></td>";
    echo"<td align='right'>$totals[0]</td>";
    echo"<td align=\"right\">$totals[1]</td>";
    echo"<td align=\"left\">$totals[2]</td>";
    echo"<td align=\"right\">$$sales</td>";
    echo"</tr>";

    echo"</table>";
}



//echo"<table style=\"width:90%\">";
//echo"<tr>";
//echo"<th align=\"left\">Customer: </th>";
//echo"<th align=\"center\">Order Number: </th>";
//echo"<th>Order Date: </th>";
//echo"</tr>";
//echo"</table>";

/*
 * echo street <br>
 * echo City, State Zip<br>
 * */

//echo "<table style=\"width:90%\">";/* open table */
//echo "<tr>";
//echo "<th align=\"right\">SKU</th>";
//echo "<th align=\"left\">Description</th>";
//echo "<th align=\"right\">Price</th>";
//echo "<th align=\"right\">Quantity</th>";
//echo "<th align=\"right\">Weight</th>";
//echo "<th align=\"right\">Extended Price</th>";
//echo "<th align=\"right\">Extended Weight</th>";
//echo "<th align=\"right\">Total</th>";
//echo "</tr>";


//echo"<tr>";
//echo"<td align=\"right\">APPL</td>";
//echo"<td>iPhone</td>";
//echo"<td align=\"right\">$199</td>";
//echo"<td align=\"right\">1</td>";
//echo"<td align=\"right\">3</td>";
//echo"<td align=\"right\">$230</td>";
//echo"<td align=\"right\">5</td>";
//echo"</tr>";

//echo"<tr>";
//echo"<td align=\"right\"></td>";
//echo"<td></td>";
//echo"<td align=\"right\"></td>";
//echo"<td align=\"right\"></td>";
//echo"<td align=\"right\"></td>";
//echo"<td align=\"right\"></td>";
//echo"<td align=\"right\"></td>";
//echo"<td align=\"right\">$230</td>";
//echo"</tr>";
//echo "</table>";/* close table */

//echo "<td align='R'>";




?>