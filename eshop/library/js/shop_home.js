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

                //let result = document.getElementById(id).innerHTML;
                document.getElementById(id).innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
}

function changeFunc(pCode) {


    var selectBox = document.getElementById("selectAttBox");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;


    loadDoc('library/php/queryStock.php?product=' + pCode + '&att=' + selectedValue, loadContent, 'stock_number');

}

function addCart() {
    //get product name
    let pName = $("#productName").text();

    //get product att


    let selectAttBox = $("#selectAttBox")[0]; //equals to document.getElementByID
    let selectedAttValue = selectAttBox.options[selectAttBox.selectedIndex].text;


    //get product quantity
    let selectQuantityBox = $("#selectQuantityBox")[0];
    let selectedQuantityValue = selectQuantityBox.options[selectQuantityBox.selectedIndex].text;

    //get sale price(if have discount)
    let pSalePrice = $("#salePrice").text();

    //create cart iem object
    //add cart item to cart list
    if (selectedAttValue !== "Please Select") {
        //add information to cart list
        //debug
        //alert("Product Name: " + pName + "<br/>Product Attribute: " + selectedAttValue + "<br/>Product Quantity: " + selectedQuantityValue + "<br/>Product Price: " + pSalePrice);
        let item = new cart_item(pName, selectedAttValue, selectedQuantityValue, pSalePrice);
        item.addToList();

    }
    else {

        //handle error
        alert("Please select an attribute");
    }


}

//post request to shop_checkout.php

function makeid(length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function delItem(ele) {
    let cart_list = new shopCart_list();
    cart_list.delItem(ele);
}
/////////////ajax call////////////

