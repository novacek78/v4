<?php

class Admin_ModelUser
{

    /**
     * Prihlasi uzivatela do systemu
     *
     * @param stdClass $DataObj
     * @return bool
     */
    public function login($DataObj) {

        if ($DataObj->username == 'enovacek@quickpanel.sk') {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['loggedUsername'] = $DataObj->username;
            return true;
        } else {
            return false;
        }
    }
}