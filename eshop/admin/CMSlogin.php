<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <!-- block search engine-->
    <meta name="robots" content="noindex, nofollow">
    <!-- turn responsive on/off-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
    <meta name="description" content="Eshop of Hong Kong Arabcci Chamber">

    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

    <title>Arabcci CMS</title>

    <!-- Jquery -->
    <script src="../library/Jquery_331/jquery-3.3.1.slim.min.js">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
    <script src="../library/popperjs/popper_1.14.7.min.js"></script>

    <script src="../library/bootstrap_431/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../library/bootstrap_431/css/bootstrap.min.css">

    <!-- Font Awesome library -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="library/css/signin.css" rel="stylesheet">


    <!-- Custom javascript for this template -->
    <script src="library/js/loginClass.js"></script>
    <script src="library/js/adminCMS.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div id="content" class="col-12">
                <form class="form-signin" autocomplete="on">
                    <h1 class="text-center pb-5 w-100">Arabcci Chamber CMS</h1>

                    <label for="inputEmail" class="sr-only">User Name</label>
                    <input autocomplete="on" id="username" type="text" id="inputEmail" class="form-control"
                        placeholder="User Name" required autofocus>
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input autocomplete="on" id="password" type="password" id="inputPassword" class="form-control"
                        placeholder="Password" required>
                    
                    <button id="btnLogin" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                    <p class="mt-5 mb-3 text-muted">&copy; 2017-2019</p>
                </form>
            </div>

        </div>

    </div>
</body>

</html>