<?php

namespace common\models\active;

/**
 * This is the ActiveQuery class for [[\common\models\HomeBanner]].
 *
 * @see \common\models\HomeBanner
 */
class HomeBannerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\HomeBanner[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\HomeBanner|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
