<?php
namespace company\models;

use Yii;
use yii\base\Model;
use common\models\Employee;
use common\models\EmployeeRole;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $EmpId;
    public $CompanyId;
    public $Password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['CompanyId','EmpId', 'Password'], 'required'],
           // password is validated by validatePassword()
            ['Password', 'validatePassword'],
        ];
    }
	/**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            
            'EmpId' => Yii::t('app', 'Username'),
            'CompanyId' => Yii::t('app', 'Company Name'),
            'Password' => Yii::t('app', 'Password'),
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->EmployeeRoleId==EmployeeRole::NORMAL_EMPLOYEEE  || !$user->validatePassword($this->Password)) {
                $this->addError($attribute, 'Incorrect username or password.');
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
