<?php
include_once('connectShopDB.php');
use function arabcci_chamber_eshop\queryShopDB_PDO;

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

function getTrendingDealsInfo()
{
    $sql = "SELECT * FROM `products` ORDER BY `discount(%)` ASC LIMIT 4";
    $infoArr=queryShopDB_PDO($sql, null);
    if (count($infoArr)>0) {
        return $infoArr;
    } else {
        echo '0 results';
    }
}

function getBestActivities()
{
    $catArr=['Competitions','Events','Fairs','Galas','Seminars','Ticketing'];
    $cat  = str_repeat('?,', count($catArr) - 1) . '?';
    $sql = "SELECT * FROM products WHERE categoriesName IN ($cat)";
    
    $infoArr=queryShopDB_PDO($sql, $catArr);
    if (count($infoArr)>0) {
        return $infoArr;
    } else {
        echo '0 results';
    }
}

function getBestFoods()
{
    /*
    $conn= connectShopDB(); //included in loadHeader.php
    $infoArr= array();

    $sql = "SELECT * FROM products WHERE categoriesName = 'Hypermarket' ORDER BY `hitrate` DESC LIMIT 4";

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($infoArr, $row);
        }
    } else {
        echo '0 results';
    }

    $conn->close();
    return $infoArr;
    */
    
    $sql = "SELECT * FROM products WHERE categoriesName = ? ORDER BY `hitrate` DESC LIMIT 4";
    $infoArr=queryShopDB_PDO($sql, 'Hypermarket');
    return $infoArr;
}

function getBestShops()
{
    /*
    $conn= connectShopDB(); //included in loadHeader.php
    $infoArr= array();

    $sql = "SELECT * FROM `vendors` ORDER BY hitrate DESC LIMIT 4";

    $result=$conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($infoArr, $row);
        }
    } else {
        echo '0 results';
    }

    $conn->close();
    */
    $sql = "SELECT * FROM `vendors` ORDER BY hitrate DESC LIMIT 4";
    $infoArr=queryShopDB_PDO($sql, null);
    return $infoArr;
}
function printProductRow($arr, $col)
{
    /// print product rows in shop index
    
    if (count($arr)!=0) {
        $width = 12/$col;
        for ($row=0;$row<count($arr);$row++) {
           
            ///print best activities product list
            echo '<!-- col-3 for each -->';
            echo '<div class="col-sm-'.$width.' px-4  pb-5">'."\n";

            //print product thumbnails
            echo '<div class="row px-1">'."\n".'
          <div class="col image-container overflow-hidden h-100 w-100 p-0 rounded-top">'."\n";

          
            ////print product image url
            echo "<a href='#'><img onclick=\"loadDoc('layouts/productDetails.php?product=".$arr[$row]['productCode']."',loadContent,'main')\" src='".$arr[$row]['Image']."' class='img-fluid zoom-in row-image m-0 '></a>\n";
            ////print product image url

            echo "</div>\n
      </div>\n";

            //print prduct text
            echo '<div class="row px-1">
              <div class="col-sm-12 bg-white pb-5 pt-3">';

              

            if (strlen($arr[$row]['productName'])>34) {
                $descriptionArr = explode(" ", $arr[$row]['productName']);

                echo '<h6>'."\n";
                for ($part=0; $part<6; $part++) {
                    echo $descriptionArr[$part].' ';
                }
                echo '....';
            } else {
                echo '<h6>'."\n".$arr[$row]['productName']."\n";
            }

            echo '</h6>'."\n".
      '</div>'."\n".
      '</div>'."\n";

            //print product price
            echo '<div class="row   px-1">
          <div class="col-sm-12 bg-white py-2 " style="border-top:1px solid #f2f2f2">
              <p>';
      
            // calculate price after discount
            $price = $arr[$row]['MSRP'];
            $pAfterDiscount = $price * $arr[$row]['discount(%)']/100;
      
            if ($arr[$row]['discount(%)']!=100) {
                echo '<font class="text-secondary pr-1"><del>USD'.$price.'</del></font>';
                echo '<font class="text-success pr-1">USD'.$pAfterDiscount .'</font>';
            } else {
                echo '<font class="text-success ">USD'.$price.'</font>';
            }

            ///print ajax queries to product's details
            echo "
      <a href='#' class='btn btm-sm btn-primary float-right' onclick=\"loadDoc('layouts/productDetails.php?product=".$arr[$row]['productCode']."',loadContent,'main')\">
      View More</a>";

            echo '</p>
          </div>
      </div>';

            //end </div>
            echo "</div>\n";
        }
    } else {
        echo 'Sorry, no such goods in stock.';
    }
}

