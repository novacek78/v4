<?php

require '../../../init_wiki.php';

session_start();

Request::initParam('p', 'string');

if ( ! isset($_SESSION['language'])) {
    $_SESSION['language'] = Request::getPreferredLanguage();
}

if (file_exists(TRANS_DIR . TRANS_FILE_PREFIX . $_SESSION['language'] . '.php'))
    include TRANS_DIR . TRANS_FILE_PREFIX . $_SESSION['language'] . '.php';
else
    include TRANS_DIR . TRANS_FILE_PREFIX . 'en.php';


?><!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title><?php echo TransT(1) ?></title>
    <link rel='stylesheet' href='css/wiki.css' type='text/css'>
</head>
<body>

<table width="100%">
    <tr>
        <td style="padding-right: 15px">
            <?php
            if (isset($_PARAMS['p'])) {
                include "$_PARAMS[p].php";
            } else {
                echo TransT(2) . "<br />" . TransT(3);
            }
            ?>
        </td>
        <td id='menu'>
            <?php
            include "menu.php";
            ?>
        </td>
    </tr>
</table>

</body>
</html>