<?php
namespace app\modules\image\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * Description of TestAssets
 *
 * @author Trubachev Denis
 */
class TestAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/angular/testApp.js',
    ];
    public $jsOptions = [
        'position' => View::POS_END,
    ];
    public $depends = [
        'app\modules\image\assets\AngularAsset',
    ];
}
