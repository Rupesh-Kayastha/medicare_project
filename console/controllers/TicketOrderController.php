<?php
namespace console\controllers;


use yii;
use yii\console\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use common\models\TicketOrder;
use common\models\TicketChat;
use common\models\Cart;
use common\models\CartItem;
use common\models\TicketCartMap;
use common\models\EmployeeAddress;
use common\models\EmiPlans;
use backend\models\User;


class TicketOrderController extends Controller
{
	public function actionTokenautoclose(){
		$allticketorder = TicketOrder::find()->where(['TicketStatus'=>1,'IsDelete'=>0])->all();
		
		foreach($allticketorder as $key => $value){
			
			$ticketChats = $value->lastTicketChats;
			if($ticketChats){
				
				$currentdatetime = date("Y-m-d h:i:s");
				$lastmessagetime = date("Y-m-d h:i:s",strtotime($ticketChats->OnDate));
				$second_difference = strtotime($currentdatetime) - strtotime($lastmessagetime);
				//600 -> 10 Minutes
				if($second_difference > 600){
					$ticketDetails = TicketOrder::find()->where(['Token'=>$value->Token,'IsDelete'=>0])->one();
					if($ticketDetails && $ticketDetails->Operator){
						$ticketDetails->TicketStatus = 2;
						if($ticketDetails->save()){
						  echo 1;
						}else{
						  echo 0;
							
						}
					}
				}
			}
		}
		
		
	}
}

?>