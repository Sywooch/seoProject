<?php
use \yii\bootstrap\Html;
use \xj\imgareaselect\ImgAreaSelect;
use \xj\imgareaselect\ImgareaselectAsset;
use \yii\web\View;
use app\modules\image\assets\ImageAsset;
//TestAssets::register($this);
ImageAsset::register($this);
$id = '';
echo Html::img('@web/images/motor.png', [
    'id' => 'myimage',
    'usemap' => '#imgmap' . $id,
    'class' => 'mapper',
]);

$content = '<area coords="110,71, 210,151" shape="rect" alt="alt" title="title" class="noborder icolor00ff00" href="#">';
$areas = [];
foreach ($areas as $area) {
    $content .= Html::tag('area', '',
        [
            'shape' => 'rect',
            'alt' => $area->name,
            'title' => $area->name,
            'coords' => $area->getCoordsString(),
            'href' => '#' . $area->id
        ]
    );
}
echo Html::tag('map', $content, [
    'name' => 'imgmap' . $id,
]);

ImgAreaSelect::widget([
    'id' => '#myimage',
    'style' => ImgareaselectAsset::STYLE_ANIMATED, //default STYLE_DEFAULT
    'position' => View::POS_READY, //default POS_LOAD
    'clientOptions' => [
        'maxWidth' => 100,
        'maxHeight' => 100,
        'onInit' => 'function(img, selection){console.log("event: init");}',
        'onSelectStart' => 'function(img, selection){console.log("event: select start");}',
        'onSelectChange' => 'function(img, selection){console.log("event: select change");}',
        'onSelectEnd' => 'function(img, selection){console.log(selection);}',
    ]
]);
yii\web\JqueryAsset::register($this);
?>