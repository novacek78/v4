<?php

class Quickplan_ModelUser
{

    public function login($data) {

        $dbData = $this->getUserData($data['username']);

        if ($dbData['login'] == $data['username']) {
            $_SESSION['isLoggedIn'] = true;
            return true;
        } else {
            return false;
        }
    }

    public function getUserData($loginName) {

        $data = Db::fetchAll('SELECT * FROM `user` WHERE login = ?', $loginName);

        return $data[0];
    }
}