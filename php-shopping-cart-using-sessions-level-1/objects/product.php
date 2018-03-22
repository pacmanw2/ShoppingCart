<?php
// 'product' object
class Product{

    // database connection and table name
    public $conn;
    private $table_name="products";



    // object properties
    public $id;
    public $name;
    public $price;
    public $description;
    public $category_id;
    public $category_name;
    public $timestamp;
    public $extendedPrice;

    // constructor
    public function __construct($db){
        $this->conn = $db;

    }

    // read all products
    function read($from_record_num, $records_per_page){
        if($this->conn == null) {
            echo "connection in product.php is null. getting new connection";
            // get database connection
            $database = new Database();
            $this->conn = $database->getConnection();
        }
        $stmt = $this->conn->query('SELECT id, name, description, price
                                     FROM products');

//        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $count = count($results);


        // bind limit clause variables
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        //return $count;
        // execute query
        $stmt->execute();

        // return values
        return $stmt;
    }

// used for paging products
    public function count(){

        // query to count all product records
        $query = "SELECT count(*) FROM " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // execute query
        $stmt->execute();

        // get row value
        $rows = $stmt->fetch(PDO::FETCH_NUM);

        // return count
        return $rows[0];
    }

    // read all product based on product ids included in the $ids variable
// reference http://stackoverflow.com/a/10722827/827418
    public function readByIds($ids){

        $ids_arr = str_repeat('?,', count($ids) - 1) . '?';

        // query to select products
        $query = "SELECT id, name, price FROM " . $this->table_name . " WHERE id IN ({$ids_arr}) ORDER BY name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute($ids);

        // return values from database
        return $stmt;
    }

    // used when filling up the update product form
    function readOne(){

        // query to select single record
        $query = "SELECT
                name, description, price
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind product id value
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get row values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign retrieved row value to object properties
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->price = $row['price'];
        //$this->extendedPrice = $row['quantity'] * $row['price'];
    }

    function getOrderNumber($companyName)
    {
        $p_ini = parse_ini_file("../config.ini", true);

        $servername = $p_ini['Database']['servername'];
        $username = $p_ini['Database']['username'];
        $password = $p_ini['Database']['password'];
        $database = "raysmusic";

        try
        {

            //$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $this->conn->prepare("LOCK TABLE NextOrderNumber WRITE;");
            $stmt->execute();

            $stmt = $this->conn->prepare("SELECT NextOrder FROM NextOrderNumber WHERE CompanyName = $companyName;");
            $stmt->execute();

            $orderNum = -1;
            if ($stmt->rowCount() == 1)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $orderNum = intval($row['NextOrder']);
                $nextNum = $orderNum + 1;

                $stmt = $this->conn->prepare("UPDATE NextOrderNumber SET NextOrder = ? WHERE CompanyName = $companyName;");
                $stmt->bindValue(1, strval($nextNum), PDO::PARAM_STR);
                $stmt->execute();
            }
            $stmt = $this->conn->prepare("UNLOCK TABLES;");
            $stmt->execute();
            return $orderNum;
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
        $conn = null;
        return 0;
    }
}