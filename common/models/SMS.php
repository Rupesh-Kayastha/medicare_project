<?php

namespace common\models;

use Yii;


class SMS 
{
    
	public function generateOtp($contactno)
    {
		if(!STATIC_OTP){
			$otpcode = mt_rand(100000, 999999);

			$uname='krititech';
			$pwd='kriti@2705';
			$senderid='ARMEDI';
			
			$send_array = array();
			$msg = urlencode('Dear Customer, Your One Time Password for Account Login is : '.$otpcode);
		
			$smsurl="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=$uname&password=$pwd&msisdn=$contactno&sid=$senderid&msg=$msg&fl=0&gwid=2";
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,$smsurl);
			curl_setopt($curl, CURLOPT_TIMEOUT, 500);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $send_array);
			$result = curl_exec($curl);
			curl_close($curl);
			
			$finalresult=json_decode($result);
			if($finalresult->ErrorMessage=='Success')
			{
				 $response = $otpcode;
			}
			else
			{
			   $response = 0;
			}
		}
		else
			$response=123456;
		
		return $response;
		
	}

	public function sendSms($contactno,$password,$empid)
    {
		//if(!STATIC_OTP){
			//$otpcode = mt_rand(100000, 999999);

			$uname='krititech';
			$pwd='kriti@2705';
			$senderid='ARMEDI';
			
			$send_array = array();
			$msg = urlencode('Dear Customer, Your credential for Arundaya Medicare Pvt. Ltd. is UserId:'.$empid.' and Password :'.$password);
		
			$smsurl="http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=$uname&password=$pwd&msisdn=$contactno&sid=$senderid&msg=$msg&fl=0&gwid=2";
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,$smsurl);
			curl_setopt($curl, CURLOPT_TIMEOUT, 500);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $send_array);
			$result = curl_exec($curl);
			curl_close($curl);
			
			$finalresult=json_decode($result);
			//var_dump($finalresult);
			if($finalresult->ErrorMessage=='Success')
			{
				 $response = 1;
			}
			else
			{
			   $response = 0;
			}
		/*}
		else
			$response=123456;
		*/
		return $response;
		
	}
	
}
