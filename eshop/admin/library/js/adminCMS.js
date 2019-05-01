function loadDocSetting() {
    //click event of login button
    $("#btnLogin").click(function (e) {
        let userName = $("#username").val();
        let password = $("#password").val();
        //handle null input

        let user = new adminUser(userName, password)
        e.preventDefault();
        //debug
        user.authenticateUser();

        //send ajax query 



    });
}

//jquery
$(document).ready(function () {
    loadDocSetting();
});