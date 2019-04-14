<?php
function getPCatInfo()
{
    $conn= connectShopDB();

    $pCatArr = array();
    $sql ="SELECT * FROM `categories` ORDER BY `categories`.`hitrate` DESC LIMIT 7";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($pCatArr, $row);
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    return $pCatArr;
}