function printShopProductsRow($arr, $col, $productCode)
{
    /// print product rows in shop index
    
    if (count($arr)!=0) {
        $width = 12/$col;
        for ($row=0;$row<count($arr);$row++) {
            if ($arr[$row]['productCode']===$productCode) {
                //skip the product with same product code
            } else {
                ///print best activities product list
                echo '<!-- col-3 for each -->';
                echo '<div class="col-sm-'.$width.' px-4  pb-5">'."\n";

                //print product thumbnails
                echo '<div class="row px-1">'."\n".'
          <div class="col image-container overflow-hidden h-100 w-100 p-0 rounded-top">'."\n";

          
                ////print product image url
                echo "<a href='#'><img onclick=\"loadDoc('layouts/productDetails.php?product=".$arr[$row]['productCode']."',loadContent,'main')\" src='".$arr[$row]['Image']."' class='img-fluid zoom-in row-image m-0 '></a>\n";
                ////print product image url

                echo "</div>\n
                </div>\n";

                //print prduct text
                echo '<div class="row px-1 ">
              <div class="col-sm-12 bg-white pb-5">';

              

                if (strlen($arr[$row]['productName'])>34) {
                    $descriptionArr = explode(" ", $arr[$row]['productName']);

                    echo '<h6>'."\n";
                    for ($part=0; $part<6; $part++) {
                        echo $descriptionArr[$part].' ';
                    }
                    echo '....';
                } else {
                    echo '<h6>'."\n".$arr[$row]['productName']."\n";
                }

                echo '</h6>'."\n".
                '</div>'."\n".
                '</div>'."\n";

                //print product price
                echo '<div class="row   px-1">
                <div class="col-sm-12 bg-white py-2 " style="border-top:1px solid #f2f2f2">
                    <p>';
      
                // calculate price after discount
                $price = $arr[$row]['MSRP'];
                $pAfterDiscount = $price * $arr[$row]['discount(%)']/100;
      
                if ($arr[$row]['discount(%)']!=100) {
                    echo '<font class="text-secondary pr-1"><del>USD'.$price.'</del></font>';
                    echo '<font class="text-success pr-1">USD'.$pAfterDiscount .'</font>';
                } else {
                    echo '<font class="text-success ">USD'.$price.'</font>';
                }

                ///print ajax queries to product's details
                echo "<a href='#' class='btn btm-sm btn-primary float-right' onclick=\"loadDoc('layouts/productDetails.php?product=".$arr[$row]['productCode']."',loadContent,'main')\">
                View More</a>";

                echo '</p>
                    </div>
                </div>';

                //end </div>
                echo "</div>\n";
            }
        }
    } else {
        echo 'Sorry, no such goods in stock.';
    }
}
function printProductAttributes($arr)
{
    echo "<select id='selectBox' class='custom-select' onchange=\"changeFunc('".$arr[0]['productCode']."')\">"."\n".
  '<option  value="0">Please Select</option>'."\n";

    $attArr=explode('|', $arr[0]['attributes']);

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
function printProductStock($arr)
{
    $stockStr = 'Stock :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$arr[0]['quantityInStock'];
    echo $stockStr;
}
function printShopRow($arr)
{
    /// print product rows in shop index

    for ($row=0;$row<count($arr);$row++) {
    

        ///print best activities product list
        echo '<!-- col-3 for each -->';
        echo '<div class="col-sm-3 px-4 ">'."\n";

        //print product thumbnails
        echo '<div class="row px-1">'."\n".'
          <div class="col image-container overflow-hidden h-100 w-100 p-0 rounded-top">'."\n";

          
        ////print product image url
        echo "<a href='#'><img onclick=\"loadDoc('layouts/vendorDetails.php?vendor=".$arr[$row]['vendorCode']."',loadContent,'main')\" src='".$arr[$row]['Image']."' class='img-fluid zoom-in row-image m-0 '></a>\n";
        ////print product image url

        echo "</div>\n
      </div>\n";

        //print prduct text
        echo '<div class="row px-1 ">
              <div class="col-sm-12 bg-white pb-5 pt-3">';

              

        if (strlen($arr[$row]['vendorDescription'])>34) {
            $descriptionArr = explode(" ", $arr[$row]['vendorDescription']);

            echo '<h6>'."\n";
            for ($part=0; $part<6; $part++) {
                echo $descriptionArr[$part].' ';
            }
            echo '....';
        } else {
            echo '<h6>'."\n".$arr[$row]['vendorDescription']."\n";
        }

        echo '</h6>'."\n".
      '</div>'."\n".
      '</div>'."\n";

        //print product price
        echo '<div class="row   px-1">
          <div class="col-sm-12 bg-white py-2 " style="border-top:1px solid #f2f2f2">
              <p>';
      
      

        ///print ajax queries to product's details
        echo "<a href='#' class='btn btm-sm btn-primary float-right' onclick=\"loadDoc('layouts/vendorDetails.php?vendor=".$arr[$row]['vendorCode']."',loadContent,'main')\">
      View More</a>";

        echo '</p>
          </div>
      </div>';

        //end </div>
        echo "</div>\n";
    }
}

function printCatSideMenu($arr)
{

    ////// function used for printing the categories list side menu of categoriesList.php
    for ($row=0;$row<count($arr);$row++) {
        echo '<li class="list-group-item pCat_li pl-2">';
        //// print ajax queries
        echo "<a href='#' 
             onclick=\"loadDoc('layouts/categoriesList.php?cat=".$arr[$row]['categoriesName']."',loadContent,'main')\">";
        //// print ajax queries

        echo '<span class=" ml-2 change_icon">
             <i class="fas fa-heart"></i>';

        //// print ajax queries
        echo $arr[$row]['categoriesName'];
        //// print ajax queries
        echo '</span>
             </a>
         </li>';
    }
}