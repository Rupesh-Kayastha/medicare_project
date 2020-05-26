<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%WebPrescription}}".
 *
 * @property int $Id
 * @property string $Prescription
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 */
class WebPrescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%WebPrescription}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Prescription','UserName','UserContact','UserMail','UserAddress'], 'required'],
            [['UserContact'],'number', 'min' => 10],
            ['UserMail','email'],
            [['IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['Prescription'], 'string', 'max' => 200],
            [['UserMessage'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'EmployeeId' => Yii::t('app', 'Employee ID'),
            'UserName' => Yii::t('app', 'Name'),
            'UserContact' => Yii::t('app', 'Contact No'),
            'UserMail' => Yii::t('app', 'Mail ID'),
            'UserMessage' => Yii::t('app', 'Message'),
            'UserAddress' => Yii::t('app', 'Address'),
            'Prescription' => Yii::t('app', 'Upload Prescription'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\WebPrescriptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\WebPrescriptionQuery(get_called_class());
    }
     public function sendEmail($to,$subject,$html,$from,$cc)
    {
       /* return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();*/
         /*  if (mail($to,$subject,$message,$from)) {
               return true;
            } else{
                return false;
            }*/
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
