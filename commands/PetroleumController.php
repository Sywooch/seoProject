<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\commands;
use app\models\Petroleum;
use yii\helpers\Json;

/**
 * Description of PetroleumController
 *
 * @author Trubachev Denis
 */
class PetroleumController extends \yii\console\Controller
{
    public $boardId = 'BR';

    public $urlTemplate = 'http://iss.moex.com/iss/history/engines/futures/markets/forts/boards/RFUD/securities/{boardId}.json';
    
    public static $monthCode = [
        1 => 'F',
        2 => 'G',
        3 => 'H',
        4 => 'J',
        5 => 'K',
        6 => 'M',
        7 => 'N',
        8 => 'Q',
        9 => 'U',
        10 => 'V',
        11 => 'X',
        12 => 'Z'
    ];

    public function actionTest()
    {
        $url = $this->generateUrl(['from' => '2016-12-01']);
        $cont = file_get_contents($url);
        $jsonData = Json::decode($cont);
        $columns = $jsonData['history']['columns'];
        $data = $jsonData['history']['data'];
        $mapAttributes = [
            'TRADEDATE' => 'trade_date',
            'SECID' => 'code',
            'OPEN' => 'open_price',
            'LOW' => 'low_price',
            'HIGH' => 'high_price',
            'CLOSE' => 'close_price'
        ];

        $model = new Petroleum();
        foreach ($mapAttributes as $apiAttribute => $attribute) {
            $apiAttributeIndex = array_search($apiAttribute, $columns);
            $model->setAttribute($attribute, $data[0][$apiAttributeIndex]);
        }

        if (!$model->save()) {
            var_dump($model->errors);
            var_dump($model->attributes);
        } else {
            var_dump($model->attributes);
        }

//        var_dump(json_decode($cont));
    }

    public function actionTest2()
    {
        var_dump(Petroleum::find()->all());
//        Petroleum::deleteAll();
    }

    /**
     * @param null $date
     * @throws Exception
     */
    public function actionPetroleumHistory($date = null)
    {
        if (!$date) {
            $date = '2012-02-01';
        }

        $now = time();
        while (strtotime($date) < $now) {
            $this->actionLoad($date);
            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }
    }

    /**
     * @param array $params
     * @return string
     */
    private function generateUrl($time, $params)
    {
        $url = $this->urlTemplate;
        $url = str_replace('{boardId}', $this->getBoardId($time), $url);
        return $url . (empty($params)? '': '?' . http_build_query($params));
    }

    /**
     * @param string $time
     * @return string
     */
    private function getBoardId($time = 'now')
    {
        $boardId = $this->boardId;
        $boardId .= self::$monthCode[date('n', strtotime($time . ' +1 month'))];
        $boardId .= date('Y', strtotime($time . ' +1 month')) % 10;
        return $boardId;
    }

    public function actionLoad($date = 'now')
    {
        if ($date != 'now') {
            $params = ['from' => $date, 'till' => $date];
        } else {
            $params = ['from' => date('Y-m-d'), 'till' => date('Y-m-d')];
        }
        $url = $this->generateUrl($date, $params);
        var_dump($url);
        $cont = file_get_contents($url);
        $jsonData = Json::decode($cont);
        $columns = $jsonData['history']['columns'];
        $data = $jsonData['history']['data'];

        if (empty($data)) {
            return;
        }

        $mapAttributes = [
            'TRADEDATE' => 'trade_date',
            'SECID' => 'code',
            'OPEN' => 'open_price',
            'LOW' => 'low_price',
            'HIGH' => 'high_price',
            'CLOSE' => 'close_price'
        ];

        $model = new Petroleum();
        foreach ($mapAttributes as $apiAttribute => $attribute) {
            $apiAttributeIndex = array_search($apiAttribute, $columns);
            $model->setAttribute($attribute, $data[0][$apiAttributeIndex]);
        }

        if (!$model->save()) {
            var_dump($model->errors);
            var_dump($model->attributes);
        }
    }

    public function actionShow()
    {
        $petroleums = Petroleum::find()->orderBy(['trade_date' => SORT_DESC])->all();
        foreach ($petroleums as $petroleum) {
            /* @var Petroleum $petroleum */
            echo $petroleum->code,'|', $petroleum->trade_date,'|', $petroleum->close_price, PHP_EOL;
        }
    }
}
