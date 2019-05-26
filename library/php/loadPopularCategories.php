<?php

use function arabcci_chamber_eshop\queryShopDB_PDO;

function getPCatInfo()
{
    $sql ="SELECT * FROM `categories` ORDER BY `categories`.`hitrate` DESC LIMIT 7";
    $pCatArr=queryShopDB_PDO($sql, null);
    return $pCatArr;
}