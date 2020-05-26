<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%TicketChat}}".
 *
 * @property int $TicketChatID
 * @property int $TicketID
 * @property int $FromID
 * @property int $ToID
 * @property string $Message
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 *
 * @property TicketOrder $ticket
 */
class TicketChat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%TicketChat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TicketID', 'FromID', 'ToID', 'Message'], 'required'],
            [['TicketID', 'FromID', 'ToID', 'IsDelete'], 'integer'],
            [['Message'], 'string'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['TicketID'], 'exist', 'skipOnError' => true, 'targetClass' => TicketOrder::className(), 'targetAttribute' => ['TicketID' => 'TicketID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'TicketChatID' => Yii::t('app', 'Ticket Chat I D'),
            'TicketID' => Yii::t('app', 'Ticket I D'),
            'FromID' => Yii::t('app', 'From I D'),
            'ToID' => Yii::t('app', 'To I D'),
            'Message' => Yii::t('app', 'Message'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(TicketOrder::className(), ['TicketID' => 'TicketID']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\TicketChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\TicketChatQuery(get_called_class());
    }
	
	public function getFrom()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'FromID']);
    }
    
    public function getTo()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'ToID']);
    }
    
    public function send($fromid,$message,$ticketid,$to=0)
    {
        $TicketChat=new TicketChat();
        $TicketChat->TicketID=$ticketid;
        $TicketChat->FromID=$fromid;
        $TicketChat->ToID=$to;
        $TicketChat->Message=$message;
        $TicketChat->OnDate=date('Y-m-d H:i:s');
        if($TicketChat->save())
        {
            return $TicketChat->TicketChatID;
        }
        else
        {
            return 0;
        }
    }
    
    public function fetchmessage($ticketid,$me,$lasttime)
    {
		$ind_time = new \DateTimeZone('Asia/Kolkata');
        $allmessage=TicketChat::find()->where(['TicketID'=>$ticketid,'IsDelete'=>0])
        ->andFilterWhere(['>','OnDate',$lasttime])
        ->orderBy(['OnDate'=>SORT_ASC])->all();
        $data=array();
        if($allmessage)
        {
            foreach($allmessage as $key=>$value)
            {
                $fromname=($value->from)?$value->from->EmployeeName:'Operator';
                $data[$key]['username']=($value->FromID==$me)?'You':$fromname;
                $data[$key]['message']=$value->Message;
                $data[$key]['Time']=(date('Y-m-d')==date('Y-m-d',strtotime($value->OnDate)))?date('H:i',strtotime($value->OnDate)):date('Y-m-d H:i',strtotime($value->OnDate));
				
				$datetime = new \DateTime($value->OnDate);
				$datetime->setTimezone($ind_time);
				
				$data[$key]['Time']=$datetime->format('H:i, jS M');
                $data[$key]['isme']=($value->FromID==$me)?0:1;
                $data[$key]['FullTime']=$value->OnDate;
            }
        }
        
        return $data;
    }
}
