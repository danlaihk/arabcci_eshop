<?php
//include_once('loadProductsInfo.php');
include_once('eShopClass.php');
use function arabcci_chamber_eshop\queryShopDB_PDO;

function inputData($dataSourceArr, $colName)
{
    $array=array();
    if ($colName==='productName') {
        $sql = "SELECT productName,productCode FROM products";
        //$infoArr=getSQLResult($sql);
        $infoArr= queryShopDB_PDO($sql, null);

        for ($i=0;$i<count($dataSourceArr);$i++) {
            array_push($array, $infoArr[$i]['productCode'].'-'.$dataSourceArr[$i][$colName]);
        }
    } else {
        for ($i=0;$i<count($dataSourceArr);$i++) {
            array_push($array, $dataSourceArr[$i][$colName]);
        }
    }
    return $array;
}

function printResult($array)
{
    $pInfoArr=array();
    for ($i=0;$i<count($array);$i++) {
        $resultStrArr= explode(': ', $array[$i]);
        
        //print result rows
        echo '<p  class="pr-3 pl-2 pt-3 searchResult ">'."\n";

        //print jquery link
        switch ($resultStrArr[0]) {
            case 'Product':
                $pInfoArr=explode('-', $resultStrArr[1]);
                echo "<a href='#' class='text-dark' onclick=\"loadDoc('layouts/productDetails.php?product=".$pInfoArr[0]."',loadContent,'main')\">\n";
                break;

            case 'Product Line':
                echo "<a href='#' class='text-dark' onclick=\"loadDoc('layouts/productLine.php?pLine=".$resultStrArr[1]."',loadContent,'main')\">\n";
                break;

            case 'Categories':
                echo "<a href='#' class='text-dark' onclick=\"loadDoc('layouts/categoriesList.php?cat=".$resultStrArr[1]."',loadContent,'main')\">\n";
                break;

            case 'Vendor':
                echo "<a href='#' class='text-dark' onclick=\"loadDoc('layouts/vendorDetails.php?vendor=".$resultStrArr[1]."',loadContent,'main')\">\n";
                break;
        }

        //print product name
        if ($resultStrArr[0]==='Product') {
            echo '<span id="resultName" class="float-left pl-1 ">'.$pInfoArr[1]."</span>\n";
        } else {
            echo '<span id="resultName" class="float-left pl-1">'.$resultStrArr[1]."</span>\n";
        }
        //print product class
        echo '<span id="resultClass" class="float-right font-italic">
                <del class="text-secondary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</del>
                &nbsp;<span>';

        //print product class
        echo $resultStrArr[0];
        
        echo"</span>\n
            </a>\n
            </p>\n";


        /*
        original html
        <p class="pr-3 pl-2 pt-3 resultRow">
        <a href="#" onclick="loadDoc('layouts/targetPage.php?resultClass=resultName',loadContent,'main')">
            <span id="resultName" class="float-left pl-1">Product</span>
            <span id="resultClass" class="float-right font-italic"><del
                    class="text-secondary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</del>&nbsp;<span>Class</span>
        </a>
        </p>
        */
    }
}

if (isset($_REQUEST["q"])) {
    //connect to shop db
    //$conn= connectShopDB();

    $hintsArr=array();
    //product data array
    $pSQL= "SELECT productName FROM products";

    $pArray=queryShopDB_PDO($pSQL, null);

    //product line data array
    $pLineSQL= "SELECT productLine FROM productlines";

    $pLineArr=queryShopDB_PDO($pLineSQL, null);


    //categories data array
    $catSQL= "SELECT categoriesName FROM categories";

    $catArray=queryShopDB_PDO($catSQL, null);

    //vendor data array
    $vSQL="SELECT vendorName FROM vendors ";

    $vendorArray=queryShopDB_PDO($vSQL, null);

    //put all the sql results into the array
    array_push($hintsArr, inputData($pArray, 'productName'));
    array_push($hintsArr, inputData($pLineArr, 'productLine'));
    array_push($hintsArr, inputData($catArray, 'categoriesName'));
    array_push($hintsArr, inputData($vendorArray, 'vendorName'));

    //$conn->close();
    //above correct

    //array that carries results
    $resultArr= array();

    // get the q parameter from URL

    $q = $_REQUEST["q"];

    if ($q !== "") {
        $q = strtolower($q);
        $len=strlen($q);
        for ($i=0;$i<count($hintsArr);$i++) {
            foreach ($hintsArr[$i] as $result) {
                ///check if the query is the same part as result
                if (stristr($q, substr($result, 0, $len))) {

                /// if result is product
                    switch ($i) {
                    case 0:
                    array_push($resultArr, "Product: ".$result);
                        break;
                    
                    case 1:
                    array_push($resultArr, "Product Line: ".$result);
                        break;

                    case 2:
                    array_push($resultArr, "Categories: ".$result);
                        break;

                    case 3:
                    array_push($resultArr, "Vendor: ".$result);
                        break;

                    }
                }
            }
        }
    }
    /// return result as html format
    /*
    <div id="livesearch_result" class="search-result  bg-white text-dark pt-3">
        <!-- return string @ here-->
    </div>

    */

    if (count($resultArr)===0) {
        echo '<p class="pr-3 pl-2 pt-3 resultRow">&nbsp;No Result</span>';
    } else {
        printResult($resultArr);
    }


    //print_r($resultArr);
} else {
    echo '<p class="pr-3 pl-2 pt-3 resultRow">&nbsp;Please enter keywords</span>';
}