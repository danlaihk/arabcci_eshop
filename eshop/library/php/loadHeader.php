<?php
include('library/php/connectShopDB.php');

function getProductLineInfo()
{
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
}
function getCategoriesInfo($cat)
{
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
}