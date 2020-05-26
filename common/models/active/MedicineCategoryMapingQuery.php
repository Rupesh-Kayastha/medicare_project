<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\MedicineCategoryMaping]].
 *
 * @see \common\models\MedicineCategoryMaping
 */
class MedicineCategoryMapingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\MedicineCategoryMaping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\MedicineCategoryMaping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
