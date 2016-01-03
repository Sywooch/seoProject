<?php
use \yii\bootstrap\Html;
use app\modules\image\assets\ImageAsset2;
ImageAsset2::register($this);
$id = '';
?>
<div ng-controller="Image2Ctrl">
    <div class="preview" id="preview">
        <div class="inner" id="draw" ng-mousedown="addPoint($event)">
            <canvas id="canvas" ></canvas>
            <?php
            echo Html::img('@web/images/motor.png', [
                'id' => 'myimage',
                'usemap' => '#imgmap' . $id,
                'class' => 'mapper',
                'ng-image-area-select' => "",
            ]);
            ?>
        </div>
    </div>
    <map name="imgmap<?= $id ?>">
        <area ng-repeat="area in areas" coords="{{getCoordinats(area)}}" shape="poly" alt="alt" title="{{area.title}}" class="noborder icolor00ff00" href="#" id="imgmap_{{$index}}" onmouseover="setAreaOver(this,'myimage_canvas','0,0,255','0,0,0','0.33',0,0,0);null" onmouseout="setAreaOut(this,'myimage_canvas',0,0);null">
    </map>

    <div class="bar" id="bar">
        <button ng-click="clear">clear all</button>
        <button ng-click="addBtn()" ng-hide="active"><?=  \Yii::t('image', 'Add area')?></button>
        <button ng-click="saveBtn()" ng-show="active"><?=  \Yii::t('image', 'Save area')?></button>
        <input type="text" ng-model="title" placeholder="<?=  \Yii::t('image', 'Title')?>"  ng-show="active" />
    </div>
    <div class="info" id="info"></div>    
</div>