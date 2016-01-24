<?php

require '../../../init_quickplan.php';

try {

    $Application = new Application();
    $Application->run();
} catch (Exception $e) {

    $userRandomId = uniqid();
    Logger::error("Error ID: $userRandomId");
    Logger::error("ENV: " . ENV);
    Logger::error($e->getMessage());
    Logger::error("    URL request: $_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]");
    $errorMsg = 'Chyba, kontaktujte prosim administratora s tymto kodom: ' . $userRandomId;
}



if ( ! isset($errorMsg)) exit;

// rozparsovanie slugu sa deje v init_admin.php
// v skriptoch su uz potom parametre pristupne cez premennu $_PARAMS[]

//echo '<h1>ahoj admin</h1>';
//echo '<p>Prislo mi toto:<br>' . var_export($_PARAMS, true) . '</p>';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>QuickPanel - chyba</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">

</head>
<body>

<div class="container">
    <h2 class="form-signin-heading"><?php echo $errorMsg ?></h2>
</div>

</body>
</html>
