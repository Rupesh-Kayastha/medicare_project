<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%ContactForm}}".
 *
 * @property int $Id
 * @property string $Name
 * @property string $Mob
 * @property string $Email
 * @property string $Subject
 * @property string $Message
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 */
class ContactForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ContactForm}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'Mob', 'Email', 'Subject'], 'required'],
            [['IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Name'], 'string', 'max' => 100],
            [['Mob'], 'string', 'max' => 20],
            [['Email'], 'string', 'max' => 30],
            [['Subject'], 'string', 'max' => 50],
            [['Message'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'Mob' => Yii::t('app', 'Mob'),
            'Email' => Yii::t('app', 'Email'),
            'Subject' => Yii::t('app', 'Subject'),
            'Message' => Yii::t('app', 'Message'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\ContactFormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\ContactFormQuery(get_called_class());
    }
     public function sendEmail($to,$subject,$html,$from, $cc)
    {

        $sendGrid = Yii::$app->sendGrid;
        $message = $sendGrid->compose();
        $message->setFrom($from)
        ->setTo($to)
        ->setTo($cc)
        ->setSubject($subject)
        ->setHtmlBody($html)
        ->send($sendGrid);
    }
}
