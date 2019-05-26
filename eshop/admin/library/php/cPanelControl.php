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
        /*
        if ($_SERVER['HTTP_REFERER']!='http://localhost/eshop/admin/CMSlogin.php') {
            echo 'Please enter CMS from login page';
            header('Location: '.'../CMSlogin.php');
            //echo "\n".$_SERVER['HTTP_REFERER'];
            die();
        }
        */
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
    

    if ($_SESSION['login_status']!="logined") {
        header('Location: '.'../CMSlogin.php');
        die();
    }
 
    //check http referer
    //http://localhost/eshop/admin/CMSlogin.php
    /*
    $refererArr=explode('?', $_SERVER['HTTP_REFERER']);
    if (empty($_SERVER['HTTP_REFERER'])||$refererArr[0]!="http://localhost/eshop/admin/layouts/cPanel.php") {
        echo 'wrong source ';
        //echo $_SERVER['HTTP_REFERER'];
        header('Location: '.'../CMSlogin.php');
        die();
    }
    */
    //check call type as ajax
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        header('Location: '.'../CMSlogin.php');
        die();
    }
   

    //hand
    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action']=='delProduct') {

            //delete condition must be placed before the upload file handling

            //check the featureInfo table whether has product code
            $pCode= $_REQUEST['inputPCode'];
            
            //check any foreign key
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="SELECT productCode FROM featuredinfo where productCode=?";
            $result=$connInfo->queryDB_PDO($sql, $pCode);
            if (count($result)!=0) {
                echo 'usedByFeature';
                exit();
            }


            //delete record
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="DELETE FROM products WHERE productCode=?";
            $result=$connInfo->alterDB_PDO($sql, $pCode);

            echo 'deleted';
            exit();
        }
        
        //get cat list
        $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
        $sql="select categoriesName from categories order by categoriesName asc";
        $cResult=$connInfo->queryDB_PDO($sql, $_SESSION['userName']);
      
        foreach ($cResult as $key=>$row) {
            //value 1 is first result
            $catMap[$key+1]= $row['categoriesName'];
        }
        //get ven list
        $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
        $sql="SELECT vendorName FROM vendors ORDER BY vendorName ASC";
        $vResult=$connInfo->queryDB_PDO($sql, $_SESSION['userName']);
      
        foreach ($vResult as $key=>$row) {
            $vendorMap[$key+1]= $row['vendorName'];
        }
    
        $ptableMap=array(
        'inputPCode'=>'productCode',
        'inputPName'=>'productName'
        );

        //calculate total stock
        $totalStock=0;
        $inputAttrArr=explode('|', $_REQUEST['inputAttrs']);

        //att1-50|att2-32|att3
        foreach ($inputAttrArr as $attrInfo) {
            $attrInfoArr= explode('-', $attrInfo);
            if (count($attrInfoArr)<2) {
                $totalStock+=0;
            } else {
                if (is_numeric($attrInfoArr[1])) {
                    $totalStock+=intval($attrInfoArr[1]);
                }
            }
        }
        
        //upload files
        try {
            if (file_exists($_FILES['pImageUpload']['tmp_name']) && is_uploaded_file($_FILES['pImageUpload']['tmp_name'])) {
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                !isset($_FILES['pImageUpload']['error']) ||
                is_array($_FILES['pImageUpload']['error'])
            ) {
                    throw new RuntimeException('Invalid parameters.');
                }
        
                // Check $_FILES['upfile']['error'] value.
                switch ($_FILES['pImageUpload']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
        
                // You should also check filesize here.
                if ($_FILES['pImageUpload']['size'] > 1000000) {
                    throw new RuntimeException('Exceeded filesize limit.');
                }
        
                // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($_FILES['pImageUpload']['tmp_name']),
                    array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                    true
            )) {
                    throw new RuntimeException('Invalid file format.');
                }
        
                $imgPath=sprintf(
                    '../../library/image/%s.%s',
                    sha1_file($_FILES['pImageUpload']['tmp_name']),
                    $ext
            );
                // You should name it uniquely.
                // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                if (!move_uploaded_file(
                    $_FILES['pImageUpload']['tmp_name'],
                    $imgPath
            )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }
        
                //echo 'File is uploaded successfully.';
            } else {
                echo 'ufileError';
                exit();
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        $imgPath=substr($imgPath, 6);


        //handle actions
        if ($_REQUEST['action']=='update') {
          
            //debug
            $insertArr=array_slice($_REQUEST, 1, 2); //start from second and total length is two
           

            $sql="select productCode from products where productCode=? ";
            $conn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $result= $conn->queryDB_PDO($sql, $_REQUEST['inputPCode']);
            
            if (count($result)<1) {
                //if there is no duplicate item
                echo 'noResult';
                exit();
            }
       
            
            $insertArr= array(
                $_REQUEST['inputPName'],
                $catMap[intval($_REQUEST['selectCategories'])],
                $vendorMap[intval($_REQUEST['selectVendor'])],
                $_REQUEST['inputDescript'],
                $totalStock,
                $_REQUEST['inputBuyprice'],
                $_REQUEST['inputMSRP'],
                $_REQUEST['inputDiscount'],
                $_REQUEST['inputAttrs'],
                $imgPath,
                $_REQUEST['inputPCode']
            );
            
            $sqlUpdate="UPDATE products SET productName = ?,categoriesName = ?,vendorName = ? ,productDescription = ?, quantityInStock = ?,buyPrice = ?,MSRP = ?,pdiscount = ?,attributes = ?,Image = ? WHERE productCode =?";
            $changeTokenConn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlUpdate, $insertArr);
            
            echo 'updateCompleted';
            //echo $catMap[intval($_REQUEST['selectCategories'])];
            exit();
        }

        if ($_REQUEST['action']=='add') {

            //debug
            $insertArr=array_slice($_REQUEST, 1);
           

            $statementArr=array();
          
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


          

         
            

            $insertArr= array(
                $_REQUEST['inputPCode'],
                $_REQUEST['inputPName'],
                $catMap[intval($_REQUEST['selectCategories'])],
                $vendorMap[intval($_REQUEST['selectVendor'])],
                $_REQUEST['inputDescript'],
                $totalStock,
                $_REQUEST['inputBuyprice'],
                $_REQUEST['inputMSRP'],
                $_REQUEST['inputDiscount'],
                $_REQUEST['inputAttrs'],
                $imgPath,
                0
            );
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sqlInsert="INSERT INTO products (productCode, productName, categoriesName, vendorName, productDescription,quantityInStock,buyPrice, MSRP, pdiscount, attributes,Image,hitrate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result=$connInfo->alterDB_PDO($sqlInsert, $insertArr);
            
            echo 'passed';

           
                
      
            exit();
        }
    }
}


