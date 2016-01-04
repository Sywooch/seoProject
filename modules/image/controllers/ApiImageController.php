<?php

namespace app\modules\image\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use \app\modules\image\models\ImageItem;
use \yii\web\UploadedFile;
use \app\modules\image\models\Area;
use \yii\helpers\Json;

class ApiImageController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['image'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['image'],
//            'rules' => [
//                [
//                    'actions' => ['dashboard'],
//                    'allow' => true,
//                    'roles' => ['@'],
//                ],
//            ],
//        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Handler for load all info about image
     * 
     * @param type $id
     * @return type
     */
    public function actionLoad($id)
    {
        $model = ImageItem::find($id)->with('areas');
        $image = $model->getImage();
        $areas = $model->areas;
        $areasResponse = [];
        foreach ($areas as $area) {
            $areasResponse[] = [
                'title' => $area->title,
                'points' => $area->area[0]
            ];
        }
        $response = [
            'image' => $image->getUrl(),
            'areas' => $areasResponse,
        ];
        return $response;
    }

    /**
     * Handler for upload file
     * 
     * @return type
     */
    public function actionUpload()
    {
        $file = UploadedFile::getInstanceByName('file');
        $resul = $file->saveAs('uploads/tmp/' . $file->baseName . '.' . $file->extension);
        $fileUrl = '/uploads/tmp/' . $file->baseName . '.' . $file->extension;
        return [
            'file' => $fileUrl
        ];
    }

    /**
     * Handler for save information about image
     * @return type
     */
    public function actionSave()
    {
        $request = \Yii::$app->request;
        $name = $request->get('name');
        $file = $request->get('file');
        $id = $request->get('id', false);
        if ($id) {
            $model = ImageItem::find($id);
        } else {
            $model = new ImageItem();
        }
        $model->user_id = Yii::$app->user->getId();
        $model->name = $name;
        if ($model->save()) {
            $filePath = \Yii::getAlias('@webroot/') . $file;
            $model->attachImage($filePath, true);
            return [
                'success' => true,
                'id' => $model->getPrimaryKey(),
                'name' => $model->name,
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }
    
    /**
     * Handler for save area
     * 
     * @return type
     */
    public function actionAddArea()
    {
        $request = \Yii::$app->request;
        $get = $request->get();
        
        $id = $request->get('id', false);
        $area = $request->get('area');
        $area = Json::decode($area);
        $title = $request->get('title', '');
        $points = [];
        foreach ($area as $point) {
            $points[] = [$point['x'], $point['y']];
        }
        $areaModel = new Area();
        $areaModel->title = $title;
        $areaModel->item_id = $id;
        $areaModel->area = [$points];
        if ($areaModel->save()) {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }

}
