<?php

function printFooterNavLink($arr)
{
    for ($i=0;$i<count($arr);$i++) {
        echo '<li class="nav-item ">';

        ////// print ajax query
        echo "<a class='nav-link px-1 ' href='#' onclick=\"loadDoc('layouts/productLine.php?pLine=".$arr[$i]['productLine'].", loadContent,'main')\">";


        echo '<h6 class="align-middle pl-2">';

        //////print product lines
        echo $arr[$i]['productLine'];
        //////print product lines

        echo   '</h6>
        </a>
    </li>';
    }
}