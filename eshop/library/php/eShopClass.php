<?php

namespace arabcci_chamber_eshop;

use mysqli;
use PDO;

//include_once('loadProductsInfo.php');
//mysql method
function connectShopDB()
{
    $servername = "localhost";
    $username = "shop";
    $password = "hYCIkdc2RDghvxAH";
    $dbname = "arabcci_shop";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        return $conn;
    }
}
function getSQLResult($sql)
{
    $conn= connectShopDB(); //included in loadHeader.php
    $infoArr= array();

    

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($infoArr, $row);
        }
           
        /////else return a empty array
    }

    $conn->close();
    return $infoArr;
}

//PDO method
function queryShopDB_PDO($sql, $value)
{
    $servername =  "localhost";
    $dbname   = "arabcci_shop";
    $dbUserName = "shop";
    $password = "hYCIkdc2RDghvxAH";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbUserName, $password);
        

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        //echo "Connected successfully";
        $stmt = $conn->prepare($sql);
        if ($value==null) {
            // if no parameterized value
            $stmt->execute();
        } elseif (is_array($value)) {
            //for IN query and parameterized array value
            $stmt->execute($value);
        } else {
            //for parameterized value
            $stmt->execute([$value]);
        }
        
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //close connection
        $conn=null;
        //return result array
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}





//eshop classes
class vendor
{
    public $venCode;
    private $venName;
    private $venDescription;
    private $venHtml;
    private $venImage;
    private $venHitrate;

    public function __construct($code)
    {
        $sql = "SELECT * FROM `vendors` WHERE vendorCode=?";
        $vendorInfoArr=queryShopDB_PDO($sql, $code);

        $this->venCode=$vendorInfoArr[0]['vendorCode'];
        $this->venName=$vendorInfoArr[0]['vendorName'];
        $this->venDescription=$vendorInfoArr[0]['vendorDescription'];
        $this->venImage=$vendorInfoArr[0]['Image'];
        $this->venHitrate=$vendorInfoArr[0]['hitrate'];
        $this->venHtml=$vendorInfoArr[0]['vendorHtml'];
    }
    public function getVenCode()
    {
        return $this->venCode;
    }
    public function getVenName()
    {
        return $this->venName;
    }
    public function getVenDescription()
    {
        return $this->venDescription;
    }
    public function getVenImage()
    {
        return $this->venImage;
    }
    public function getVenHitrate()
    {
        return $this->venHitrate;
    }
    public function getVenHtml()
    {
        return $this->venHtml;
    }
}

class productLine
{
    private $pLineName;
    private $pLineDescript;
    private $pLineHtml;
    private $pLineIcon;

    public function __construct($pLine)
    {
        $sql = "SELECT * FROM `productlines` WHERE productline=?";
        $pLineInfoArr=queryShopDB_PDO($sql, $pLine);
        
        $this->pLineName=$pLineInfoArr[0]['productLine'];
        $this->pLineDescript=$pLineInfoArr[0]['textDescription'];
        $this->pLineHtml=$pLineInfoArr[0]['htmlDescription'];
        $this->pLineIcon=$pLineInfoArr[0]['icon'];
    }

    public function getPLineName()
    {
        return $this->pLineName;
    }
    public function getPLineDescript()
    {
        return $this->pLineDescript;
    }
    public function getPLineHtml()
    {
        return $this->pLineHtml;
    }
    public function getPLineIcon()
    {
        return $this->pLineIcon;
    }
}

class categories extends productLine
{
    private $catName;
    private $catPLine;
    private $catDescript;
    private $catHtml;
    private $catHitrate;

    public function __construct($catName)
    {
        $sql = "SELECT * FROM categories WHERE categoriesName=?";
        $catInfoArr=queryShopDB_PDO($sql, $catName);

        $this->catName=$catInfoArr[0]['categoriesName'];
        $this->catPLine=$catInfoArr[0]['productLine'];
        $this->catDescript=$catInfoArr[0]['textDescription'];
        $this->catHtml=$catInfoArr[0]['htmlDescription'];
        $this->catHitrate=$catInfoArr[0]['hitrate'];
    }

    public function getCatName()
    {
        return $this->catName;
    }
    public function getCatPLine()
    {
        return $this->catPLine;
    }
    public function getCatDescript()
    {
        return $this->catDescript;
    }
    public function getCatHtml()
    {
        return $this->catHtml;
    }
    public function getCatHitrate()
    {
        return $this->catHitrate;
    }
}

class product
{
    private $pCode;
    private $pName;
    private $pCat;
    private $pVendor;
    private $pDescript;
    private $pTotalStock;
    private $pSuggestedPrice;
    private $pImageURL;
    private $pAtt;
    private $pDiscount;
    private $pBuyPrice;
    private $pHitrate;

