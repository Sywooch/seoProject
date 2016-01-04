<?php

namespace app\modules\image\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\image\models\ImageItem]].
 *
 * @see \app\modules\image\models\ImageItem
 */
class ImageItemQuery extends \yii\db\ActiveQuery
{
    /* public function active()
      {
      $this->andWhere('[[status]]=1');
      return $this;
      } */

    /**
     * @inheritdoc
     * @return \app\modules\image\models\ImageItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\image\models\ImageItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

}
