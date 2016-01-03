<?php
namespace app\modules\angularjs\assets;

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
        'js/angular/ng-imgAreaSelect.js',
        'js/angular/mapper.js',
        'js/angular/imageApp.js',
    ];
    public $jsOptions = [
        'position' => View::POS_END,
    ];
    public $depends = [
        'app\modules\angularjs\assets\AngularAsset',
        'yii\web\JqueryAsset',
    ];
}
