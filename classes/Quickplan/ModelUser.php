<?php

class Quickplan_ModelUser {

    /**
     * Prihlasi uzivatela do systemu
     *
     * @param stdClass $DataObj
     * @return bool
     */
    public function login($DataObj) {

        $dbData = $this->getUserData($DataObj->username);

        if ( ! empty($dbData) && ($dbData['login'] == $DataObj->username)) {
            $_SESSION['isLoggedIn'] = true;
            return true;
        }

        return false;
    }

    public function getUserData($loginName) {

        $data = Db::fetchAll('SELECT * FROM `user` WHERE login = ?', $loginName);

        return $data[0];
    }
}