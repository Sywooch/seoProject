<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%oil_price}}".
 *
 * @property integer $id
 * @property string $trade_date
 * @property string $code
 * @property double $close_price
 * @property double $open_price
 * @property double $low_price
 * @property double $high_price
 */
class Petroleum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oil_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trade_date'], 'safe'],
            [['close_price', 'open_price', 'low_price', 'high_price'], 'number'],
            [['code'], 'string', 'max' => 4],
            ['trade_date', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tradedate' => 'Tradedate',
            'code' => 'Code',
            'close_price' => 'Close Price',
            'open_price' => 'Open Price',
            'low_price' => 'Low Price',
            'high_price' => 'High Price',
        ];
    }
}
