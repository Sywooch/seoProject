<?php

namespace app\modules\image\controllers;

use yii\web\Controller;
use app\modules\image\models\ImageItem;
use \app\modules\image\models\Area;
use \nanson\postgis\helpers\GeoJsonHelper;
use nanson\postgis\behaviors\GeometryBehavior;

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
//        $this->layout = 'simple';
        return $this->render('image2');
    }

    public function actionTest()
    {
        $model = new ImageItem();
        $model->name = 'Test Image';
        $model->save();
        $id = $model->getPrimaryKey();
        $model->attachImage(\Yii::getAlias('@webroot/images/motor.png'), true);
        $area = new Area();
        $area->title = 'Test area';
        $area->area = [
                    [
                        [110, 71],
                        [210, 71],
                        [210, 151],
                        [110, 151]
                    ]
        ];
        $area->save();
        //Returns main model image
        $image = $model->getImage();

        if ($image) {
            //get path to resized image 

            //path to original image
   echo $image->getUrl();
        }
        $area = Area::findOne(1);
        var_dump($area->area);
    }

}
