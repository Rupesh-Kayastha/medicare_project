<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "EmployeeRole".
 *
 * @property int $EmployeeRoleId
 * @property string $EmployeeRole
 * @property int $Status
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property Employee[] $employees
 */
class EmployeeRole extends \yii\db\ActiveRecord
{
	const SUPPER_ADMIN=1;
	const ADMIN=2;
	const MANAGER=3;
	const NORMAL_EMPLOYEEE=4;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'EmployeeRole';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EmployeeRole'], 'required'],
            [['Status', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['EmployeeRole'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EmployeeRoleId' => Yii::t('app', 'Employee Role ID'),
            'EmployeeRole' => Yii::t('app', 'Employee Role'),
            'Status' => Yii::t('app', 'Status'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['EmployeeRoleId' => 'EmployeeRoleId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\EmployeeRoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\EmployeeRoleQuery(get_called_class());
    }
}
