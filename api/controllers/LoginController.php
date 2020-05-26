<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Employee;
use common\models\SMS;
use frontend\models\LoginForm;

class LoginController extends BaseController
{
    
    public function actionSendotp()
    {
        $results=array();
        $companyid=Yii::$app->request->post('companyid');
        $employeeid=Yii::$app->request->post('employeeid');
        $res=Employee::findByUsername($employeeid,$companyid);
        if($res)
        {
                if($res->EmployeeRoleId!=1)
                {
                    $SMS= new SMS();
                    $otpcode=$SMS->generateOtp($res->ContactNo);
                    if($otpcode)
                    {
                      $res->setOtpHash($otpcode);
                      $res->save();
                      $status=1;
                      $msg="Otp has been sent to your mobile no.";
                    }
                    else
                    {
                      $status=0;
                      $msg="Network Error in OTP Sending, Please Try Again Later.";
                    }
                }
                else
                {
                    $status=0;
                    $msg="Supper Admin are not allowed in mobile app.";
                }
                
        
        }
        else
        {
           $status=0;
           $msg="Please check your login details";
           $code='';
        }
        $results['status']=$status;
        $results['message']=$msg;
       $this->sendResponce($results);
    }
    
    
    
    public function actionUserlogin()
    {
        $results=array();
        $data=array();
        $companyid=Yii::$app->request->post('companyid');
        $employeeid=Yii::$app->request->post('employeeid');
        $otpcode=Yii::$app->request->post('otpcode');
        
        $res=Employee::findByUsername($employeeid,$companyid);
        if($res)
        {
         
            $validate=$res->validateOtpHash($otpcode);
            if($validate)
            {
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
                $msg="Successfully loggedin";
            }
            else
            {
                $status=0;
                $msg="Invalid Otp";
            }

        }
        else
        {
            $status=0;
            $msg="Please check your login details";
        }
       
        $results['status']=$status;
        $results['message']=$msg;
        $results['data']=$data;
        $this->sendResponce($results);
    }

    
}
