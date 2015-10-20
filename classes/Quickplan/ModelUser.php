<?php

class Quickplan_ModelUser
{

    public function login($data) {

//        if (($data['username'] == 'enovacek') && ($data['password'] == 'heslo')){
        if (true) {
            $_SESSION['isLoggedIn'] = true;
            return true;
        } else {
            return false;
        }
    }
}