<?php

abstract class View
{


    /**
     * @var array
     */
    protected $_data;


    public abstract function render();


    public function setData($data) {

        $this->_data = $data;
    }

    public function renderHeader() {

        echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">
    ';
    }

    public function renderFooter() {

        echo '</body>
    </html>
    ';
    }
}