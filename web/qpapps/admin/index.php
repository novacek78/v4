<?php

require '../../../init_admin.php';

// rozparsovanie slugu sa deje v init_admin.php
// v skriptoch su uz potom parametre pristupne cez premennu $_PARAMS[]

//echo '<h1>ahoj admin</h1>';
//echo '<p>Prislo mi toto:<br>' . var_export($_PARAMS, true) . '</p>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Prihlásenie</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">

</head>

<body>

<div class="container">

    <form class="form-signin">
        <h2 class="form-signin-heading">Prihlásenie</h2>
        <label for="inputUsername" class="sr-only">Meno</label>
        <input type="text" id="inputUsername" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Heslo</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Prihlásiť</button>
    </form>

</div>
<!-- /container -->

</body>
</html>