function loadFeaturePanel()
{
    session_start();
    

    if ($_SESSION['login_status']!="logined") {
        header('Location: '.'../CMSlogin.php');
        die();
    }
 
    //check http referer
    //http://localhost/eshop/admin/CMSlogin.php
    /*
    $refererArr=explode('?', $_SERVER['HTTP_REFERER']);
    if (empty($_SERVER['HTTP_REFERER'])||$refererArr[0]!="http://localhost/eshop/admin/layouts/cPanel.php") {
        echo 'wrong source ';
        //echo $_SERVER['HTTP_REFERER'];
        header('Location: '.'../CMSlogin.php');
        die();
    }
    */
    //check call type as ajax
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        header('Location: '.'../CMSlogin.php');
        die();
    }
   

    //handle actions
    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action']=='delFeature') {

            //delete condition must be placed before the upload file handling
            $pCode= $_REQUEST['inputCode'];
       
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="DELETE FROM featuredinfo WHERE featureCode=?";
            $result=$connInfo->alterDB_PDO($sql, $pCode);

            echo 'deleted';
            exit();
        }
   
        //get pcode list
        $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
        $sql="select productCode from products order by productCode asc";
        $result=$connInfo->queryDB_PDO($sql, $_SESSION['userName']);
          
        foreach ($result as $key=>$row) {
            //value 1 is first result
            $pCodeMap[$key+1]= $row['productCode'];
        }
        $featurePosMap=array(1=>'banner',2=>'today');
        //upload files
        try {
            if (file_exists($_FILES['fImageUpload']['tmp_name']) && is_uploaded_file($_FILES['fImageUpload']['tmp_name'])) {
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                !isset($_FILES['fImageUpload']['error']) ||
                is_array($_FILES['fImageUpload']['error'])
            ) {
                    throw new RuntimeException('Invalid parameters.');
                }
        
                // Check $_FILES['upfile']['error'] value.
                switch ($_FILES['fImageUpload']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
        
                // You should also check filesize here.
                if ($_FILES['fImageUpload']['size'] > 1000000) {
                    throw new RuntimeException('Exceeded filesize limit.');
                }
        
                // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($_FILES['fImageUpload']['tmp_name']),
                    array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                    true
            )) {
                    throw new RuntimeException('Invalid file format.');
                }
        
                $imgPath=sprintf(
                    '../../library/image/features/%s.%s',
                    sha1_file($_FILES['fImageUpload']['tmp_name']),
                    $ext
            );
                // You should name it uniquely.
                // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                if (!move_uploaded_file(
                    $_FILES['fImageUpload']['tmp_name'],
                    $imgPath
            )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }
        
                //echo 'File is uploaded successfully.';
            } else {
                echo 'ufileError';
                exit();
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        $imgPath=substr($imgPath, 6);


        //handle actions
        if ($_REQUEST['action']=='update') {
          
            //debug
            //$insertArr=array_slice($_REQUEST, 1, 2); //start from second and total length is two
           

            $sql="select featureCode from featuredinfo where featureCode=? ";
            $conn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $result= $conn->queryDB_PDO($sql, $_REQUEST['inputCode']);
            
            if (count($result)<1) {
                //if there is no duplicate item
                echo 'noResult';
                exit();
            }
       
            
            $insertArr= array(
                $pCodeMap[intval($_REQUEST['selectPCode'])],
                $imgPath,
                $_REQUEST['inputDescript'],
                $featurePosMap[intval($_REQUEST['selectAttr'])],
                $_REQUEST['inputCode']

            );
            
            $sqlUpdate="UPDATE featuredinfo SET productCode = ?,imageURL = ?,featureText = ? ,attribute = ? WHERE featureCode =?";
            $changeTokenConn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlUpdate, $insertArr);
            
            echo 'updateCompleted';
            //echo $catMap[intval($_REQUEST['selectCategories'])];
            exit();
        }

        if ($_REQUEST['action']=='add') {

            //debug
            $insertArr=array_slice($_REQUEST, 1);
           

            $statementArr=array();
          
            //handle request array
           
            $sql="select featureCode from featuredinfo where featureCode=? ";
            $conn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $result= $conn->queryDB_PDO($sql, $_REQUEST['inputCode']);
            
            if (count($result)>0) {
                //if there is no duplicate item
                echo 'duplicate';
                exit();
            }

            $insertArr= array(
                $_REQUEST['inputCode'],
                $pCodeMap[intval($_REQUEST['selectPCode'])],
                $imgPath,
                $_REQUEST['inputDescript'],
                $featurePosMap[intval($_REQUEST['selectAttr'])]

            );
            
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sqlInsert="INSERT INTO featuredinfo (featureCode, productCode, imageURL, featureText, attribute) VALUES (?, ?, ?, ?, ?)";
            $result=$connInfo->alterDB_PDO($sqlInsert, $insertArr);
            
            echo 'passed';

            exit();
        }
    }
}


