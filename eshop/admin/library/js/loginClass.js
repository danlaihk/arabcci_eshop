class adminSession {
    constructor(userName, password) {
        this._userName = userName;
        this._password = password;
        this._token = $('meta[name="csrf-token"]').attr('content');
    }


    authenticateUser(userName, password) {

        var token = this._token;
        $.ajax({
            type: 'POST',

            url: 'library/php/userAuthentication.php',


            data: {
                //query '?list='+listStr
                userName: userName,
                password: password,
                token: token,
            },

            success: function (data) {
                var returnObj = JSON.parse(data);
                // alert(returnObj["correct"] + returnObj["userName"] + returnObj["token"]);
                if (returnObj["correct"] == false) {
                    alert('wrong login info!!');
                }
                if (returnObj["correct"] == true) {

                    window.location.replace('layouts/cPanel.php');
                }


            }


        });
    }


}
function loadCMSIndex(name, returnToken) {
    var userName = name;
    var token = returnToken;
    $.ajax({
        type: 'POST',
        async: true,
        url: 'layouts/cPanel.php',

        data: {

            userName: userName,
            token: token

        },

        success: function (response) {
            $("body").html(response);
            $("body").removeClass("loginBody");
        }

    });
}