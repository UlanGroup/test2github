<?php

namespace app\Product;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'app\Product\Controllers';


    public $layout = '/main';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
