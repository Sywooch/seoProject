<?php
namespace app\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Description of ImageAsset
 *
 * @author Trubachev Denis
 */
class ImageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/image/canvasDrawer.js',
        'js/image/serverside.js',
        'js/image/imageCtrl.js',
    ];
    public $css = [
        'css/image.css'
    ];
    public $jsOptions = [
        'position' => View::POS_END,
    ];
    public $depends = [
        'app\modules\image\assets\AngularAsset',
        'yii\web\JqueryAsset',
    ];
}
