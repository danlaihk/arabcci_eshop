class docSet {

    constructor() {
        this._windowWidth = $(window).width();
        this._windowOutWidth = $(window).outerWidth();

        //header search box
        this._headerSearchBoxObj = $("#search_box");
        this._headerSearchResultBoxObj = $("#livesearch_result");
        //header cart list
        this._headerCartListObj = $("#cart_list");
        this._headerCartIconObj = $("#cart_icon");
        this._headerCartCounterObj = $("#cart_counter");
        this._headerCartListBtnObj = $("#btnCartList");
        //this._delItemBtn = $(".delItemBtn");
        //header drop down 
        this._headerDropdownRowObj = $(".dropdown-row");
        this._headerDropdownMenuObj = $(".dropdown-menu");
        this._headerDropdownBtnObj = $(".dropdown.dropdownBtn");



    }
    setHeader() {
        $("header").width(this._windowWidth);
    }
    setSearchBoXAndResults() {
        // search box and result setting
        let searchBoxObj = this._headerSearchBoxObj;
        let searchResultBoxObj = this._headerSearchResultBoxObj;

        searchResultBoxObj.width(searchBoxObj.outerWidth());
        searchResultBoxObj.offset({

            left: searchBoxObj.offset().left

        });

        searchResultBoxObj.hide();

        searchBoxObj.click(function () {
            searchResultBoxObj.show();
        });



    }
    setCartListAndCounter() {

        //cart_list position and setting
        let cartCounterStr = "0";
        let cartListObj = this._headerCartListObj;
        let cartIconObj = this._headerCartIconObj;
        let cartCounterObj = this._headerCartCounterObj;
        let btnCartListObj = this._headerCartListBtnObj;

        let delItemBtn = this._delItemBtn;


        cartIconObj.click(function () {
            cartListObj.show();

        });

        //setting cart counter
        if (parseInt(cartCounterStr) === 0) {
            cartCounterObj.hide();
        }
        if (parseInt(cartCounterStr) < 10) {
            cartCounterStr = cartCounterStr + "0";
        }
        cartCounterObj.text(cartCounterStr);


        btnCartListObj.click(function () {
            //ajax show cart list details
            //ShowCartListDetails();
            let cart_list = new shopCart_list();

            //add to list array
            cart_list.showCartListDetails();

        });


    }
    setDropdownElements() {
        let headerDropdownRowObj = this._headerDropdownRowObj;
        let headerDropdownMenuObj = this._headerDropdownMenuObj;
        let headerDropdownBtnObj = this._headerDropdownBtnObj;


        //set dropdown row's width equal to window's width;
        headerDropdownRowObj.width(this._windowOutWidth);
        headerDropdownRowObj.offset({
            left: 0,
            right: 0
        });
        headerDropdownMenuObj.width(headerDropdownBtnObj.width());


    }
    hideElementsFunction() {

        let searchBoxObj = this._headerSearchBoxObj;
        let cartListObj = this._headerCartListObj;
        let searchResultsObj = this._headerSearchResultBoxObj;
        //click outside and hide elements
        $(document).mouseup(function (e) {
            // If the target of the click isn't the container
            if (!searchBoxObj.is(e.target) && searchBoxObj.has(e.target).length === 0) {
                searchResultsObj.hide();
            }

            if (!cartListObj.is(e.target) && cartListObj.has(e.target).length === 0) {
                cartListObj.hide();

            }

        });
    }
}

class shopCart_list {
    constructor() {

        //this._itemList = arr;
        this._itemList = [];

        let itemList = this._itemList;
        this._listLength = itemList.length;

        this._headerCartListTable_row = $('.cart-list-table tbody tr');
        this._headerCartListTable_column = $('.cart-list-table tbody tr td');
    }

