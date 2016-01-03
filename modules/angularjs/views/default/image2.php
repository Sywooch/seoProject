<?php
use \yii\bootstrap\Html;
use app\modules\angularjs\assets\ImageAsset2;
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
        <area ng-repeat="area in areas" coords="{{getCoordinats(area)}}" shape="poly" alt="alt" title="title" class="noborder icolor00ff00" href="#" id="imgmap_{{$index}}" onmouseover="setAreaOver(this,'myimage_canvas','0,0,255','0,0,0','0.33',0,0,0);null" onmouseout="setAreaOut(this,'myimage_canvas',0,0);null">
    </map>

    <div class="bar" id="bar">
        <button ng-click="clear">clear all</button>
        <button ng-click="addBtn()" ng-hide="active">Add area</button>
        <button ng-click="saveBtn()" ng-show="active">save area</button>
        <button ng-click="saveBtn()" ng-show="active">save area</button>
    </div>
    <div class="info" id="info"></div>    
</div>



<style type="text/css">
    body {
        margin: 0;
        padding: 20px;
        font-family: Arial, Helvetica, sans-serif;
    }
    img {
        border: none;
        outline: none;
        display: block;
        -moz-user-select: none;
        -webkit-user-select: none;
        user-select: none;
    }
    .canvas {
        border: 2px solid #333;
        padding: 2px;
        margin-bottom: 16px;
        display: inline-block;
        //display: inline;
        //zoom:1;
    }
    .canvas.draw {
        border-color: #3C0;
    }
    .canvas .inner {
        position: relative;
    }
    .canvas .point {
        width: 1px;
        height: 1px;
        background-color: #fff;
        border: 1px solid #000;
        overflow: hidden;
        position: absolute;
    }
    .bar {
        margin-bottom: 16px;
    }
    .info {
        background-color: #FCFCFC;
        border: 1px dotted #CCC;
        font-size: 12px;
        font-style: italic;
        padding: 8px;
        word-wrap: break-word;
    }
</style>