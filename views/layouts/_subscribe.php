<?php
/* @var $this \yii\web\View */
use \app\models\Subscribe;
use \app\assets\SubscribeAsset;

$model = new Subscribe();
$model->user_id = Yii::$app->user->getId();
SubscribeAsset::register($this);
?>

<div id="slideout">
    <img src="/images/feedback.png" alt="Отправить отзыв">
    <div id="slideout_inner">
        <div class="info"></div>
        <div class="feedback-asistent">
            <img src="/images/asistent.jpg" width="60"/>
            <div class="feedback-asistent-text">
                <p class="no-asistent">
                    Задайте вопрос. Мы Вам обязательно ответим.
                </p>
            </div>
        </div>
<?= $this->render('@app/modules/admin/views/subscribe/_form', ['model' => $model]) ?>
    </div>
</div>

