<?php
namespace app\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Description of ImageAsset
 *
 * @author Trubachev Denis
 */
class ImageAsset2 extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
//        'js/angular/mapper.js',
        'js/angular/image2App.js',
//        'js/angular/jquery.maphilight.js',
//        'js/angular/angular-maphilights.js',
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
