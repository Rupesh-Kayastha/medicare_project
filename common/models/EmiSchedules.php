<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "EmiSchedules".
 *
 * @property int $EmiSchedulesId
 * @property int $EmployeeId
 * @property int $CompanyId
 * @property string $OrderIdentifier
 * @property double $EmiAmount
 * @property string $EmiMonth
 * @property string $CreatedDate
 * @property string $UpdatedDate
 * @property int $EmiClearingStatus
 *
 * @property Employee $employee
 * @property Orders $orderIdentifier
 * @property Company $company
 */
class EmiSchedules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EmiSchedules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EmployeeId', 'CompanyId', 'OrderIdentifier', 'EmiAmount'], 'required'],
            [['EmployeeId', 'CompanyId', 'EmiClearingStatus'], 'integer'],
            [['EmiAmount'], 'number'],
            [['CreatedDate', 'UpdatedDate'], 'safe'],
            [['OrderIdentifier', 'EmiMonth'], 'string', 'max' => 100],
            [['EmployeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EmployeeId' => 'EmployeeId']],
            [['OrderIdentifier'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['OrderIdentifier' => 'OrderIdentifier']],
            [['CompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['CompanyId' => 'CompanyId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EmiSchedulesId' => Yii::t('app', 'Emi Schedules ID'),
            'EmployeeId' => Yii::t('app', 'Employee ID'),
            'CompanyId' => Yii::t('app', 'Company ID'),
            'OrderIdentifier' => Yii::t('app', 'Order Identifier'),
            'EmiAmount' => Yii::t('app', 'Emi Amount'),
            'EmiMonth' => Yii::t('app', 'Emi Month'),
            'CreatedDate' => Yii::t('app', 'Created Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
            'EmiClearingStatus' => Yii::t('app', 'Emi Clearing Status'),
			'EmpId'=>  Yii::t('app', 'Employee ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'EmployeeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderIdentifier()
    {
        return $this->hasOne(Orders::className(), ['OrderIdentifier' => 'OrderIdentifier']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['CompanyId' => 'CompanyId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\EmiSchedulesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\EmiSchedulesQuery(get_called_class());
    }
}
