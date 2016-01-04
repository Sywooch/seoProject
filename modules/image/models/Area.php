<?php

namespace app\modules\image\models;

use Yii;
use \nanson\postgis\behaviors\GeometryBehavior;
use \nanson\postgis\behaviors\StBufferBehavior;
/**
 * This is the model class for table "{{%areas}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $item_id
 * @property string $area
 *
 * @property ImageItem $item
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%areas}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id'], 'integer'],
            [['area'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImageItem::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('image', 'ID'),
            'title' => Yii::t('image', 'Title'),
            'item_id' => Yii::t('image', 'Item ID'),
            'area' => Yii::t('image', 'Area'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(ImageItem::className(), ['id' => 'item_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\image\models\query\AreaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\image\models\query\AreaQuery(get_called_class());
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => GeometryBehavior::className(),
                'attribute' => 'area',
                'type' => GeometryBehavior::GEOMETRY_POLYGON,
                 'skipAfterFindPostgis' => false,
            ],
        ];
    }
}
