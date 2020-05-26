<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\TicketOrder;
use common\models\TicketChat;

class ChatController extends BaseController
{
   
    public function actionOrderbysupport()
    {
        
        $tickettoken=Yii::$app->request->post('Tickettoken');
        $employeeid=Yii::$app->request->post('EmployeeId');
        
        $results=array();
        $data=array();
        
        $TicketOrder=new TicketOrder();
        if($tickettoken=='')
        {
                    $isavail=TicketOrder::find()->where(['UserID'=>$employeeid,'TicketStatus'=>1])->one();
                    if(!$isavail)
                    {
                        $count=$TicketOrder::find()->count()+1;
                        $Token='AMD'.str_pad($count,9,"0",STR_PAD_LEFT);
                        
                        $TicketOrder->UserID=$employeeid;
                        $TicketOrder->Token=$Token;
                        if($TicketOrder->save())
                        {
                            $status=1;
                            $msg='New token created.';
                            $data['Tickettoken']=$Token;
                        }
                        else
                        { 
                            $status=0;
                            $msg='Token is not created.';
                            $data['Tickettoken']='';
                        }
                    }
                    else
                    {
                        $status=1;
                        $msg='Already Created.';
                        $data['Tickettoken']=$isavail->Token;
                    }
           
        }
        else
        {
            $status=1;
            $msg='Already Created.';
            $data['Tickettoken']=$tickettoken;
        }
        
        $results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        $this->sendResponce($results);
    }
    
    public function actionFetchmessage()
    {
        $TicketToken=Yii::$app->request->post('Tickettoken');
        $lasttime=Yii::$app->request->post('lasttime');
        $employeeid=Yii::$app->request->post('EmployeeId');
        
        $results=array();
        $data=array();
        
        $TicketOrder=new TicketOrder();
        $TicketChat=new TicketChat();
        
		if($TicketToken!='')
        {
            $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
            if(count($Ticket)>0)
            {
                    $to=$employeeid;
                    $ticketid=$Ticket->TicketID;
                    if($Ticket->TicketStatus==2)
                    {
                        $status=0;
                        $msg="Token is closed.";
                    }
                    else
                    {
                        
                        $allmessage=$TicketChat->fetchmessage($ticketid,$to,$lasttime);
                        if(!empty($allmessage))
                        {
                           $status=1;
                           $msg="Record Found.";
                           $data['allmessage']=$allmessage;
                        }
                        else
                        {
                           $status=2;
                           $msg="No Record Found.";
                        }
                         
                    }
            }
            else
            {
                $status=0;
                $msg="Invalid Token.";
            }
           
		}
		else
        {
            $status=0;
            $msg="Invalid Token.";
		}
        
        $results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        $this->sendResponce($results);
    }
    
    public function actionMessagesend()
    {
        $message=Yii::$app->request->post('message');
        $TicketToken=Yii::$app->request->post('Tickettoken');
        $employeeid=Yii::$app->request->post('EmployeeId');
        
        $results=array();
       
        $TicketOrder=new TicketOrder();
        $TicketChat=new TicketChat();
        
        $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
        if(count($Ticket)>0)
        {
            $to=$Ticket->Operator;
            $ticketid=$Ticket->TicketID;
            $result=$TicketChat->send($employeeid,$message,$ticketid,$to);
            if($result==0)
            {
                $status=0;
                $msg="Message is not added.";
            }
            else
            {
                $status=$result;
                $msg="Successfully Added.";
            }
            
        }
        else
        {
            $status=0;
            $msg="Invalid Token.";
        }
        
        $results['status']=$status;
        $results['message']=$msg;
        $this->sendResponce($results);
    }
    public function actionPrescriptionupload(){
		
		file_put_contents(Yii::getAlias('@root')."/log.txt",print_r($_FILES["file"],true));
		
		
		if(!$_FILES["file"]['error']){
			$file=urldecode($_FILES["file"]['name']);
			$file=preg_replace("/[^a-zA-Z0-9\s]/", "", $file);
			$target_file=uniqid().$file.".jpg";
			move_uploaded_file($_FILES["file"]["tmp_name"], Yii::getAlias('@storage').'/prescriptions/'.$target_file);
			
			
			
			$TicketChatID=Yii::$app->request->post("TicketChatID");
			
			
			$TicketChat=TicketChat::find()->where(['TicketChatID'=>$TicketChatID])->one();
			$TicketChat->Message='<div class="img_c"><img class="lazy lazy-fade-in" src="'.Yii::getAlias('@storageUrl').'/prescriptions/'.$target_file.'" data-src="'.Yii::getAlias('@storageUrl').'/prescriptions/'.$target_file.'" onclick="viewPresscription(this)" /></div>';
			$TicketChat->save();
			
			
			
			$results['status']=1;
			$results['message']="Prescription Upload";
		}
		else{
			
			$results['status']=0;
			$results['message']=$_FILES["file"]['error'];
		}
		
		
        $this->sendResponce($results);
		
	}
    
}
