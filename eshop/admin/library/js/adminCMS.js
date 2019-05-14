function loadDocSetting() {
    //click event of login button
    $("#btnLogin").click(function (e) {
        let admin;
        let userName = $("#username").val();
        let password = $("#password").val();
        //handle null input

        admin = new adminSession(userName, password)
        e.preventDefault();
        //debug
        admin.authenticateUser(userName, password);

        //alert(resultData["userName"] + resultData["token"]);
        //admin.loadCmsPage(token);
        //send ajax query 



    });
}

//jquery
$(document).ready(function () {
    loadDocSetting();
});