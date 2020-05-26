<?php

namespace common\models;

use Yii;
use yii\httpclient\Client;


class ShipRocket 
{
    private $token;
    
    private $user;
    private $pass;
    
    private $client;
    private $base_url;
    
    function __construct()
    {
       $this->user=SHIPROCKET_USER;
       $this->pass=SHIPROCKET_PASSWORD;
       $this->base_url="https://apiv2.shiprocket.in/v1/external/";
       $this->client= new Client([
                            'baseUrl' => $this->base_url,
                            'requestConfig' => [
                                'format' => Client::FORMAT_JSON
                            ],
                            'responseConfig' => [
                                'format' => Client::FORMAT_JSON
                            ],
                        ]);
       
       
    }
    
    public function Connect(){
        $response=false;
        
        $response=$this->client->createRequest()->setMethod('POST')->setUrl('auth/login')->setData(['email' => $this->user, 'password' => $this->pass])->send();
        if($response->isOk){
            
            $this->token=$response->data['token'];
            return $response->isOk;
        }       
        return false;
    }
    
    
    public function PlaceOrder($data){
        
        $response=$this->client->createRequest()->addHeaders(['Authorization' => 'Bearer '.$this->token])->setMethod('POST')->setUrl('orders/create/adhoc')->setData($data)->send();
        if($response->isOk){            
            return true;            
        }
        var_dump($response->content);
        
    }
   
    
    
    public function PrepareOrder($orderIdentifire){
        
       
        $Order = Orders::find()->where(['OrderIdentifier'=>$orderIdentifire,'OrderStatus'=>1])->one();
       
        if($Order){
            
            $items=array();
            
            foreach($Order->orderitems as $item){
                $Oi=array(
                      "name"=> $item->OrderItemName,
                      "sku"=>sprintf("AMD_%'.09d", (int)$item->OrderMedicineID),
                      "units"=>$item->OrderItemQty,
                      "selling_price"=> $item->OrderItemPrice,
                     );
                    $items[]=$Oi;
            }
                                
           $data=array(
              "order_id"=>$Order->OrderIdentifier,
              "order_date" => $Order->CreatedDate,
              "pickup_location"=>"Primary",
              "billing_customer_name"=> $Order->employee->EmployeeName,
              "billing_last_name"=>"",
              "billing_address"=>$Order->employeeaddress->AddressLine1,
              "billing_address_2"=> $Order->employeeaddress->AddressLine2,
              "billing_city"=>$Order->employeeaddress->City,
              "billing_pincode"=> $Order->employeeaddress->Zipcode,
              "billing_state"=> $Order->employeeaddress->State,
              "billing_email"=> $Order->employee->EmailId,
              "billing_phone"=> $Order->employeeaddress->ContactNo,
              "billing_country"=>"India",
              "shipping_is_billing"=> 1,
              "order_items"=>$items,
              "payment_method"=>$Order->PaymentType==1?"COD":"Prepaid",
              "shipping_charges"=>0,
              "giftwrap_charges"=>0 ,
              "transaction_charges"=>0,
              "total_discount"=>0,
              "sub_total"=> $Order->OrderTotalPrice,
              "length"=> 0.5,
              "breadth"=>0.5,
              "height"=> 0.5,
              "weight"=> 0.001
            );         
            
            return $data;
        }
        return false;
    }
    
    
    
}