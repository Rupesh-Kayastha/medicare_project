<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "Employee".
 *
 * @property int $EmployeeId
 * @property string $EmpId Username or Employee Id ( Organization )
 * @property int $CompanyId Company Name
 * @property string $Password
 * @property string $OtpHash
 * @property int $EmployeeRoleId Employee Role
 * @property string $EmployeeName
 * @property string $Dob Date Of Birth
 * @property double $CreditLimit 
 * @property double $CrediBalance 
 * @property double $CreditOnHold 
 * @property int $EmployeeActiveStatus Active Status
 * @property int $ContactNo
 * @property string $EmailId
 * @property string $BloodGroup
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 * @property int $created_at Identity Class 
 * @property int $updated_at Identity Class 
 *
 * @property Cart[] $carts 
 * @property EmployeeRole $employeeRole
 * @property Company $company
 * @property EmployeeAddress[] $employeeAddresses
 * @property Orders[] $orders 
 */
class Employee extends ActiveRecord implements IdentityInterface
{
	public $password_repeat;
	
	public $auth_key="medicare";
	
	const STATUS_NOT_DELETED = 0;
    const STATUS_ACTIVE = 1;
    
   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Employee';
    }
	
	/**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EmpId', 'CompanyId','EmployeeRoleId', 'EmployeeName', 'Dob', 'ContactNo', 'EmailId', 'BloodGroup'], 'required'],
            [['CompanyId', 'EmployeeRoleId', 'ContactNo','EmployeeActiveStatus','IsDelete'], 'integer'],
            [['Dob', 'OnDate', 'UpdatedDate'], 'safe'],
			[['Dob'], 'date', 'format' => 'php:Y-m-d'],
			[['CreditLimit', 'CreditBalance', 'CreditOnHold'], 'number'],
            [['EmpId'], 'string', 'max' => 50],
			['EmailId', 'email'],
            [['Password','OtpHash', 'EmailId'], 'string', 'max' => 255],
            [['EmployeeName'], 'string', 'max' => 100],
            [['BloodGroup'], 'string', 'max' => 20],
            ['EmpId',  'unique', 'targetAttribute' => ['EmpId','CompanyId'],'message'=>" {value} has already been used"],
			[['EmployeeRoleId'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeRole::className(), 'targetAttribute' => ['EmployeeRoleId' => 'EmployeeRoleId']],
            [['CompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['CompanyId' => 'CompanyId']],
            [
			'Password',
			'required',
			'on'=>'create',
			'when' => function ($model) { 
				  return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE);
			  }, 
			  'whenClient' => "function (attribute, value) { 
				return $('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE."; 
			  }"
			],
			[
			'password_repeat',
			'required',
			'on'=>'create',
			'when' => function ($model) { 
				   return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE || $model->Password!="");
			  },
			  'whenClient' => "function (attribute, value) { 
				return ($('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE." || $('#employee-password').val()); 
			  }"
			  
			],
			[
			'Password',
			'required',
			'on'=>'updateCompany',
			'when' => function ($model) { 
				  return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE && $model->Password!="" );
			  }, 
			  'whenClient' => "function (attribute, value) { 
				return ($('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE." && $('#employee-password').val());
			  }"
			],
			[
			'password_repeat',
			'required',
			'on'=>'updateCompany',
			'when' => function ($model) { 
				  return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE);
			  },
			  'whenClient' => "function (attribute, value) { 
				return ($('#employee-password').val()); 
			  }"
			  
			],
			[
			'Password',
			'required',
			'on'=>'updateEmployee',
			'when' => function ($model) { 
				  return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE && $model->Password!="" );
			  }, 
			  'whenClient' => "function (attribute, value) { 
				
							if($('#old_roleId').val()== ".EmployeeRole::NORMAL_EMPLOYEEE." ) {
							  
							  return ($('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE." || $('#employee-password').val());
							}
							else {
								return ($('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE." &&  $('#employee-password').val());
							}				
				
			  }"
			],
			[
			'password_repeat',
			'required',
			'on'=>'updateEmployee',
			'when' => function ($model) { 
				  return ($model->EmployeeRoleId !=EmployeeRole::NORMAL_EMPLOYEEE && $model->Password!="");
			  },
			  'whenClient' => "function (attribute, value) { 
			  
							if($('#old_roleId').val()== ".EmployeeRole::NORMAL_EMPLOYEEE." ) {
							  
							  return ($('#employee-employeeroleid').val() != ".EmployeeRole::NORMAL_EMPLOYEEE." || $('#employee-password').val());
							}
							else {
								return ($('#employee-password').val());
							}
				 
			  }"
			  
			],
			
			['password_repeat', 'compare', 'compareAttribute'=>'Password' , 'message'=>"Passwords don't match" ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EmployeeId' => Yii::t('app', 'Employee ID'),
            'EmpId' => Yii::t('app', 'Username or Employee Id ( Organization )'),
            'CompanyId' => Yii::t('app', 'Company Name'),
            'Password' => Yii::t('app', 'Password'),
            'OtpHash' => Yii::t('app', 'Otp Hash'),
            'EmployeeRoleId' => Yii::t('app', 'Employee Role'),
            'EmployeeName' => Yii::t('app', 'Employee Name'),
            'Dob' => Yii::t('app', 'Date Of Birth'),
            'ContactNo' => Yii::t('app', 'Contact No'),
            'EmailId' => Yii::t('app', 'Email ID'),
            'BloodGroup' => Yii::t('app', 'Blood Group'),
			'CreditLimit' => Yii::t('app', 'Credit Limit'), 
			'CreditBalance' => Yii::t('app', 'Credit Balance'), 
			'CreditOnHold' => Yii::t('app', 'Credit On Hold'), 
            'EmployeeActiveStatus' => Yii::t('app', 'Active Status'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
			'password_repeat' => Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeRole()
    {
        return $this->hasOne(EmployeeRole::className(), ['EmployeeRoleId' => 'EmployeeRoleId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['CompanyId' => 'CompanyId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeAddresses()
    {
        return $this->hasMany(EmployeeAddress::className(), ['EmployeeId' => 'EmployeeId']);
    }
	
	/** 
	 * @return \yii\db\ActiveQuery 
	 */ 
	public function getCarts() 
	{ 
	   return $this->hasMany(Cart::className(), ['EmployeeId' => 'EmployeeId']); 
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders()
	{
	   return $this->hasMany(Orders::className(), ['EmployeeId' => 'EmployeeId']);
	}
	
    /**
     * {@inheritdoc}
     * @return \common\models\active\EmployeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\EmployeeQuery(get_called_class());
    }
	
	/**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['EmployeeId' => $id, 'EmployeeActiveStatus'=> self::STATUS_ACTIVE,'IsDelete' =>self::STATUS_NOT_DELETED]);
    }
	
	/**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
	
	/**
     * Finds user by Employee Id
     *
     * @param string $username
     * @return static|null
     */
	public static function findByUsername($EmpId,$company)
	{
        return static::findOne(['EmpId' => $EmpId,'CompanyId'=>$company,'EmployeeActiveStatus'=> self::STATUS_ACTIVE,'IsDelete' =>self::STATUS_NOT_DELETED]);
    }
	
	/**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
	
	/**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return "";
    }
	
	/**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
	
	/**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->Password);
    }
	
	/**
     * Validates OtpHash
     *
     * @param string $OtpHash Otp to validate
     * @return bool if OtpHash provided is valid for current user
     */
	public function validateOtpHash($Otp)
    {
        return Yii::$app->security->validatePassword($Otp, $this->OtpHash);
    }
		
	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
		$this->Password 		= Yii::$app->security->generatePasswordHash($password);
		$this->password_repeat	= $this->Password;
    }
	
	/**
     * Generates OtpHash hash from Otp and sets it to the model
     *
     * @param string $Otp
     */
    public function setOtpHash($OtpHash)
    {
		$this->OtpHash 		= Yii::$app->security->generatePasswordHash($OtpHash);		
    }
}
