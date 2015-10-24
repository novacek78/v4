<?php

class Quickplan_ModelUser
{

    /**
     * Prihlasi uzivatela do systemu
     *
     * @param stdClass $DataObj
     * @return bool
     */
    public function login($DataObj) {

        $dbData = $this->getUserData($DataObj->username);

        if ($dbData['login'] == $DataObj->username) {
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