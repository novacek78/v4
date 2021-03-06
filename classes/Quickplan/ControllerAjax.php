<?php

class Quickplan_ControllerAjax extends Quickplan_ControllerAbstract
{

    /**
     * Poradove cislo slugu GET parametra "uri" kde sa nachadza nazov metody
     *
     * @var int
     */
    private $_methodNameParameterNumber = 2;


    public function run() {

        $methodName = Request::getParamByNum($this->_methodNameParameterNumber);

        if (method_exists($this, $methodName))
            $this->$methodName();
        else
            echo "Unknown method: ".get_class($this)."->$methodName()";

        exit;
    }

    protected function getEmailBody() {

        $Params = Request::getGetData();

        if (isset($Params->uid)) {

            $ImapServer = new Quickplan_ModelImapServer();
            $body = $ImapServer->getEmailBody($Params->uid);
            $ImapServer->closeMailServerConnection();

            echo $body;

        } else {
            echo "Email UID not set.";
        }
    }
}