<?php

namespace frontend\models\query;

use Yii;

use creocoder\nestedsets\NestedSetsQueryBehavior;


class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}
?>