    addToList(item) {
        let itemList = this._itemList;
        itemList.push(item);
    }
    delItem(ele) {
        let del_col = ele.parentElement;
        let del_row = del_col.parentElement;
        del_row.remove();
        //get number of row of table cart list
        let counter = $('.cart-list-table tbody tr').length;

        // put the number into the counter
        if (counter < 10) {
            $("#cart_counter").text("0" + counter);
        }
        else {
            $("#cart_counter").text(counter);
        }


        if (counter === 0) {
            $("#cart_counter").hide();
        }
    }
    showCartListDetails() {
        let table_row_obj = this._headerCartListTable_row;
        // name = $('.cart-list-table tbody tr td').eq(0).text();
        let itemList = this._itemList;

        //declare varibles 
        let i;
        let name;
        let attribute;
        let quantity;
        let subTotal;

        for (i = 0; i < table_row_obj.length; i++) {
            let col = i + 1;

            //get table data first
            //get the i+1 row and 2,3,4,5 col of the table
            name = $('.cart-list-table tbody tr:nth-child(' + col + ') td:nth-child(1)').text();
            attribute = $('.cart-list-table tbody tr:nth-child(' + col + ') td:nth-child(2)').text();
            quantity = $('.cart-list-table tbody tr:nth-child(' + col + ') td:nth-child(3)').text();
            subTotal = $('.cart-list-table tbody tr:nth-child(' + col + ') td:nth-child(4)').text();
            //let item = new cart_item(pName, selectedAttValue, selectedQuantityValue, pSalePrice);
            //declare a cart item objec
            let price = parseFloat(subTotal) / parseFloat(quantity);
            let item = new cart_item(name, attribute, quantity, price);
            this.addToList(item);

        }
        // declare a cart list object

        // json encode the object
        let listSTr = JSON.stringify(itemList);

        //ajax pass querires 

        //loadDoc('layouts/shop_checkout.php?list=' + listSTr, loadContent, 'main')

        $.ajax({
            type: 'GET',
            //url: 'layouts/shop_checkout.php?list=' + listSTr,
            url: 'layouts/shop_checkout.php',
            data: {
                //query '?list='+listStr
                list: listSTr
            },

            success: function (data) {
                //echo what the server sent back...
                // $("main").html(data);
                alert(data);
            }


        });
    }


}
class cart_item {

    constructor(name, attribute, quantity, price) {
        this._name = name;
        this._attribute = attribute;
        this._quantity = quantity;
        this._price = price;
        this._subTotal = parseFloat(this._price) * parseFloat(this._quantity);

    }

    addToList() {
        let cart_counter = $("#cart_counter").text();
        cart_counter = parseInt(cart_counter) + 1;

        let container = $("#cart_item_container")[0]; //get element by id 

        let innerEle = container.innerHTML;


        let nameEle = "<td class='text-center'>" + this._name + "</td>";
        let attEle = "<td class='text-center'>" + this._attribute + "</td>";
        let quantityEle = "<td class='text-center'>" + this._quantity + "</td>";
        let subTotalEle = "<td class='text-center'>" + this._quantity + "</td>";
        let deleteBtn = "<td class='text-center'><a href='#' class='delItemBtn' onclick='delItem(this)'><i class='fas fa-trash-alt'></i></a></td>";
        container.innerHTML = innerEle + "<tr>" + nameEle + attEle + quantityEle + subTotalEle + deleteBtn + "</tr>";
        //container.innerHTML = innerEle + "<div class='row'><div class='col-12'>" + "" + this._name + "</div></div>";


        //set digit of counter as two

        if (cart_counter < 10) {
            $("#cart_counter").text("0" + cart_counter);
        }
        else {
            $("#cart_counter").text(cart_counter);
        }
        if (cart_counter === 0) {
            $("#cart_counter").hide();
        }
        else {
            $("#cart_counter").show();
        }

        alert("Item Added!");
    }
    testAlert() {
        alert("Product Name: " + this._name + "<br/>Product Attribute: " + this._attribute + "<br/>Product Quantity: " + this._quantity + "<br/>Product Price: " + this._price);
    }
}
