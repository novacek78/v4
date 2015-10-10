<style type="text/css" media="screen">
    <!--
    a.faq_q {
        display: block;
        font-weight: bold;
        cursor: help;
        text-decoration: none;
        margin-top: 7px;
    }

    a.faq_q:hover {
        text-decoration: underline;
    }

    div.faq_a {
        display: none;
        cursor: help;
        margin-bottom: 15px;
        margin-top: 5px;
        border: 1px solid #ccc;
        padding: 3px 15px;
    }

    h1.faq_group {
        font-family: arial;
        font-size: 15px;
        font-weight: bold;
        padding-left: 5px;
        margin-top: 25px;
        margin-bottom: 10px;
        background-color: #eee;
        border: 1px solid #ccc;
    }

    /
    /
    -->
</style>

<script type="text/javascript" language="JavaScript">
    <!--
    function ShowAnswer(cislo) {
        if (document.getElementById('a' + cislo).style.display == 'block')
            document.getElementById('a' + cislo).style.display = 'none';
        else
            document.getElementById('a' + cislo).style.display = 'block';
    }
    //-->
</script>

<h1><?php echo TransT(12) ?></h1><br>


<h1 class="faq_group"><?php echo TransT(15) ?></h1> <!-- VSEOBECNE OTAZKY -->

<?php

$q = 1;
$trans_id = 20;
$otazok = 9;

for ($i = 0; $i < $otazok; $i++) {
    echo "<a class='faq_q' onClick='ShowAnswer($q)'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</a><div class='faq_a' onClick='ShowAnswer($q)' id='a" . $q++ . "'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</div>\n\n";
}
?>



<h1 class="faq_group"><?php echo TransT(38) ?></h1> <!-- PRACA V PROGRAME -->

<?php

$trans_id++; // lebo na nadpis sekcie sa pouzilo jedno ID
$otazok = 7;

for ($i = 0; $i < $otazok; $i++) {
    echo "<a class='faq_q' onClick='ShowAnswer($q)'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</a><div class='faq_a' onClick='ShowAnswer($q)' id='a" . $q++ . "'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</div>\n\n";
}
?>



<h1 class="faq_group"><?php echo TransT(53) ?></h1> <!-- MATERIAL PANELA -->

<?php

$trans_id++; // lebo na nadpis sekcie sa pouzilo jedno ID
$otazok = 8;

for ($i = 0; $i < $otazok; $i++) {
    echo "<a class='faq_q' onClick='ShowAnswer($q)'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</a><div class='faq_a' onClick='ShowAnswer($q)' id='a" . $q++ . "'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</div>\n\n";
}
?>



<h1 class="faq_group"><?php echo TransT(70) ?></h1> <!-- TEXTOVY POPIS PANELA -->

<?php

$trans_id++; // lebo na nadpis sekcie sa pouzilo jedno ID
$otazok = 6;

for ($i = 0; $i < $otazok; $i++) {
    echo "<a class='faq_q' onClick='ShowAnswer($q)'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</a><div class='faq_a' onClick='ShowAnswer($q)' id='a" . $q++ . "'>\n";
    echo TransT($trans_id++) . "\n";
    echo "</div>\n\n";
}
?>
