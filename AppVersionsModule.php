<?php
/**
 * Created by PhpStorm.
 * User: chrystian
 * Date: 9/26/14
 * Time: 9:56 AM
 */

class AppVersionsModule extends CWebModule
{
    public $defaultController='manage';

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            'appversions.controllers.*',
            'appversions.views.*',
            'appversions.models.*',
            'translations.helpers.*'
        ));

    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }
} 