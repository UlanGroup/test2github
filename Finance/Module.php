<?php

namespace app\Finance;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\Finance\Controller';


    public $layout = '/main';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
