<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Hospital}}".
 *
 * @property int $HospitalId
 * @property string $HospitalTypes
 * @property string $Hospital Name
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 */
class Hospital extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Hospital}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['HospitalTypes', 'HospitalName', 'Phone_Number'], 'required'],
            [['IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['HospitalTypes'], 'string', 'max' => 50],
            [['HospitalName'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'HospitalId' => Yii::t('app', 'Hospital ID'),
            'HospitalTypes' => Yii::t('app', 'Hospital Types'),
            'HospitalName' => Yii::t('app', 'HospitalName'),
            'Phone_Number' => Yii::t('app', 'Phone Number'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\HospitalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\HospitalQuery(get_called_class());
    }
}
