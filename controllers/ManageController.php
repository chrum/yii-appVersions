<?php

class ManageController extends EController
{
    public function accessRules()
    {
        $roles = array('admin', 'manager');
        $langs = Yii::app()->params['langs'];
        foreach($langs as $code => $name) {
            $roles[] = "translator-".$code;
        }
        return array(
            array('allow', 'roles'=>$roles),
            array('deny'),
        );
    }

    public function actionIndex() {
        if (isset($_REQUEST['currentVersion'])) {
            $currentVersion = Versions::model()->findByPk($_REQUEST['currentVersion']);

        }
        if (empty($currentVersion)) {
            $currentVersion = Versions::getCurrentVersion();
        }
        if (empty($currentVersion)) {
            $currentVersion = Versions::model()->find();
        }
        $versionsList = Versions::getVersionsList();


        $this->render('index', array(
            "currentVersion" => $currentVersion,
            "list" => $versionsList
        ));
    }

    public function actionSave() {
        $result = new stdClass();
        if (isset($_REQUEST['version']) && isset($_REQUEST['message'])) {
            $version = Versions::model()->findByPk($_REQUEST['version']);
            if ($version == null) {
                $result->error = true;

            } else {
                $version->message = json_encode($_REQUEST['message']);
                $version->save();
            }
        }

        $this->renderJson($result);
        Yii::app()->end();
    }

}