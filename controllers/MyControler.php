<?php
namespace app\controllers;
use yii\web\Controller;
use \yii\web\View;
/**
 * Description of MyControler
 *
 * @author Trubachev Denis
 */
class MyControler extends Controller
{
    /**
     * Registration goal before submit form
     * 
     * @param type $formId
     * @param type $goalName
     */
    public function addGoalForForm($formId, $goalName)
    {
        $counter = \Yii::$app->params['yandexCounter'];
          $js= <<<EOD
$("#$formId").on("beforeSubmit", function () {
    yaCounter$counter.reachGoal("$goalName");
});
 
EOD;
        $this->view->registerJs($js, View::POS_READY);
    }
}
