<?php

DajVar('msg', false, '/^[a-z,A-Z,_,0-9]{2,}$/');

?>

<p>&nbsp;</p>

<?php
if ($msg == 'ok') {
    ?>
    <h2 class="checkmark"><?php echo TransT(500) ?></h2>
    <p class="checkmark"><?php echo TransT(501) ?></p>
<?php
} elseif ($msg == 'err_np') { // Tuto objednavku nie je mozne potvrdit - kontaktujte nas prosim.
    ?>
    <h2 class="redcross"><?php echo TransT(510) ?></h2>
    <p class="redcross"><?php echo TransT(511) ?></p>
<?php
} elseif ($msg == 'err_ac') { // Tato objednavka uz bola potvrdena.
    ?>
    <h2 class="redcross"><?php echo TransT(520) ?></h2>
    <p class="redcross"><?php echo TransT(521) ?></p>
<?php
} elseif ($msg == 'err_ns') { // Tato objednavka este nebola odoslana uzivatelovi na potvrdenie.
    ?>
    <h2 class="redcross"><?php echo TransT(530) ?></h2>
    <p class="redcross"><?php echo TransT(531) ?></p>
<?php
} elseif ($msg == 'err_ad') { // Nepovoleny pristup.
    ?>
    <h2 class="redcross"><?php echo TransT(540) ?></h2>
    <p class="redcross"><?php echo TransT(541) ?></p>
<?php
} elseif ($msg == 'err_me') { // mail error : Mail o novej objednavke neposlany zakaznikovi
    ?>
    <h2 class="redcross"><?php echo TransT(550) ?></h2>
    <p class="redcross"><?php echo TransT(551) ?></p>
<?php
} else { // ina chyba
    ?>
    <h2 class="redcross"><?php echo TransT(580) ?></h2>
    <p class="redcross"><?php echo TransT(581) ?></p>
<?php
}
?>
<p>&nbsp;</p>