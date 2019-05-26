<?php
//include('library/php/connectShopDB.php');
use function arabcci_chamber_eshop\queryShopDB_PDO;

function getProductLineInfo()
{
    /*
    $conn = connectShopDB();

    $pLineArr = array();

    $sql = "SELECT * FROM productlines";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($pLineArr, $row);
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    return $pLineArr;
    */

    //pdo method
    $sql = "SELECT * FROM productlines";
    $pLineArr=queryShopDB_PDO($sql, null);
    return $pLineArr;
}
function getCategoriesInfo($cat)
{
    /*
    $conn= connectShopDB();

    $catArr = array();
    $sql ="SELECT * FROM categories WHERE productLine = '".$cat."'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($catArr, $row);
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    return $catArr;
    */
    $sql ="SELECT * FROM categories WHERE productLine=?";
    $catArr=queryShopDB_PDO($sql, $cat);
    
    return $catArr;
}