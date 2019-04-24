class adminUser {
    constructor(userName, password) {
        this._userName = userName;
        this._password = password;
    }

    testAlert() {

        alert("user: " + this._userName + "password: " + this._password);
    }
    jslogin() {
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
                document.getElementById("content").innerHTML = xhttp.responseText;

            }
        };
        xhttp.open("GET", 'library/php/userAuthentication.php', true);
        xhttp.send();
    }
    login() {
        let sourceURL = window.location.href;
        $.ajax({
            type: 'GET',
            //url: 'layouts/shop_checkout.php?list=' + listSTr,
            url: 'library/php/userAuthentication.php',
            data: {
                //query '?list='+listStr
                userName: this._userName,
                password: this._password,
                source: sourceURL
            },

            success: function (data) {

                //echo what the server sent back...
                $("body").html(data);
            }


        });
    }
}