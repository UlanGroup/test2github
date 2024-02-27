<?php

namespace app\User;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\User\Controller';


    public $layout = '/main';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}