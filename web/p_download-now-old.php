<?php
include_once '../config_location.php';
include_once '../admin.php';
include_once '../lib_db.php';


session_start();

DajVar('partnernick');

if ($_SESSION['partnernick'] == ''){
  if (IsSet($partnernick))
    $_SESSION['partnernick'] = $partnernick;
  else
    $_SESSION['partnernick'] = 'quickpanel';
}

$filename = "quickpanel_".$_SESSION['partnernick']."_setup.zip";

if (($_SESSION['partnernick'] == 'quickpanel') || (!file_exists("download/".$filename)))
  $filename = "quickpanel_setup.zip";
  

?><!DOCTYPE HTML>
<html>
  <head>
  <link href='img/favicon.gif' rel='icon' type='image/gif'/> 
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>QuickPanel - Download</title>
  <link rel="stylesheet" href="../css/index.css" type="text/css">
  </head>
  <body>

  <h2><?php echo TransT(53) ?></h2>
  <p><?php echo TransT(54) ?><a href="../download/<?php echo $filename ?>"><?php echo TransT(55) ?></a>.</p>

  <script type="text/javascript">
  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
  </script>
  <script type="text/javascript">
  try {
  var pageTracker = _gat._getTracker("UA-2612009-2");
  pageTracker._trackPageview();
  } catch(err) {}
  </script>

  <script type="text/javascript">
  document.location = '../download/<?php echo $filename?>';
  </script>
  
  </body>
</html>
