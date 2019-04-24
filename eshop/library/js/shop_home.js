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
function ShowCartListDetails() {
    //declare a array to store item objects
    let cart_itemArr = [];

    //event handler of cart list show details btn
    //count the number of row
    let table_row = $('.cart-list-table tbody tr').length;
    //count the number of column
    let table_column = $('.cart-list-table tbody tr td').length;

    //declare varibles 
    let i;
    let name;
    let attribute;
    let quantity;
    let price;

    for (i = 0; i < table_row; i++) {

        //get table data first

        name = $('.cart-list-table tbody tr td').eq(0).text();
        attribute = $('.cart-list-table tbody tr td').eq(1).text();
        quantity = $('.cart-list-table tbody tr td').eq(2).text();
        price = $('.cart-list-table tbody tr td').eq(3).text();
        //let item = new cart_item(pName, selectedAttValue, selectedQuantityValue, pSalePrice);
        //declare a cart item objec
        let item = new cart_item(name, attribute, quantity, price);
        cart_itemArr.push(item);

    }
    // declare a cart list object
    let list = new shopCart_list(cart_itemArr);
    // json encode the object
    listSTr = JSON.stringify(list);

    //ajax pass querires 

    //loadDoc('layouts/shop_checkout.php?list=' + listSTr, loadContent, 'main')
    let response = "";
    $.ajax({
        type: 'POST',

        url: 'layouts/shop_checkout.php?',
        data: { list: listSTr }, //query '?list='+listStr

        success: function (data) {
            //echo what the server sent back...
            $("main").html(data);
        }


    });
}
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

