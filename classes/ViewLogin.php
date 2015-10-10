<?php

class ViewLogin extends View
{

    public function render() {

        parent::renderHeader();

        echo '<title>Prihlásenie</title>

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
            <input type="text" id="inputUsername" class="form-control" placeholder="Meno" required autofocus>
            <label for="inputPassword" class="sr-only">Heslo</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Heslo" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Prihlásiť</button>
        </form>
    </div>';

        parent::renderFooter();
    }
}