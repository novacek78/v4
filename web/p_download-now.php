<?php
// tento skript vlastne len pripravi nazov suboru, ktory sa bude stahovat a ulozi ho do premennej FILENAME
// tato premenna je nasledne v hlavnom INDEX.PHP vyuzita na stiahnutie suboru

Request::getParam('partnernick', 'string', false, '', 'quickpanel');


if ( ! isset($_SESSION['partnernick']) || empty($_SESSION['partnernick'])) {
    $_SESSION['partnernick'] = $_PARAMS['partnernick'];
}

$filename = "quickpanel_" . $_SESSION['partnernick'] . "_setup.zip";

// ak by nahodou neexistoval subor s menom partnera, stiahneme svoj
if (($_SESSION['partnernick'] == 'quickpanel') || ( ! file_exists("files/download/$filename"))) {
    $filename = "quickpanel_setup.zip";

    // ak by som zabudol premenovat subor, tak skusi najst este tento
    if ( ! file_exists("files/download/$filename"))
        $filename = "quickpanel_quickpanel_setup.zip";
}
?>

<h2><?php echo TransT(53) ?></h2>
<p><?php echo TransT(54) ?><a href="../files/download/<?php echo $filename ?>"><?php echo TransT(55) ?></a>.</p>
<p>&nbsp;</p>
