<?php

namespace app\Wildberries;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\Wildberries\Controller';


    public $layout = '/main';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
