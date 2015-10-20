<?php

class Quickplan_ControllerAjax extends Quickplan_ControllerAbstract
{

    public function run() {

        $methodName = Request::getParamByNum(2);

        if (method_exists($this, $methodName))
            $this->$methodName();
        else
            echo "Unknown method: ".get_class($this)."->$methodName()";

        exit;
    }

    protected function getEmailBody() {

        $emailUid = Request::getParamByName('uid', REQUEST_PARAM_INT);
        if (isset($emailUid)) {

            $EmailModel = new Quickplan_ModelEmail();
            $body = $EmailModel->getEmailBody($emailUid);

            echo $body;

        } else {
            echo "Email UID not set.";
        }
    }
}