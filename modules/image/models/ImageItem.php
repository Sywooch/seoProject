<?php

namespace app\modules\image\models;

use Yii;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
/**
 * This is the model class for table "{{%image_item}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $update_at
 *
 * @property Areas[] $areas
 * @property User $user
 */
class ImageItem extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'update_at'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('image', 'ID'),
            'user_id' => Yii::t('image', 'User ID'),
            'name' => Yii::t('image', 'Name'),
            'description' => Yii::t('image', 'Description'),
            'created_at' => Yii::t('image', 'Created At'),
            'update_at' => Yii::t('image', 'Update At'),
            'file' => Yii::t('image', 'Image File')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Areas::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\image\models\query\ImageItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\image\models\query\ImageItemQuery(get_called_class());
    }
    
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'update_at',
            ],
        ];
    }
    
    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->getId();
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        $file = UploadedFile::getInstance($this, 'file');
        if (!is_null($file)) {
            $filePath = $file->tempName;
            $this->attachImage($filePath, true);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}  