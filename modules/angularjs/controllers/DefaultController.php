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
        $this->layout = 'simple';
        return $this->render('image');
    }
    
    public function actionImage2()
    {
        $this->layout = 'simple';
        return $this->render('image_angular');
    }
    public function actionImage3()
    {
        $this->layout = 'simple';
        return $this->render('image2');
    }
}
