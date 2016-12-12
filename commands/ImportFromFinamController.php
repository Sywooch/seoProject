<?php
namespace app\commands;


use app\models\Petroleum;
use yii\console\Controller;

class ImportFromFinamController extends Controller
{
    private $_url = 'http://export.finam.ru/data.csv';
//http://export.finam.ru/data.csv?market=24&em=19473&code=ICE.BRN&apply=0&df=6&mf=11&yf=2016&from=06.12.2016&dt=8&mt=11&yt=2016&to=08.12.2016&p=7&f=data&e=.csv&cn=ICE.BRN&dtf=1&tmf=1&MSOR=1&mstime=on&mstimever=1&sep=3&sep2=2&datf=1
//http://export.finam.ru/data.csv?market=24&em=19473&code=ICE.BRN&apply=0&df=6&mf=12&yf=2016&from=06.12.2016&dt=8&mt=12&yt=2016&to=08.12.2016&p=7&e=.csv&f=data&cn=ICE.BRN&dtf=1&tmf=1&MSOR=1&mstime=on&mstimever=1&sep=3&sep2=2&daft=1
    private function generateUrl($dateFrom, $dateTo)
    {
        $params = [
            'market' => 24,
            'em' => 19473,
            'code' => 'ICE.BRN',
            'apply' => 0,
            'df' => idate('d', $dateFrom),
            'mf' => idate('m', $dateFrom) - 1,
            'yf' => idate('Y', $dateFrom),
            'from' => date('d.m.Y', $dateFrom),
            'dt' => idate('d', $dateTo),
            'mt' => idate('m', $dateTo) - 1,
            'yt' => idate('Y', $dateTo),
            'to' => date('d.m.Y', $dateTo),
            'p' => 8,//8 - день, 7 - час
            'e' => '.csv',
            'f' => 'data',
            'cn' => 'ICE.BRN',
            'dtf' => 4,
            'tmf' => 1,
            'MSOR' => 1,
            'mstime' => 'on',
            'mstimever' => 1,
            'sep' => 3,
            'sep2' => 2,
            'daft' => 1
        ];

        return $this->_url . '?' . http_build_query($params);
    }

    public function actionIndex()
    {
        Petroleum::deleteAll([]);
        $url = $this->generateUrl(strtotime('2016-12-09'), strtotime('2016-12-11'));
        var_dump($url);
        $data = file_get_contents($url);
        $rows = explode("\n",$data);
        foreach($rows as $row) {
            $attributes = str_getcsv($row, ';');
            if (empty($attributes) || count($attributes) < 6) {
                continue;
            }
            var_dump($attributes);
            $model = new Petroleum();
            $model->trade_date = date('d-m-Y', strtotime(str_replace('/', '-', $attributes[0])));
            $model->code = 'BRN';
            $model->open_price = $attributes[2];
            $model->low_price = $attributes[3];
            $model->high_price = $attributes[4];
            $model->close_price = $attributes[5];
            if (!$model->save()) {
                var_dump($model->errors, $model->attributes);
            } else {
                echo 'save';
            }
        }
    }
}