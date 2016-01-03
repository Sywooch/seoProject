<div ng-controller="ImageController">
<?php
use \yii\bootstrap\Html;
use \xj\imgareaselect\ImgAreaSelect;
use \xj\imgareaselect\ImgareaselectAsset;
use \app\modules\image\assets\TestAssets;
use app\modules\image\assets\ImageAsset;
ImgareaselectAsset::registerWithStyle($this, ImgareaselectAsset::STYLE_ANIMATED);
ImageAsset::register($this);
$id = '';
echo Html::img('@web/images/motor.png', [
    'id' => 'myimage',
    'usemap' => '#imgmap' . $id,
    'ng-image-area-select' =>"",
]);
?>
<map name="imgmap<?=$id?>">
    <area ng-repeat="area in areas" coords="{{getCoordinats(area)}}" shape="rect" alt="alt" title="title" class="noborder icolor00ff00" href="#">
</map>
    
</div>
