<?php

/**
 * Created by PhpStorm.
 * User: enovacek
 * Date: 23. 10. 2015
 * Time: 20:47
 */
class Db {

    /**
     * @var PDO
     */
    private static $_connection = null;

    private static $_dbEngine = null;
    private static $_dbHost = null;
    private static $_dbName = null;
    private static $_dbUser = null;
    private static $_dbPwd = null;

    /**
     * Vykona SQL dotaz a vrati pole vysledkov
     *
     * @param string $sql
     * @param mixed  $externalData Data pre vlozenie do dotazu na miesta '?'
     * @return array|false
     */
    public static function fetchAll($sql, $externalData = null) {

        self::_connect();

        if (isset($externalData)) {

            // s bindovanim uzivatelskych dat
            if ( ! is_array($externalData))
                $externalData = array($externalData);
            $Stmnt = self::$_connection->prepare($sql);
            $Stmnt->execute($externalData);
        } else {

            // bez bindovania dat
            $Stmnt = self::$_connection->query($sql);
        }

        return $Stmnt->fetchAll();
    }

    private static function _connect() {

        if ( isset(self::$_connection))
            return;

        if (empty(self::$_dbHost))
            self::init(DB_NAME, DB_USER, DB_PWD, DB_HOST, DB_ENGINE);

        self::$_connection = new PDO(
            self::$_dbEngine .
            ':host=' . self::$_dbHost .
            ';dbname=' . self::$_dbName .
            ';charset=utf8',
            self::$_dbUser,
            self::$_dbPwd,
            array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false));
    }

    public static function init($dbName, $userName, $pwd, $dbHost, $dbEngine) {

        self::$_dbHost = $dbHost;
        self::$_dbEngine = $dbEngine;
        self::$_dbName = $dbName;
        self::$_dbUser = $userName;
        self::$_dbPwd = $pwd;
    }

    /**
     * Vykona SQL dotaz (vacsinou pre INSERT, DELETE a UPDATE)
     *
     * @param string $sql
     * @param null   $externalData
     */
    public static function exec($sql, $externalData = null) {

        self::_connect();

        if (isset($externalData) && ! is_array($externalData))
            $externalData = array($externalData);

        $Stmnt = self::$_connection->prepare($sql);
        $Stmnt->execute($externalData);
    }

    /**
     * Vrati ID posledneho vlozeneho riadku
     *
     * @return string
     */
    public static function lastInsertId() {

        self::_connect();

        return self::$_connection->lastInsertId();
    }
}