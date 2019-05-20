<?php
include_once("arabcci_login.php");

use Arabcci_Chamber_Login\AdminCheck_DBInfo;
use Arabcci_Chamber_Login\EncryptionSession;

//userName
function loadCMSIndex()
{
    session_start();



    if (isset($_REQUEST['action'])) {
        //logout query
        if ($_REQUEST['action']=='logout') {
            session_destroy();
            echo 'ok';
            exit(0);
        }
    }
    if (!$_SESSION['userName']||!$_SESSION['token']) {
        //echo 'wrong http query';
        header('Location: '.'../CMSlogin.php');
        die();
    }
    if (isset($_SESSION['login_status'])) {
        if ($_SESSION['login_status']=="logined") {
            //pass
        } else {
            header('Location: '.'../CMSlogin.php');
            die();
        }
    } else {
        //if no login status , run checking

        //check http referer
        if ($_SERVER['HTTP_REFERER']!='http://localhost/eshop/admin/CMSlogin.php') {
            echo 'Please enter CMS from login page';
            header('Location: '.'../CMSlogin.php');
            //echo "\n".$_SERVER['HTTP_REFERER'];
            die();
        }

        $userName=$_SESSION['userName'];
        //verify token
        $connInfo=new AdminCheck_DBInfo('ashop_userCheck', $userName);

        //SELECT * FROM `token` WHERE `userName`='admin'
        $sql ="SELECT * FROM token WHERE userName=?";
        $result=$connInfo->queryDB_PDO($sql, $userName);

        //decript user token
        $enSession=new EncryptionSession();


        $inputToken = $enSession->decryptedInfo($_SESSION['token'], $result[0]['tokenKey'], $result[0]['tokenIV']);


        if ($inputToken!=$result[0]['tokenValue']) {
            //wrong token
            echo 'wrong value query';

            exit(0);
        }
        

        $connInfo=new AdminCheck_DBInfo('ashop_userCheck', $userName);
        $sql="DELETE FROM token WHERE userName=?";
        $result=$connInfo->alterDB_PDO($sql, $userName);

        $_SESSION['login_status']="logined";
    }
}
function loadProductPanel()
{
    session_start();
    $userName=$_SESSION['userName'];

    if ($_SESSION['login_status']!="logined") {
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
    
    //check call type as ajax
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        header('Location: '.'../CMSlogin.php');
        die();
    }


    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action']=='add') {

            //debug
            $insertArr=array_slice($_REQUEST, 1);
           

            $statementArr=array();
            $ptableMap=array(
                'inputPCode'=>'productCode',
                'inputPName'=>'productName'
            );
            //handle request array
            foreach ($insertArr as $key=>$value) {
                if ($key=='inputPCode'||$key=='inputPName') {
                    $statementArr[$ptableMap[$key]]=$value;
                }
            }

            $checkResult=checkDuplicate($statementArr, 'products');

            if ($checkResult==true) {
                //if there is duplicate item
                echo 'duplicate';
                exit();
            }

            //image handle
            //9 input without image

            //stock equal to the total of attribute stock
           
            //hitrate = 0

            //12input

            //$result=queryDB($statementArr, 'products');
            //debug
           
                                  
            /*
            $connInfo=new AdminCheck_DBInfo('ashop', $userName);
            $sql="select categoriesName from categories order by categoriesName asc";
            $categoriesMap=$connInfo->queryDB_PDO($sql, $userName);

            //value 1 = result 0
            //12 para
            $sqlInsert="INSERT INTO products (productCode, productName, categoriesName, vendorName, productDescription,quantityInStock,buyPrice,MSRP,discount(%),attributes,Image,hitrate) VALUES (?, ?, ?, ?, ?)";
            $changeTokenConn = new AdminCheck_DBInfo('ashop_userCheck', $userName);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlInsert, $insertArr);
            */

            //need to return something
                
            echo 'passed';
            exit();
        }
    }
}

function checkDuplicate($query, $tableName)
{
    //check is there any pcode duplicate
    $colName=array_keys($query);
   
    //$sql="select productCode,productName from products where productCode=? or productName=? order by productCode asc";
    end($query);//move pointer to the last element
    $lastkey= key($query);
    $sql="SELECT ";

    foreach ($query as $key=>$value) {
        //productCode,productName
        if ($key==$lastkey) {
            $sql.=$key.' ';
        } else {
            $sql.= $key.', ';
        }
    }

    $sql.=' FROM '.$tableName.' WHERE ';

    foreach ($query as $key=>$value) {
        //productCode=? or productName=?';
        if ($key==$lastkey) {
            $sql.=$key.' = ?';
        } else {
            $sql.= $key.' = ? || ';
        }
    }

    $queryValue=array();

    foreach ($query as $key=>$value) {
        //value of '?'
        array_push($queryValue, $value);
    }

 
    $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
    

    //$sql='SELECT productCode, productName FROM products WHERE productCode = ? || productName = ?';
    //[0=>'Competition0001',1=>1]
    $result=$connInfo->queryDB_PDO($sql, $queryValue);

    if (count($result)!=0) {
        return true;
    } else {
        return false;
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
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="select * from products";
    $pResult=$connInfo->queryDB_PDO($sql, $userName);

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
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="select categoriesName from categories order by categoriesName asc";
    $cResult=$connInfo->queryDB_PDO($sql, $userName);

    $catListHtml='';
    for ($i=0;$i<count($cResult);$i++) {
        $value=$i+1;
        $catListHtml.="<option value='".$value."'>".$cResult[$i]['categoriesName']."</option>";
    }
    return  $catListHtml;
}

function printVendorsOpts()
{
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="SELECT vendorName FROM vendors ORDER BY vendorName ASC";
    $cResult=$connInfo->queryDB_PDO($sql, $userName);

    $catListHtml='';
    for ($i=0;$i<count($cResult);$i++) {
        $value=$i+1;
        $catListHtml.="<option value='".$value."'>".$cResult[$i]['vendorName']."</option>";
    }
    return  $catListHtml;
}