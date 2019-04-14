////////////page jquery///////////
$(document).ready(function () {
    var width = $("#search_box").outerWidth();

    var search_box = $("#search_box");
    var position = search_box.offset();

    $("#livesearch_result").width(width);
    $("#livesearch_result").offset({
        left: position.left
    });

    $("#livesearch_result").hide();

    $("#search_box").click(function () {
        $("#livesearch_result").show();
    });


    $(document).click(function (event) {

        // if the click event target have a ancestor which has a id is search_box with 0 length
        // or does not have such ancestor
        if ($(event.target).closest('#search_box').length == 0) {
            $('#livesearch_result').hide();
        }
    });
})





///////////page jquery///////////////

//////////ajax call//////////////
function loadDoc(url, inputFunction, id) {

    var xhttp;

    //handling old browsers
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            inputFunction(this, id);

        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

//side menu and nav bar function
function loadContent(xhttp, id) {
    document.getElementById(id).innerHTML = xhttp.responseText;
}

// nav bar search function
function showResult(url, id) {
    if (url.length == 0) {
        document.getElementById(id).innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
}
/////////////ajax call////////////

