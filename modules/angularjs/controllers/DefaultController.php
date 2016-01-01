<?php

namespace app\modules\angularjs\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = 'main';
    public function actionIndex()
    {
        $this->view->title = 'My Angular Yii Application';
        return $this->render('index');
    }
    
    public function actionImage()
    {
        return $this->render('image');
    }
}
