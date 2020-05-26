<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\EmployeeAddress;
use common\models\Employee;
use common\models\Orders;
use common\models\OrderItems;
use common\models\EmiSchedules;

class EmployeeController extends BaseController
{
 
    public function actionGetaddress()
    {
        $data=array();
        $results=array();
        $employeeid=Yii::$app->request->post('Employeeid');
        $employeeaddressid=Yii::$app->request->post('Employeeaddressid');
        if($employeeaddressid!='')
        {
            $resaddrs=EmployeeAddress::find()->where(['EmployeeId'=>$employeeid,'EmployeeAddressId'=>$employeeaddressid,'IsDelete'=>0])->orderBy(['OnDate'=>SORT_DESC])->all();
        }
        else
        {
            $resaddrs=EmployeeAddress::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0])->orderBy(['OnDate'=>SORT_DESC])->all();
        }
        
        if(count($resaddrs)>0)
        {
            foreach($resaddrs as $key=>$value)
            {
                $data[$key]['EmployeeAddressId']=$value->EmployeeAddressId;
                $data[$key]['AddressLine1']=urldecode($value->AddressLine1);
                $data[$key]['AddressLine2']=urldecode($value->AddressLine2);
                $data[$key]['LandMark']=urldecode($value->LandMark);
                $data[$key]['State']=$value->State;
                $data[$key]['City']=$value->City;
                $data[$key]['Zipcode']=$value->Zipcode;
                $data[$key]['ContactNo']=$value->ContactNo;
                $data[$key]['OnDate']=$value->OnDate;
            }
            
            $status=1;
            $msg='Success';
        }
        else
        {
            $status=0;
            $msg='Fail';
        }
        
        $results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        
        $this->sendResponce($results);
    }
    
    public function actionAddressadd()
    {
        
        $results=array();
        $employeeid=Yii::$app->request->post('Employeeid');
        $AddressLine1=Yii::$app->request->post('AddressLine1');
        $AddressLine2=Yii::$app->request->post('AddressLine2');
        $LandMark=Yii::$app->request->post('LandMark');
        $State=Yii::$app->request->post('State');
        $City=Yii::$app->request->post('City');
        $Zipcode=Yii::$app->request->post('Zipcode');
        $ContactNo=Yii::$app->request->post('ContactNo');
        $date=date('Y-m-d H:i:s');
        
        $countemp=Employee::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0,'EmployeeActiveStatus'=>1])->count();
        if($countemp>0)
        {
            $model=new EmployeeAddress();
            $model->EmployeeId=$employeeid;
            $model->AddressLine1=$AddressLine1;
            $model->AddressLine2=$AddressLine2;
            $model->LandMark=$LandMark;
            $model->State=$State;
            $model->City=$City;
            $model->Zipcode=$Zipcode;
            $model->ContactNo=$ContactNo;
            $model->OnDate=$date;
            if($model->save())
            {
                $status=1;
                $msg='Successfully Added.';
            }
            else
            {
                $status=0;
                $msg='Please try again.';
            }
            
        }
        else
        {
            $status=0;
            $msg='Invalid Employee.';
        }
        $results['status']=$status;
        $results['message']=$msg;
        
        $this->sendResponce($results);

    }
    
    
    public function actionAddressupdate()
    {
        
        $results=array();
        $employeeid=Yii::$app->request->post('Employeeid');
        $employeeaddressid=Yii::$app->request->post('Employeeaddressid');
        $AddressLine1=Yii::$app->request->post('AddressLine1');
        $AddressLine2=Yii::$app->request->post('AddressLine2');
        $LandMark=Yii::$app->request->post('LandMark');
        $State=Yii::$app->request->post('State');
        $City=Yii::$app->request->post('City');
        $Zipcode=Yii::$app->request->post('Zipcode');
        $ContactNo=Yii::$app->request->post('ContactNo');
        $res=EmployeeAddress::find()->where(['EmployeeId'=>$employeeid,'EmployeeAddressId'=>$employeeaddressid,'IsDelete'=>0])->one();
        if(count($res)>0)
        {
            $res->AddressLine1=urlencode($AddressLine1);
            $res->AddressLine2=urlencode($AddressLine2);
            $res->LandMark=urlencode($LandMark);
            $res->State=$State;
            $res->City=$City;
            $res->Zipcode=$Zipcode;
            $res->ContactNo=$ContactNo;
            if($res->save())
            {
                $status=1;
                $msg='Successfully Updated.';
            }
            else
            {
                $status=0;
                $msg='Fail';
            }
            
        }
        else
        {
            $status=0;
            $msg='Fail';
        }
        
        $results['status']=$status;
        $results['message']=$msg;
        
        $this->sendResponce($results);
        
    }
    
	public function actionGetemployeedetails(){
		$results=array();
        $data=array();
		$employeeid=Yii::$app->request->post('employeeid');
		$res=Employee::findIdentity($employeeid);
		
		if($res){
			
				$data['employeeid']=$res->EmployeeId;
                $data['employeeroleid']=$res->EmployeeRoleId;
                $data['companyid']=$res->CompanyId;
                $data['empid']=$res->EmpId;
                $data['employename']=$res->EmployeeName;
                $data['companyname']=$res->company->Name;
                $data['contactno']=$res->ContactNo;
                $data['emailid']=$res->EmailId;
                $data['creditlimit']=$res->CreditLimit;
                $data['creditbalance']=$res->CreditBalance;
				
				$status=1;
                $msg="Data Fetched";
		}
		else{
			$status=0;
            $msg="No Data Found";
		}
		$results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        $this->sendResponce($results);
	}
	
	public function actionGetemi(){
		$results=array();
        $data=array();
		
		$employeeid=Yii::$app->request->post('employeeid');
		
		$res=EmiSchedules::find()->where(['EmployeeId'=>$employeeid,])->orderBy(['OrderIdentifier'=>SORT_DESC,'EmiSchedulesId'=>SORT_ASC ])->all();
		if($res){
			
			
			$data['EMI']['Current']['Schedules']=array();
			
			
			
			$data['EMI']['Current']['Total']=0;
			
			
			$data['Orders']=array();
			
			$current_Month=date("M Y",time());
			$orderData=array();
			foreach($res as $emi){
				
				
				
				$orderData[$emi->OrderIdentifier]['OrderId']=$emi->orderIdentifier->OrderId;
				$orderData[$emi->OrderIdentifier]['OrderIdentifier']=$emi->OrderIdentifier;
				$orderData[$emi->OrderIdentifier]['EMI'][]=array('Month'=>$emi->EmiMonth,'Amount'=>$emi->EmiAmount);
				
				
			
				if(strtotime($current_Month)==strtotime($emi->EmiMonth)){
					
					$data['EMI']['Current']['Schedules'][]=array('OrderId'=>$emi->orderIdentifier->OrderId,'OrderIdentifier'=>$emi->OrderIdentifier,'Month'=>$emi->EmiMonth,'Amount'=>$emi->EmiAmount);
					
					$data['EMI']['Current']['Total']=$data['EMI']['Current']['Total']+$emi->EmiAmount;
					$data['EMI']['Current']['Month']=$current_Month;
				}
			}
			foreach($orderData as $Order)
			$data['Orders'][]=$Order;
			$status=1;
			$msg="Data Fetched";
		}
		else{
			$status=0;
            $msg="No Data Found";			
		}
		$results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        $this->sendResponce($results);
	}
   
}
?>