function loadVendorPanel()
{
    session_start();
    

    if ($_SESSION['login_status']!="logined") {
        header('Location: '.'../CMSlogin.php');
        die();
    }
 
    //check http referer
    //http://localhost/eshop/admin/CMSlogin.php
    /*
    $refererArr=explode('?', $_SERVER['HTTP_REFERER']);
    if (empty($_SERVER['HTTP_REFERER'])||$refererArr[0]!="http://localhost/eshop/admin/layouts/cPanel.php") {
        echo 'wrong source ';
        //echo $_SERVER['HTTP_REFERER'];
        header('Location: '.'../CMSlogin.php');
        die();
    }
    */
    //check call type as ajax
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'improper call';
        header('Location: '.'../CMSlogin.php');
        die();
    }
   

    //handle actions
    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action']=='delVendor') {

            //delete condition must be placed before the upload file handling
            $vName= $_REQUEST['inputName'];
            
            //check any foreign key
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="SELECT vendorName FROM products where vendorName=?";
            $result=$connInfo->queryDB_PDO($sql, $vName);
            if (count($result)>0) {
                echo 'usedByProducts';
                exit();
            }

            //find the vendor code by vendor name
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="SELECT vendorCode FROM vendors where vendorName=?";
            $result=$connInfo->queryDB_PDO($sql, $vName);
            if (count($result)<1) {
                echo 'noResult';
                exit();
            }
            $vCode=$result['vendorCode'];

            //delete record
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sql="DELETE FROM vendors WHERE vendorCode=?";
            $result=$connInfo->alterDB_PDO($sql, $vCode);

            echo 'deleted';
            exit();
        }
   
    
        //upload files
        try {
            if (file_exists($_FILES['vImageUpload']['tmp_name']) && is_uploaded_file($_FILES['vImageUpload']['tmp_name'])) {
                // Undefined | Multiple Files | $_FILES Corruption Attack
                // If this request falls under any of them, treat it invalid.
                if (
                !isset($_FILES['vImageUpload']['error']) ||
                is_array($_FILES['vImageUpload']['error'])
            ) {
                    throw new RuntimeException('Invalid parameters.');
                }
        
                // Check $_FILES['upfile']['error'] value.
                switch ($_FILES['vImageUpload']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }
        
                // You should also check filesize here.
                if ($_FILES['vImageUpload']['size'] > 1000000) {
                    throw new RuntimeException('Exceeded filesize limit.');
                }
        
                // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($_FILES['vImageUpload']['tmp_name']),
                    array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                    true
            )) {
                    throw new RuntimeException('Invalid file format.');
                }
        
                $imgPath=sprintf(
                    '../../library/image/features/%s.%s',
                    sha1_file($_FILES['vImageUpload']['tmp_name']),
                    $ext
            );
                // You should name it uniquely.
                // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
                // On this example, obtain safe unique name from its binary data.
                if (!move_uploaded_file(
                    $_FILES['vImageUpload']['tmp_name'],
                    $imgPath
            )) {
                    throw new RuntimeException('Failed to move uploaded file.');
                }
        
                //echo 'File is uploaded successfully.';
            } else {
                echo 'ufileError';
                exit();
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }

        $imgPath=substr($imgPath, 6);


        //handle actions
        if ($_REQUEST['action']=='update') {
          
            //debug
         
           
            $sql="select vendorCode,vendorName from vendors where vendorCode=? ";
            $conn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $result= $conn->queryDB_PDO($sql, $_REQUEST['inputCode']);
            
            if (count($result)<1) {
                //if there is no duplicate item
                echo 'noResult';
                exit();
            }
            //vendor code is unique
            $originVName = $result[0]['vendorName'];
  
            //also update the product record

            $insertArr=array(
                $_REQUEST['inputName'],
                $originVName
            );
            $sqlUpdate="UPDATE products SET vendorName = ? WHERE vendorName = ?";
            $changeTokenConn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlUpdate, $insertArr);
            //
            
            //update vendor table
            $insertArr= array(
                $_REQUEST['inputName'],
                $_REQUEST['inputDescript'],
                $_REQUEST['inputlink'],
                $imgPath,
                $_REQUEST['inputCode']

            );
            
            $sqlUpdate="UPDATE vendors SET vendorName = ?,vendorDescription = ?,vendorHtml = ? ,Image = ? WHERE vendorCode =?";
            $changeTokenConn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $changeResult= $changeTokenConn->alterDB_PDO($sqlUpdate, $insertArr);
            
           
            echo 'updateCompleted';
            //echo $catMap[intval($_REQUEST['selectCategories'])];
            exit();
        }

        if ($_REQUEST['action']=='add') {

            //debug
            $insertArr=array_slice($_REQUEST, 1);
           

            $statementArr=array();
          
            //handle request array
           
            $sql="select vendorCode from vendors where vendorCode=? ";
            $conn = new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $result= $conn->queryDB_PDO($sql, $_REQUEST['inputCode']);
            
            if (count($result)>0) {
                //if there is no duplicate item
                echo 'duplicate';
                exit();
            }

            $insertArr= array(
                $_REQUEST['inputCode'],
                $_REQUEST['inputName'],
                $_REQUEST['inputDescript'],
                $_REQUEST['inputlink'],
                $imgPath,
                0
            );
            
            $connInfo=new AdminCheck_DBInfo('ashop', $_SESSION['userName']);
            $sqlInsert="INSERT INTO vendors (vendorCode, vendorName, vendorDescription, vendorHtml, Image, hitrate) VALUES (?, ?, ?, ?, ?, ?)";
            $result=$connInfo->alterDB_PDO($sqlInsert, $insertArr);
            
            echo 'passed';

            exit();
        }
    }
}
function checkDuplicate($queryPar, $tableName)
{
    //check is there any pcode duplicate
  
   
    //$sql="select productCode,productName from products where productCode=? or productName=? order by productCode asc";
    end($queryPar);//move pointer to the last element
    $lastkey= key($queryPar);
    $sql="SELECT ";

    foreach ($queryPar as $key=>$value) {
        //productCode,productName
        if ($key==$lastkey) {
            $sql.=$key.' ';
        } else {
            $sql.= $key.', ';
        }
    }

    $sql.=' FROM '.$tableName.' WHERE ';

    foreach ($queryPar as $key=>$value) {
        //productCode=? or productName=?';
        if ($key==$lastkey) {
            $sql.=$key.' = ?';
        } else {
            $sql.= $key.' = ? || ';
        }
    }

    $queryValue=array();

    foreach ($queryPar as $key=>$value) {
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
        $pListHtml.="\n<td><a href='javascript: deleteProduct(\"".$row['productCode']."\")'><i class='fas fa-trash-alt'></i></a></td>"; //delete btn
        $pListHtml.="\n<td>".$row['productCode']."</td>";
        $pListHtml.="\n<td>".$row['productName']."</td>";
        $pListHtml.="\n<td>".$row['categoriesName']."</td>";
        $pListHtml.="\n<td>".$row['vendorName']."</td>";
        $pListHtml.="\n<td>".$row['productDescription']."</td>";
        $pListHtml.="\n<td>".$row['quantityInStock']."</td>";
        $pListHtml.="\n<td>".$row['buyPrice']."</td>";
        $pListHtml.="\n<td>".$row['MSRP']."</td>";
        $pListHtml.="\n<td>".$row['pdiscount']."</td>";
        $pListHtml.="\n<td>".$row['attributes']."</td>";
        $pListHtml.="\n<td>".$row['Image']."</td>";
        $pListHtml.="\n<td>".$row['hitrate']."</td>";

        $pListHtml.="\n</tr>";
    }

    return $pListHtml;
}
function printFeatureListHTML()
{
    //select products list
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="select * from featuredinfo";
    $pResult=$connInfo->queryDB_PDO($sql, $userName);

    $pListHtml='';
    foreach ($pResult as $row) {
        $pListHtml.="\n<tr>";
        //$pListHtml.="\n<td><a href='javascript: deleteProduct(\"".$row['productCode']."\")'><i class='fas fa-trash-alt'></i></a></td>"; //delete btn
        $pListHtml.="\n<td><a href='javascript: deleteFeature(\"".$row['featureCode']."\")'><i class='fas fa-trash-alt'></i></a></td>"; //delete btn
        $pListHtml.="\n<td>".$row['featureCode']."</td>";
        $pListHtml.="\n<td>".$row['productCode']."</td>";
        $pListHtml.="\n<td>".$row['imageURL']."</td>";
        $pListHtml.="\n<td>".$row['featureText']."</td>";
        $pListHtml.="\n<td>".$row['attribute']."</td>";

        $pListHtml.="\n</tr>";
    }

    return $pListHtml;
}
function printVendorListHTML()
{
    //select products list
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="select * from vendors";
    $pResult=$connInfo->queryDB_PDO($sql, $userName);

    $pListHtml='';
    foreach ($pResult as $row) {
        $pListHtml.="\n<tr>";
        //$pListHtml.="\n<td><a href='javascript: deleteProduct(\"".$row['productCode']."\")'><i class='fas fa-trash-alt'></i></a></td>"; //delete btn
        $pListHtml.="\n<td><a href='javascript: deleteVendor(\"".$row['vendorName']."\")'><i class='fas fa-trash-alt'></i></a></td>"; //delete btn
        $pListHtml.="\n<td>".$row['vendorCode']."</td>";
        $pListHtml.="\n<td>".$row['vendorName']."</td>";
        $pListHtml.="\n<td>".$row['vendorDescription']."</td>";
        $pListHtml.="\n<td>".$row['vendorHtml']."</td>";
        $pListHtml.="\n<td>".$row['Image']."</td>";
        $pListHtml.="\n<td>".$row['hitrate']."</td>";
        $pListHtml.="\n</tr>";
    }

    return $pListHtml;
}
function printPCodeOpts()
{
    $userName=$_SESSION['userName'];
    $connInfo=new AdminCheck_DBInfo('ashop', $userName);
    $sql="select productCode from products order by productCode asc";
    $result=$connInfo->queryDB_PDO($sql, $userName);

    $catListHtml='';
    for ($i=0;$i<count($result);$i++) {
        $value=$i+1;
        $catListHtml.="<option value='".$value."'>".$result[$i]['productCode']."</option>";
    }
    return  $catListHtml;
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