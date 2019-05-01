<?php
use function arabcci_chamber_eshop\queryShopDB_PDO;

function getBannerFeaturedInfo()
{
    //$sql = "SELECT * FROM `featuredinfo` WHERE `attribute`= 'banner'";
    $sql ="SELECT * FROM featuredinfo WHERE attribute=?";
    
    $fInfoArr=queryShopDB_PDO($sql, 'banner');
    
    return $fInfoArr;
}

function getTodayFeaturedInfo()
{
    $sql ="SELECT * FROM featuredinfo WHERE attribute=?";
    $fInfoArr=queryShopDB_PDO($sql, 'today');
    
    return $fInfoArr;
}