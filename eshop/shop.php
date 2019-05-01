<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <!-- turn responsive on/off-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
    <meta name="description" content="Eshop of Hong Kong Arabcci Chamber">


    <title>Shop index Sample</title>

    <!-- Jquery -->
    <script src="library/Jquery_331/jquery-3.3.1.slim.min.js">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
    <script src="library/popperjs/popper_1.14.7.min.js"></script>

    <script src="library/bootstrap_431/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="library/bootstrap_431/css/bootstrap.min.css">

    <!-- Font Awesome library -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- loading js sdk for sharing social media buttons-->
    <script src="library/js/shared_btn.js"></script>

    <!-- Custom styles for this template -->
    <link href="library/css/index.css" rel="stylesheet">
    <link href="library/css/navbar.css" rel="stylesheet">
    <link href="library/css/dropdown.css" rel="stylesheet">
    <link href="library/css/footer.css" rel="stylesheet">
    <link href="library/css/checkout.css" rel="stylesheet">

    <!-- Custom javascript for this template -->
    <script src="library/js/shop_class.js"></script>
    <script src="library/js/shop_home.js"></script>
    <script src="library/js/shop_jquery.js"></script>


</head>

<body>

    <!--header -->
    <header id="header">
        <?php
    include('layouts/header.php');
    ?>
    </header>

    <main id="main">
        <?php
           include('library/php/loadFeaturedInfo.php');
           $fBannerInfoArr= getBannerFeaturedInfo();
   
        ?>
        <div class="container-fluid fade-in12">
            <!--banner slider -->

            <div class="row justify-content-center mt-5">
                <div class="col-sm-12">
                    <div id="carouselIndicators" class="carousel slide col-sm-12  d-none d-md-block"
                        data-ride="carousel">
                        <ol class="carousel-indicators">

                            <?php

                            
                                for ($i=0; $i<count($fBannerInfoArr);$i++) {
                                    if ($i===0) {
                                        echo '<li data-target="#carouselIndicators" data-slide-to="'.$i.'" class="active"></li>';
                                    } else {
                                        echo '<li data-target="#carouselIndicators" data-slide-to="'.$i.'" ></li>';
                                    }
                                }
                            ?>
                        </ol>
                        <div class="carousel-inner">

                            <?php
                                for ($i=0; $i<count($fBannerInfoArr);$i++) {
                                    if ($i==0) {
                                        echo '<div class="carousel-item active">';
                                    } else {
                                        echo '<div class="carousel-item">';
                                    }

                                    echo "<a href='#' onclick=\"loadDoc('layouts/productDetails.php?product=".$fBannerInfoArr[$i]['productCode']."',loadContent,'main')\">
                                            <img src='".$fBannerInfoArr[$i]['imageURL']."' class='d-block w-100' alt='...'>
                                        </a>
                                    </div>";
                                }
                            ?>

                        </div>


                    </div>
                </div>
            </div>

            <!-- main -->

            <!-- first row-->
            <div class="row mt-3">

                <!-- Popular Categories-->



                <div class="col-3  d-none d-md-block">
                    <div class="row justify-content-center">
                        <div class="col-sm-10 pb-3">
                            <p class="font-weight-bold"> Popular Categories</p>

                        </div>
                    </div>

                    <div class="row justify-content-start">
                        <div class="card col-12 border-0 ">
                            <div class="card-body pt-0 pl-4 ">
                                <ul class="list-group pl-0">


                                    <?php
                                       include('library/php/loadPopularCategories.php');
                                       $pCatArr=getPCatInfo();

                                       /////print popular categories which order by hit rates
                                       for ($i=0;$i<count($pCatArr);$i++) {
                                           echo "<li class='list-group-item pCat_li pl-2'>
                                            <span class=' ml-2 change_icon'>
                                                <a href='#'
                                                    onclick=\"loadDoc('layouts/categoriesList.php?cat=".$pCatArr[$i]['categoriesName']."', loadContent,'main')\">
                                                    <i class='fas fa-heart'></i>";

                                           echo $pCatArr[$i]['categoriesName'];

                                           echo '      </a>
                                                    </span>
                                                </li>';
                                       }
                                    ?>


                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <!--window size Today Activities-->
                <div class="col-9">
                    <div class="row justify-content-center">
                        <div class="col-md-12 pb-3 pl-2 pr-4 mr-3 d-none d-md-block">
                            Today's activities
                            <!--<span class="float-right text-danger">View All</span>-->
                        </div>
                    </div>
                    <div class="row justify-content-start">
                        <div class="col-sm-12 mt-3 pb-3 pl-0">
                            <div class="card border-0 bg-white mr-3 d-none d-md-block">
                                <div class="card-body  pl-0 ">
                                    <div class="row">

                                        <div id="carousel_mainContent" class="carousel slide col-sm-12 ml-3  "
                                            data-ride="carousel">
                                            <div id="carousel_mainContent" class="carousel slide" data-ride="carousel">
                                                <ol class="carousel-indicators todayAct_Car_ind">

                                                    <?php
                                                        ////get numbers of today featured products for setting the number of indicators
                                                        $todayFInfoArr = getTodayFeaturedInfo();
                                                        for ($i=0;$i<count($todayFInfoArr);$i++) {
                                                            if ($i==0) {
                                                                echo '<li data-target="#carousel_mainContent" data-slide-to="'.$i.'"
                                                            class="active"></li>';
                                                            } else {
                                                                echo '<li data-target="#carousel_mainContent" data-slide-to="'.$i.'">
                                                                </li>';
                                                            }
                                                        }
                                                    ?>


                                                </ol>

                                                <div class="carousel-inner">
                                                    <?php
                                                    ///set active index
                                                    for ($r=0;$r<count($todayFInfoArr);$r++) {
                                                        if ($r==0) {
                                                            echo ' <div class="carousel-item active">';
                                                        } else {
                                                            echo ' <div class="carousel-item">';
                                                        }
                                                    
                                                    
                                                        echo '
                                                            <div class="row justify-content-center">
                                                                <div class="col-md-8">';
                                                        
                                                        /////// print ajax queries to product's details page
                                                        echo "   <a href='#'
                                                                onclick=\"loadDoc('layouts/productDetails.php?product=".$todayFInfoArr[$r]['productCode']."',loadContent,'main')\">";
                                                                
                                                        /////// print image url
                                                        echo   '<img src="'.$todayFInfoArr[$r]['imageURL'].'" class="d-block w-100 img-fluid">
                                                        ';
                                                            
                                                        echo '   </a>    
                                                                </div>
                                                                <div class="col-md-3 ">

                                                                    <div class="row todayAct_text">
                                                                        <h5 class="text-dark font-weight-normal h-75">';

                                                        ////// print carousel description text
                                                        echo $todayFInfoArr[$r]['featureText'];

                                                        echo'</h5>
                                                                        <h5 class=" font-weight-normal h-25">
                                                                            <span class="text-secondary"><del>USD1500</del></span>
                                                                            <span class="text-success pl-3">USD1200</span>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="row justify-content-center align-bottom">';

                                                        ////print button ajax queries to product's details

                                                        echo "<button class='btn btn-lg btn-primary w-75 mr-2'
                                                                        onclick=\"loadDoc('layouts/productDetails.php?product=".$todayFInfoArr[$r]['productCode']."',loadContent,'main')\">>View
                                                                                More!</button>";
                                                            
                                                        echo'</div>
                                                        </div>
                                                    </div></div>';
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- second row Collection for you title-->
            <div class="row ">
                <div class="col-12 ">
                    <span class="float-left pl-4">Collection for you</span>

                    <!--<span class="float-right text-danger pr-3">View All</span>-->
                </div>
            </div>

            <!-- third row Collection for you content-->

            <div class="row mt-3">
                <div class="col-sm-12 ">
                    <div class="row justify-content-center">
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>

                    </div>
                    <div class="row mt-3 ">
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>
                        <div class="col-sm-4 mb-3"><a href="#"
                                onclick="loadDoc('layouts/categoriesList.html', loadContent,'main')"><img
                                    class="img-fluid collectionBtn" src="library/image/sampleCollectionImage.png"></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- fourth row nav link -->
            <div class="row mt-3 mx-3 justify-content-center ">
                <div class="col-sm-12 bg-white">
                    <div class="row py-3 justify-content-center">


                        <div class="col-sm-3 fourth-row-link">
                            <p class="text-center"><a href="#row_TrendingDeals"><i
                                        class="fab fa-hotjar fourthRowIcon mr-2"></i><span
                                        class="fourthRowTitle">Trending
                                        Deals</span></a></p>
                        </div>
                        <div class="col-sm-3">
                            <p class="text-center"><a href="#row_BestActivities"><i
                                        class="fas fa-skiing fourthRowIcon mr-2"></i><span class="fourthRowTitle">Best
                                        Activities</span></a></p>
                        </div>
                        <div class="col-sm-3">
                            <p class="text-center"><a href="#row_BestFoods"><i
                                        class="fas fa-hamburger fourthRowIcon mr-2"></i><span
                                        class="fourthRowTitle">Best
                                        Foods</span></a></p>
                        </div>
                        <div class="col-sm-3">
                            <p class="text-center"><a href="#row_BestShops"><i
                                        class="fas fa-shopping-bag fourthRowIcon mr-2"></i><span
                                        class="fourthRowTitle">Best
                                        Shops</span></a></p>
                        </div>

                    </div>
                </div>
            </div>
            <?php
                            include('library/php/loadProductsInfo.php');
                        ?>

            <!-- Fifth row Trending Deal Title-->
            <div id="row_TrendingDeals" class="row mt-5">
                <div class="col">
                    <p><i class="fab fa-hotjar fourthRowIcon mr-2"></i><span>Trending Deals</span></p>
                </div>
            </div>

            <!-- Fifth row Trending Deal Contents-->
            <div class="row px-3">


                <?php
                            ///print trending deals top 4 product list
                            $trendingDealsInfoArr=getTrendingDealsInfo();
                            printProductRow($trendingDealsInfoArr, 4);
                            
                        ?>


                <!-- Sixth row Best Activities Title-->
                <div id="row_BestActivities" class="row mt-5">
                    <div class="col">
                        <p><i class="fab fa-hotjar fourthRowIcon mr-2"></i><span>Best Activities</span></p>
                    </div>
                </div>

                <!-- Sixth row Best Activities Contents-->

                <div class="row px-3">
                    <?php
                    $bestActInfoArr= getBestActivities();
                    printProductRow($bestActInfoArr, 4);
                    
                ?>
                </div>


                <!-- Seventh row Best Foods Title-->
                <div id="row_BestFoods" class="row mt-5">
                    <div class="col">
                        <p><i class="fab fa-hotjar fourthRowIcon mr-2"></i><span>Best Foods</span></p>
                    </div>
                </div>

                <!-- Seventh row Best Foods Contents-->
                <div class="row px-3">
                    <?php
                        $bestFoodsInfoArr= getBestFoods();
                        printProductRow($bestFoodsInfoArr, 4);
                    ?>

                </div>


                <!-- Eighth row Best Shops Activities Title-->
                <div id="row_BestShops" class="row mt-5">
                    <div class="col">
                        <p><i class="fab fa-hotjar fourthRowIcon mr-2"></i><span>Best Shops</span></p>
                    </div>
                </div>

                <!-- Eighth row Best Shops Contents-->
                <div class="row px-3">
                    <?php
                    $bestShopsInfoArr = getBestShops();
                    printShopRow($bestShopsInfoArr);
                    ?>
                </div>

            </div>


    </main>

    <footer id="footer " class="container-fluid">
        <?php
                include('layouts/footer.php');
            ?>

    </footer>


</body>

</html>