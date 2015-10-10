<?php

require '../init_frontend.php';

session_start();

Request::initParam('p', 'string', false, '/^[a-z,A-Z,-]{3,}$/', 'home');
Request::initParam('lang', 'string', false, '/^[a-z,A-Z]{2}$/');


if (isset($_PARAMS['lang']))
    $_SESSION['language'] = $_PARAMS['lang'];

if ( ! isset($_SESSION['language']))
    $_SESSION['language'] = Request::getPreferredLanguage();

if (file_exists(TRANS_DIR . TRANS_FILE_PREFIX . $_SESSION['language'] . '.php'))
    include TRANS_DIR . TRANS_FILE_PREFIX . $_SESSION['language'] . '.php';
else
    include TRANS_DIR . TRANS_FILE_PREFIX . 'en.php';


/*
  vsade kde je uvedena cesta je na zaciatok pridane "../" to preto, lebo v URL sa pouziva identifikator jazyka ako /en, /sk, atd.
  takze browser musi vediet, ze musi "vyjst" z tohto akoze adresara
*/

?><!DOCTYPE HTML>
<html>
<head>
    <link href='img/favicon.ico' rel='icon'/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?php echo TransT(1) ?></title>
    <link rel="stylesheet" href="../css/index.css" type="text/css">

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-2612009-2']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>

</head>
<body>


<div id="headerbar">

    <div style="height: 80px"> <!-- nastavenie vysky pre logo a title (aby bolo OK aj ked je uzke okno) -->

        <div id="headerbar-toplogo">
            <a href="/<?php echo $_SESSION['language'] ?>/quickpanel-front-panels-home"><img src="../img/logo.png"
                                                                                             alt="QuickPanel logo"
                                                                                             title="QuickPanel"
                                                                                             style="margin: 0 20px 0 0;"></a>
        </div>
        <div id="headerbar-toptitle">
            <h1><?php echo TransT(2) ?></h1><h4><?php echo TransT(3) ?></h4>
        </div>

    </div>

    <br style="clear: left">

    <div id="headerbar-topmenu">
        <a href="/<?php echo $_SESSION['language'] ?>/gallery-sample-front-panels" class="toplink">
            <img src="../img/camera.png"><span><h3><?php echo TransT(30) ?></h3><br><?php echo TransT(31) ?></span>
        </a>

        <a href="/<?php echo $_SESSION['language'] ?>/how-to-wiki-faq" class="toplink" target="_blank">
            <img src="../img/books.png"><span><h3><?php echo TransT(32) ?></h3><br><?php echo TransT(33) ?></span>
        </a>

        <a href="/<?php echo $_SESSION['language'] ?>/download-quickpanel-program-software" class="toplink">
            <img src="../img/windows7.png"><span><h3><?php echo TransT(34) ?></h3><br><?php echo TransT(35) ?></span>
        </a>
    </div>

</div>


<div id="bodybar">
    <div id="contentbar">
        <?php
        if (file_exists("p_$_PARAMS[p].php"))
            include "p_$_PARAMS[p].php";
        else
            include "p_home.php";
        ?>
        <p style="height: 50px"> &nbsp; </p>
    </div>

    <div id="footer">
        <table>
            <tr>
                <td><?php echo TransT(40) ?>:<br/>
                    <a href="../index.php?lang=sk"><img title="Slovensky" alt="Slovensky" src="../img/flag-slovakia.png"
                                                        class="flag"></a>
                    <a href="../index.php?lang=cs"><img title="Česky" alt="Česky" src="../img/flag-czechrepublic.png"
                                                        class="flag"></a>
                    <a href="../index.php?lang=en"><img title="English" alt="English"
                                                        src="../img/flag-unitedkingdom.png" class="flag"></a>
                    <!-- <a href="../index.php?lang=de"> --><img title="Deutsch" alt="Deutsch"
                                                                 src="../img/flag-germany-off.png" class="flag"><!-- </a> -->
                </td>
                <td style='padding-left: 150px;'>
                    <?php echo TransT(41) ?>:<br/>
        <span class='visibleText'>
          <?php echo TransT(42) ?><br/>
            <?php echo TransT(43) ?><br/>
          <br/>
          Project supported by Brevis &amp; Tener<br/>
          <br/>
        </span>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php
if ($_PARAMS['p'] == 'download-now') {
    ?>
    <script type="text/javascript">
        document.location = '../files/download/<?php echo $filename?>';
    </script>
<?php
}
?>

</body>
</html>
