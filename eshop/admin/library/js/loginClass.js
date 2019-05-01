class adminUser {
    constructor(userName, password) {
        this._userName = userName;
        this._password = password;
        this._token = $('meta[name="csrf-token"]').attr('content');
    }

    authenticateUser() {

        $.ajax({
            type: 'POST',

            url: 'library/php/userAuthentication.php',


            data: {
                //query '?list='+listStr
                userName: this._userName,
                password: this._password,
                token: this._token,
            },

            success: function (data) {
                let result;
                //echo what the server sent back...
                //result = JSON.parse(data);
                //console.log(result);
                if (data == "false") {
                    alert("Wrong login information!");
                }
                else {
                    // loadCmsPage(data);

                    $("body").html(data);
                }


            }


        });
    }
    loadCmsPage(encryptToken) {
        //load the cms page with encrypted token
        $.ajax({
            type: 'POST',
            //url: 'layouts/shop_checkout.php?list=' + listSTr,
            url: "",// to the cms index page at layout


            data: {
                //query '?list='+listStr
                userName: this._userName,
                password: this._password,
                token: encryptToken,
            },

            success: function (data) {
                $("body").html(data);
            }

        });
    }

}