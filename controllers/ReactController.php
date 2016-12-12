<?php

namespace app\controllers;


use yii\web\Controller;

class ReactController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRedux()
    {
        return $this->render('redux');
    }
}