    public function __construct($code)
    {
        $sql = "SELECT * FROM `products` WHERE productCode=?";
        $pInfoArr=queryShopDB_PDO($sql, $code);

        $this->pCode= $pInfoArr[0]['productCode'];
        $this->pName=$pInfoArr[0]['productName'];
        $this->pCat= $pInfoArr[0]['categoriesName'];
        $this->pVendor= $pInfoArr[0]['vendorName'];
        $this->pDescript= $pInfoArr[0]['productDescription'];
        $this->pTotalStock  = $pInfoArr[0]['quantityInStock'];
        $this->pSuggestedPrice  = $pInfoArr[0]['MSRP'];
        $this->pImageURL=$pInfoArr[0]['Image'];
        $this->pDiscount=$pInfoArr[0]['discount(%)'];
        $this->pBuyPrice=$pInfoArr[0]['buyPrice'];
        $this->pAtt=explode('|', $pInfoArr[0]['attributes']); // an array for storing multiple attributes
        $this->pHitrate=$pInfoArr[0]['hitrate'];
    }

    public function getPCode()
    {
        return $this->pCode;
    }
    public function getPName()
    {
        return $this->pName;
    }
    public function getPCat()
    {
        return $this->pCat;
    }
    public function getPVendor()
    {
        return $this->pVendor;
    }
    public function getPDescript()
    {
        return $this->pDescript;
    }
    public function getPTotalStock()
    {
        return $this->pTotalStock;
    }
    public function getPSuggestedPrice()
    {
        return $this->pSuggestedPrice;
    }
    public function getPImageURL()
    {
        return $this->pImageURL;
    }
    public function getOriginalPrice()
    {
        return $this->pSuggestedPrice;
    }
    public function getSalePrice()
    {
        $salePrice= ($this->pSuggestedPrice*$this->pDiscount)/100;
        return $salePrice;
    }

    public function getPAttributes()
    {
        return $this->pAtt;
    }
    public function printAttOption()
    {
        //print options in select box of product details page
        echo "<select id='selectAttBox' class='custom-select' onchange=\"changeFunc('".$this->getPCode()."')\">"."\n".
  '<option  value="0">Please Select</option>'."\n";

        $attArr=$this->getPAttributes();

        for ($i=0;$i<count($attArr);$i++) {
            $index = $i+1;
            $attStrArr = explode('-', $attArr[$i]);

            echo '<option value="'.$index.'">';
            echo '<span>';
            echo $attStrArr[0];
            echo '</span>';
        
       
            echo '</option>';
        }
        /*
        <option value="1">One</option>123
        <option value="2">Two</option>
        <option value="3">Three</option>
        */
        echo '</select>';
    }
    public function printPTotalStock()
    {
        $stockStr = 'Stock :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$this->getPTotalStock();
        echo $stockStr;
    }
    public function printProductCard()
    {


        ///print  product list
            

        //print product thumbnails
        echo '<div class="row px-1">'."\n".'
          <div class="col image-container overflow-hidden h-100 w-100 p-0 rounded-top">'."\n";

          
        ////print product image url
        echo "<a href='#'><img onclick=\"loadDoc('layouts/productDetails.php?product=".$this->pCode."',loadContent,'main')\" src='".$this->pImageURL."' class='img-fluid zoom-in row-image m-0 '></a>\n";
        ////print product image url

        echo "</div>\n
                </div>\n";

        //print prduct text
        echo '<div class="row px-1 ">
              <div class="col-sm-12 bg-white pt-2 pb-5">';

              

        if (strlen($this->pName)>34) {
            $nameArr = explode(" ", $this->pName);

            echo '<h6>'."\n";
            for ($part=0; $part<6; $part++) {
                echo $nameArr[$part].' ';
            }
            echo '....';
        } else {
            echo '<h6>'."\n".$this->pName."\n";
        }

        echo '</h6>'."\n".
                '</div>'."\n".
                '</div>'."\n";

        //print product price
        echo '<div class="row   px-1">
                <div class="col-sm-12 bg-white py-2 " style="border-top:1px solid #f2f2f2">
                    <p>';
      
        
        
        
        if ($this->pSuggestedPrice===$this->getSalePrice()) {
            echo '<font class="text-success ">USD'.$this->getSalePrice().'</font>';
        } else {
            echo '<font class="text-secondary pr-1"><del>USD'.$this->pSuggestedPrice.'</del></font>';
            echo '<font class="text-success pr-1">USD'.$this->getSalePrice() .'</font>';
        }

        ///print ajax queries to product's details
        echo "<a href='#' class='btn btm-sm btn-primary float-right' onclick=\"loadDoc('layouts/productDetails.php?product=".$this->pCode."',loadContent,'main')\">
                View More</a>";

        echo '</p>
                    </div>
                </div>';

        //end </div>
        echo "</div>\n";
    }
}