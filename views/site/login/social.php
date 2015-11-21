<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
if (Yii::$app->getSession()->hasFlash('error')) {
    echo '<div class="alert alert-danger">' . Yii::$app->getSession()->getFlash('error') . '</div>';
}
?>
    <p class="lead">Add social accaunt:</p>
    <div class="social-list">
        
    </div>
<?php echo \nodge\eauth\Widget::widget(['action' => 'site/login']); ?>
</div>
