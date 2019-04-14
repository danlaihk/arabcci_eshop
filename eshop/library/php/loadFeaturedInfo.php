<?php
function getBannerFeaturedInfo()
{
    $conn= connectShopDB(); //included in loadHeader.php
    $fInfoArr= array();

    $sql = "SELECT * FROM `featuredinfo` WHERE `attribute`= 'banner'";

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($fInfoArr, $row);
        }
    } else {
        echo '0 results';
    }

    $conn->close();
    return $fInfoArr;
}

function getTodayFeaturedInfo()
{
    $conn= connectShopDB(); //included in loadHeader.php
    $fInfoArr= array();

    $sql = "SELECT * FROM `featuredinfo` WHERE `attribute`= 'today'";

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($fInfoArr, $row);
        }
    } else {
        echo '0 results';
    }

    $conn->close();
    return $fInfoArr;
}