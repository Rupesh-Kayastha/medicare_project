<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\Medicine]].
 *
 * @see \common\models\Medicine
 */
class MedicineQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Medicine[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Medicine|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
