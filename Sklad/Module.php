<?php

namespace app\Sklad;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\Sklad\Controller';


    public $layout = '/main';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
