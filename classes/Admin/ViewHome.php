<?php

class Admin_ViewHome extends Admin_ViewAbstract
{

    public function render() {

        parent::renderHeader();

        echo '<title>Domovska stranka</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/login.css" rel="stylesheet">

    </head>

    <body>

    <div class="container">
        <h2 class="form-signin-heading">Domovska stranka</h2>
    </div>';

        parent::renderFooter();
    }
}