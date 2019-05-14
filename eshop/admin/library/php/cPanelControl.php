<?php
include_once("arabcci_login.php");

use Arabcci_Chamber_Login\AdminCheck_DBInfo;
use Arabcci_Chamber_Login\EncryptionSession;

//userName
function loadCMSIndex()
{
    if (!$_REQUEST["userName"]||!$_REQUEST["token"]) {
        //echo 'wrong http query';
        header('Location: '.'../CMSlogin.php');
        die();
    }
    //check http referer
    if ($_SERVER['HTTP_REFERER']!='http://localhost/eshop/admin/CMSlogin.php') {
        echo 'Please enter CMS from login page';
        header('Location: '.'../CMSlogin.php');
        //echo "\n".$_SERVER['HTTP_REFERER'];
        die();
    }
    //check ajax call type
    /*
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        die();
    }
    */
    
    //verify token
    $connInfo=new AdminCheck_DBInfo('ashop_userCheck', $_REQUEST['userName']);
    
    //SELECT * FROM `token` WHERE `userName`='admin'
    $sql ="SELECT * FROM token WHERE userName=?";
    $result=$connInfo->queryDB_PDO($sql, $_REQUEST['userName']);
    
    //decript user token
    $enSession=new EncryptionSession();
    
    
    $inputToken = $enSession->decryptedInfo($_REQUEST['token'], $result[0]['tokenKey'], $result[0]['tokenIV']);

    
    if ($inputToken!=$result[0]['tokenValue']) {
        //wrong token
        echo 'wrong value query';
       
        die();
    } else {
        $connInfo=new AdminCheck_DBInfo('ashop_userCheck', $_REQUEST['userName']);
        $sql="DELETE FROM token WHERE userName=?";
        $result=$connInfo->alterDB_PDO($sql, $_REQUEST['userName']);
    }
}
function loadProductPanel()
{
    if (!$_REQUEST["userName"]) {
        header('Location: '.'../CMSlogin.php');
        die();
    }
    
    //check http referer
    //http://localhost/eshop/admin/CMSlogin.php
    $refererArr=explode('?', $_SERVER['HTTP_REFERER']);
    if (empty($_SERVER['HTTP_REFERER'])||$refererArr[0]!="http://localhost/eshop/admin/layouts/cPanel.php") {
        echo 'wrong source ';
        //echo $_SERVER['HTTP_REFERER'];
        header('Location: '.'../CMSlogin.php');
        die();
    }

    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        header('Location: '.'../CMSlogin.php');
        die();
    }
}
function checkHTTP_Referer($userURL)
{
    //print_r($_SERVER['HTTP_REFERER']); //debug

    //http://localhost/eshop/admin/CMSlogin.php
    //check user source url
    
    if (!empty($userURL) && $userURL=="http://localhost/eshop/admin/CMSlogin.php") {
        return true;
    } else {
        return false;
    }
}


function printPListHTML()
{
    //select products list

    $connInfo=new AdminCheck_DBInfo('ashop', $_REQUEST['userName']);
    $sql="select * from products";
    $pResult=$connInfo->queryDB_PDO($sql, $_REQUEST['userName']);

    $pListHtml='';
    foreach ($pResult as $row) {
        $pListHtml.="\n<tr>";
        $pListHtml.="\n<td>".$row['productCode']."</td>";
        $pListHtml.="\n<td>".$row['productName']."</td>";
        $pListHtml.="\n<td>".$row['categoriesName']."</td>";
        $pListHtml.="\n<td>".$row['vendorName']."</td>";
        $pListHtml.="\n<td>".$row['productDescription']."</td>";
        $pListHtml.="\n<td>".$row['quantityInStock']."</td>";
        $pListHtml.="\n<td>".$row['buyPrice']."</td>";
        $pListHtml.="\n<td>".$row['MSRP']."</td>";
        $pListHtml.="\n<td>".$row['discount(%)']."</td>";
        $pListHtml.="\n<td>".$row['attributes']."</td>";
        $pListHtml.="\n<td>".$row['Image']."</td>";
        $pListHtml.="\n<td>".$row['hitrate']."</td>";

        $pListHtml.="\n</tr>";
    }

    return $pListHtml;
}

function printCategoriesOpts()
{
    $connInfo=new AdminCheck_DBInfo('ashop', $_REQUEST['userName']);
    $sql="select categoriesName from categories order by categoriesName asc";
    $cResult=$connInfo->queryDB_PDO($sql, $_REQUEST['userName']);

    $catListHtml='';
    for ($i=0;$i<count($cResult);$i++) {
        $value=$i+1;
        $catListHtml.="<option value='".$value."'>".$cResult[$i]['categoriesName']."</option>";
    }
    return  $catListHtml;
}

function printVendorsOpts()
{
    $connInfo=new AdminCheck_DBInfo('ashop', $_REQUEST['userName']);
    $sql="SELECT vendorName FROM vendors ORDER BY vendorName ASC";
    $cResult=$connInfo->queryDB_PDO($sql, $_REQUEST['userName']);

    $catListHtml='';
    for ($i=0;$i<count($cResult);$i++) {
        $value=$i+1;
        $catListHtml.="<option value='".$value."'>".$cResult[$i]['vendorName']."</option>";
    }
    return  $catListHtml;
}