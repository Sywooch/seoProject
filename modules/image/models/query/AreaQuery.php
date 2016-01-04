<?php

namespace app\modules\image\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\image\models\Area]].
 *
 * @see \app\modules\image\models\Area
 */
class AreaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\image\models\Area[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\image\models\Area|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
