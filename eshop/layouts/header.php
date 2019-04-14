<!-- nav bar-->
<?php
    include('library/php/loadHeader.php');
    $pLineArr = getProductLineInfo();

    ?>


<div class="row search-bar py-2">
    <div class="col-md-2 px-5 ">
        <a class="navbar-brand font-weight-bolder text-white" href="shop.php">Shop</a>
    </div>
    <!-- need to be change after language button is implemented-->
    <div class="  my-2 my-md-0  col-sm-8">

        <input id="search_box" class="form-control   rounded-left search_form" type="text" placeholder="Search for more"
            aria-label="Search" oninput="showResult('library/php/search.php?q='+ this.value,'livesearch_result')"
            onkeyup="showResult('library/php/search.php?q='+ this.value,'livesearch_result')">

    </div>

    <div class="col-md-1 ">
        <!-- need to be change after language button is implemented-->


        <a id="loginBtn" class="languageBtn btn btm-sm  font_white pt-0 pb-1 px-1 mr-2" href="#">Login
        </a>



    </div>
</div>

<div id="livesearch_result" class="search-result  bg-white text-dark ">


</div>





<!-- dropdown menu-->
<div id="dropdown_menu_row" class="row  mt-0 d-none d-md-inline no-gutters ">
    <div class="btn-group col-sm-12 bg-white justify-content-center " role="group">
        <?php
            for ($i = 0; $i < count($pLineArr); $i++) {
                echo '<div class="dropdown dropdownBtn col tenPercentWidth p-0">
                    <button class="btn dropdown_btn btn-block  dropdown_btn " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="row justify-content-center pt-2">
                            <div class="col">
                                <span class="fa-stack fa-1x text-primary">
                                    <i class="fas fa-heartbeat fa-stack-1x"></i>';

                //////////////print icon
                echo '<i class="' . $pLineArr[$i]['icon'] . ' fa-stack-2x"></i>';
                //////////////print icon

                echo ' </span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col mb-0 pb-0">';

                //////////print productLine name
                echo '<span>' . $pLineArr[$i]['productLine'] . '</span>';
                //////////print productLine name

                echo '</div>
                        </div>
                    </button>
                <div class="dropdown-menu " aria-labelledby="dropdownMenuButton">';

                //////print categories name of specific productLine
                $catArr = getCategoriesInfo($pLineArr[$i]['productLine']);
                
                
               
                for ($part = 0; $part < count($catArr); $part++) {
                    echo "<a class='dropdown-item' href='#'
                        onclick=\"loadDoc('layouts/categoriesList.php?cat=".$catArr[$part]['categoriesName']."', loadContent,'main')\">".$catArr[$part]['categoriesName']."</a>";
                }
                //////print categories name of specific productLine
                
                echo '
                </div>
            </div>';
            }
            ?>