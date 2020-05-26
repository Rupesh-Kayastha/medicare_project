<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "EmiPlans".
 *
 * @property int $EmiPlanId
 * @property int $EmiPlanCompanyId
 * @property string $EmiPlanName
 * @property int $EmiPlanPeriod
 * @property double $EmiPlanOrderMinAmount
 * @property int $EmiPlanStatus
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Company $emiPlanCompany
 */
class EmiPlans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EmiPlans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EmiPlanCompanyId', 'EmiPlanName', 'EmiPlanPeriod'], 'required'],
            [['EmiPlanCompanyId', 'EmiPlanPeriod', 'EmiPlanStatus', 'IsDelete'], 'integer'],
            [['EmiPlanOrderMinAmount'], 'number'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['EmiPlanName'], 'string', 'max' => 255],
            [['EmiPlanCompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['EmiPlanCompanyId' => 'CompanyId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EmiPlanId' => Yii::t('app', 'Emi Plan ID'),
            'EmiPlanCompanyId' => Yii::t('app', 'Emi Plan Company ID'),
            'EmiPlanName' => Yii::t('app', 'Emi Plan Name'),
            'EmiPlanPeriod' => Yii::t('app', 'Emi Plan Period In Months'),
            'EmiPlanOrderMinAmount' => Yii::t('app', 'Emi Plan Order Minimum Amount'),
            'EmiPlanStatus' => Yii::t('app', 'Emi Plan Status'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmiPlanCompany()
    {
        return $this->hasOne(Company::className(), ['CompanyId' => 'EmiPlanCompanyId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\EmiPlansQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\EmiPlansQuery(get_called_class());
    }
}
