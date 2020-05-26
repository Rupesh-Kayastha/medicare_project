<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "SystemRole".
 *
 * @property int $SystemRoleId
 * @property string $SystemRole
 * @property int $Status 0=>inactive,1=>active
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property User[] $users
 */
class SystemRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SystemRole';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['SystemRole'], 'required'],
            [['Status', 'IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['SystemRole'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'SystemRoleId' => Yii::t('backend', 'System Role ID'),
            'SystemRole' => Yii::t('backend', 'System Role'),
            'Status' => Yii::t('backend', '0=>inactive,1=>active'),
            'IsDelete' => Yii::t('backend', 'Is Delete'),
            'OnDate' => Yii::t('backend', 'On Date'),
            'UpdatedDate' => Yii::t('backend', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['SystemRoleId' => 'SystemRoleId']);
    }

    /**
     * {@inheritdoc}
     * @return \backend\models\active\SystemRoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\active\SystemRoleQuery(get_called_class());
    }
}
