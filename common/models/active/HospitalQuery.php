<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\Hospital]].
 *
 * @see \common\models\Hospital
 */
class HospitalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Hospital[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Hospital|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
