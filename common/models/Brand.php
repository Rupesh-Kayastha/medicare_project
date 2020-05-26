<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Brand".
 *
 * @property int $BrandId
 * @property string $Name
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Medicine[] $medicines
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BrandId' => Yii::t('app', 'Brand ID'),
            'Name' => Yii::t('app', 'Name'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicines()
    {
        return $this->hasMany(Medicine::className(), ['BrandId' => 'BrandId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Active\BrandQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\BrandQuery(get_called_class());
    }
}
