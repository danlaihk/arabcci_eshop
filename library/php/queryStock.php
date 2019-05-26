<?php
include_once('eShopClass.php');
use arabcci_chamber_eshop\product;

//if condition shorthand
$pCodeStr = (isset($_REQUEST['product'])) ? $_REQUEST['product'] : '';
$attValueStr = (isset($_REQUEST['att']))?$_REQUEST['att']:'';



if ($pCodeStr!==null&&$attValueStr!==null) {
    //define a new product object
    $product = new product($pCodeStr);
    if ($attValueStr==='0') {
        echo 'Total stock :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$product->getPTotalStock();
    } else {
        $attArr=$product->getPAttributes();
        $attStrArr=explode('-', $attArr[intval($attValueStr)-1]);
        echo '<span id="productAtt">'.$attStrArr[0].'</span> stock: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$attStrArr[1].'';
    }
} else {
    //handling exceptions
    echo 'Sorry, invalid input';
}