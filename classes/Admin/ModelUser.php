<?php

class Admin_ModelUser
{

    public function login($data) {

        if ($data['username'] == 'enovacek@trionyx.sk') {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['loggedUsername'] = $data['username'];
            return true;
        } else {
            return false;
        }
    }
}