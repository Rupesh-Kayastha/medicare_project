<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Employee;
use common\models\EmployeeRole;
use common\models\SMS;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $EmpId;
    public $CompanyId;
    public $OtpHash;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // EmpId and OtpHash are both required
            [['CompanyId','EmpId'], 'required'],
			[
			'OtpHash',
			'required',
			'when' => function ($model) { 
				  return ($model->OtpHash !="");
			  },
			'whenClient' => "function (attribute, value) {
				return $(attribute.container).is(':visible');
			}",
			],
           // OtpHash is validated by validateOtpHash()
            ['OtpHash', 'validateOtpHash'],
        ];
    }
	/**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            
            'EmpId' => Yii::t('app', 'Employee Id'),
            'CompanyId' => Yii::t('app', 'Company Name'),
            'OtpHash' => Yii::t('app', 'Otp'),
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOtpHash($attribute, $params)
    {
        if (!$this->hasErrors()) {
			
            $user = $this->getUser();
			
            if (!$user || $user->EmployeeRoleId==EmployeeRole::SUPPER_ADMIN  || !$user->validateOtpHash($this->OtpHash)) {
                $this->addError($attribute, 'Incorrect OTP.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
		
        if ($this->validate()) {
           $loggedin=Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);

                if($loggedin){
                    Yii::$app->session->setFlash('success', "Welcome ".Yii::$app->user->identity->EmployeeName);
                    return $loggedin;
                }
                else
                    return false;

        }
        
        return false;
    }
	
	public function generateOtp()
    {
		$data=array();
		$data['status']=false;
		$data['message']="Employee Id could not found in the given company.";
		if ($this->validate() && $user = $this->getUser()) {
			
			
			if($user->company->ActiveStatus && !$user->company->IsDelete){
				
				if($user->EmployeeRoleId!=EmployeeRole::SUPPER_ADMIN){
					
					
					
					$SMS= new SMS();
					$otpcode=$SMS->generateOtp($user->ContactNo);
					
					if($otpcode){
						$user->setOtpHash($otpcode);
						$user->save();
						$data['status']=true;
						$data['message']="Otp has been sent to your mobile no.";
					}
					else{
						$data['status']=false;
						$data['message']="Network Error in OTP Sending, Please Try Again Later.";
					}
				}
				else{
					$data['status']=false;
					$data['message']="Only Employee Can Login.";	
				}
				
			}
			else{
					$data['status']=false;
					$data['message']="Your company is currently not active in the portal.";	
			}
		}
		
		return $data;
		
	
	}

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Employee::findByUsername($this->EmpId,$this->CompanyId);
        }

        return $this->_user;
    }
}
