<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use common\models\ContactForm;
use frontend\models\MenuCategory;
use common\models\TicketOrder;
use common\models\TicketChat;
use common\models\Hospital;
use common\models\Cart;
use common\models\Orders;
use common\models\OrderItems;
use common\models\CartItem;
use common\models\HomeBanner;
use common\models\TicketCartMap;
use common\models\EmployeeAddress;
use common\models\Employee;
use common\models\EmiPlans;
use common\models\EmiSchedules;
use backend\models\User;
use common\models\SMS;
use common\models\WebPrescription;
use yii\web\UploadedFile;
use common\models\ShipRocket;
use common\models\Company;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use api\models\MedicineCategory;
use api\models\MedicineCategoryMaping;
use common\models\Medicine;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','generateotp'],
                'rules' => [
                    [
                        'actions' => ['signup','generateotp','test'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    public function actionTest(){

        $total_product=MedicineCategoryMaping::find()->with(['medicine','medicineCategory','medicine.brand'])->where(['MedicineCategoryId'=>5])->count(); 
        echo $total_product;
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
      $this->layout='homelayout.php';
      $banner=HomeBanner::find()->where(['IsDelete'=>0])->orderBy(['BannerId' => SORT_DESC])->all();
        //return $this->render('index');
      return $this->render('index',['banner'=>$banner]);
  }

  public function actionGovthospital()
  {
    $govthos=Hospital::find()->where(['IsDelete'=>0,'HospitalTypes' => 'govt'])->orderBy(['HospitalId' => SORT_ASC])->all();
        //return $this->render('index');
    return $this->render('govthospital',['govthos'=>$govthos]);
}

public function actionPvthospital()
{
    $pvthos=Hospital::find()->where(['IsDelete'=>0,'HospitalTypes' => 'pvt'])->orderBy(['HospitalId' => SORT_ASC])->all();
        //return $this->render('index');
    return $this->render('pvthospital',['pvthos'=>$pvthos]);
}

public function actionAmbulance()
{
    $Ambulance=Hospital::find()->where(['IsDelete'=>0,'HospitalTypes' => 'Ambulance'])->orderBy(['HospitalId' => SORT_ASC])->all();
        //return $this->render('index');
    return $this->render('Ambulance',['Ambulance'=>$Ambulance]);
}

public function actionBloodbank()
{
    $bloodbank=Hospital::find()->where(['IsDelete'=>0,'HospitalTypes' => 'BloodBank'])->orderBy(['HospitalId' => SORT_ASC])->all();
        //return $this->render('index');
    return $this->render('bloodbank',['bloodbank'=>$bloodbank]);
}

public function actionTerms()
{
	$this->layout = 'normal';

    return $this->render('terms');
}
public function actionPolicy()
{
	$this->layout = 'normal';

    return $this->render('policy');
}
public function actionUploadprescription()
{
       /* echo '<pre>';
        var_dump(Yii::$app->user->identity) ;
        echo '</pre>';
        echo Yii::$app->user->identity['EmpId'];*/
        $this->layout = 'normal';

        $model = new WebPrescription();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->user->identity){
                $model->EmployeeId = Yii::$app->user->identity['EmpId'];
            }else{
                $model->EmployeeId = '';
            }
            $image=UploadedFile::getInstance($model, 'Prescription');
            $fileend=explode(".", $image->name);
            $ext = end($fileend);
            $basepath=Yii::getAlias('@storage');
            Yii::$app->params['uploadPath'] = $basepath . '/webprescription/';
            $ppp=Yii::$app->security->generateRandomString();
            $path = Yii::$app->params['uploadPath'] . $ppp.".{$ext}";
            $model->Prescription = $ppp.".{$ext}";
            $model->OnDate=date('Y-m-d H:i:s');

            $image->saveAs($path);
            if($model->save())
            {
                Yii::$app->session->setFlash('success',"Prescription Uploaded Successfully...");
            }
            else
            {
                Yii::$app->session->setFlash('error',"There is some error.");
            }


            $to = "arundayamedicare@gmail.com"; //Receiver
            
            $body = '
            <table style="width: 700px;margin: 0 auto;border: 1px solid #012060;">
            <tr style="text-align: center;background-color:#39BAF0;">
            <td>
            <a href="'. Url::home() .'" class="logo-text">
            <img class="img-responsive" src="'. Url::home() .'/images/amlogo.png" alt="" title="" width="200" height="73" />
            </a>
            </td>
            </tr>
            <tr>
            <td>
            <table style="width: 100%;border-top: 1px solid #012060;">
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Name: '.$model->UserName.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Contact No: '.$model->UserContact.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Email ID: '.$model->UserMail.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Address: '.$model->UserAddress.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Message: '.$model->UserMessage.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Prescription: <a href="'. Url::home() .'/storage/webprescription/'.$model->Prescription.'">View Prescription</a>
            </div>
            </td>
            </tr>

            </table>
            </td>
            </tr>
            </table>
            ';
            $cc = "arundayamedicare1@gmail.com";
            $from = "info@onestopharma.in";
            $Subject = "New Upload Prescription From Arundaya Medicare Pvt. Ltd.";

            if ($model->sendEmail($to,$Subject,$body,$from,$cc)) {
                //echo "hiiiiiii";
                Yii::$app->session->setFlash('success', "Your Prescription Uploaded Successfully. We will respond to you as soon as possible.");
                return $this->redirect(['uploadprescription']);
                /*Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');*/
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
          /*  Yii::$app->session->setFlash('success', "Prescription uploaded successfully .");
          return $this->redirect('uploadprescription');*/

      } else {
        Yii::$app->session->setFlash('Error', "There is some error.");
        return $this->render('uploadprescription', [
            'model' => $model,
        ]);
    }

    return $this->render('uploadprescription',['model'=>$model]);
}
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
		//echo Yii::$app->session->get('CartIdentifire');


        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $sessionCartIdentifire = Yii::$app->session->get('CartIdentifire');
            /*************** Cart Details Update ******************/
            if(isset(Yii::$app->user->identity->EmployeeId)){
                $EmployeeId = Yii::$app->user->identity->EmployeeId;
                $Cart = Cart::find()
                ->where([
                  'EmployeeId'=>$EmployeeId,
                  'CartStatus'=>0,
                  'OrderType'=>2
              ])->one();
				//echo $EmployeeId."===";					
									//die();
                if($Cart){
                   $CartIdentifire = $Cart->CartIdentifire;
                   $SessionCartItems = CartItem::find()->where(['CartIdentifire'=>$sessionCartIdentifire])->all();
                   if($SessionCartItems){
                      foreach($SessionCartItems as $key=>$value){
                         $cartadded_status = $Cart->updateloginItem($CartIdentifire,$value->CartMedicineId,$value->CartItemQty);
                     }
                     $chkEmpCart = Cart::find()->where(['CartIdentifire'=>$sessionCartIdentifire,'EmployeeId'=>$EmployeeId])->one();
                     if(!$chkEmpCart){
                         $deleteSessioncartitem = CartItem::deleteAll('CartIdentifire = :CartIdentifire', [':CartIdentifire' => $sessionCartIdentifire]);
                         $deleteSessioncart = Cart::deleteAll('CartIdentifire = :CartIdentifire', [':CartIdentifire' => $sessionCartIdentifire]);
                     }


                 }


             }else{

               /************  Assign to Employee  ************/
               $CartIdentifire = $sessionCartIdentifire;
               $Cart_obj = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
               if($Cart_obj){
                  $Cart_obj->EmployeeId = $EmployeeId;
                  $Cart_obj->save();
              }
          }
          $session = Yii::$app->session;
          $session->open();
          Yii::$app->session->set('CartIdentifire',$CartIdentifire);
      }






      return $this->goBack(Yii::$app->request->referrer);
  } else {

     if($model->EmpId){
        $model->generateOtp();
    }
    else
        $model->OtpHash = '';

    return $this->render('login', [
        'model' => $model,
        'companies' => ArrayHelper::map(Company::find()->where(['ActiveStatus'=>1,'IsDelete'=>0])->asArray()->all(), 'CompanyId', 'Name')
    ]);

}
}

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();



        return $this->goHome();
    }
    

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
        	$model->save();


            $to = "arundayamedicare@gmail.com"; //Receiver
            
            $body = '
            <table style="width: 700px;margin: 0 auto;border: 1px solid #012060;">
            <tr style="text-align: center;background-color:#39BAF0;">
            <td>
            <a href="'. Url::home() .'" class="logo-text">
            <img class="img-responsive" src="'. Url::home() .'/images/amlogo.png" alt="" title="" width="200" height="73" />
            </a>
            </td>
            </tr>
            <tr>
            <td>
            <table style="width: 100%;border-top: 1px solid #012060;">
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Name: '.$model->Name.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Contact No: '.$model->Mob.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Email ID: '.$model->Email.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Subject: '.$model->Subject.'
            </div>
            </td>
            </tr>
            <tr>
            <td>
            <div style="margin: 5px 0px 5px 0px;">
            Message: '.$model->Message.'
            </div>
            </td>
            </tr>

            </table>
            </td>
            </tr>
            </table>
            ';
            $cc = "arundayamedicare1@gmail.com";
            $from = "info@onestopharma.in";
            $Subject = "New Contact From Arundaya Medicare Pvt. Ltd.";

            if ($model->sendEmail($to,$Subject,$body,$from,$cc)) {
                //echo "hiiiiiii";
                Yii::$app->session->setFlash('success', "Thank you for contacting us. We will respond to you as soon as possible.");
                return $this->redirect(['contact']);
                /*Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');*/
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSitemap()
    {
        return $this->render('sitemap');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	$model->save();
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionGenerateOtp(){

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $data=$model->generateOtp()) {

         header("Content-type: application/json; charset=utf-8");
         echo json_encode($data);
     }

 }

 public function actionOrderbysupport()
 {

    $TicketOrder=new TicketOrder();

    $session = Yii::$app->session;
    $session->open();
    if(Yii::$app->session->get('Tickettoken')=='')
    {
            //$isavail=TicketOrder::find()->where(['UserID'=>Yii::$app->user->identity->EmployeeId,'OrderStatus'=>0])->one();
        $isavail=TicketOrder::find()->where(['UserID'=>Yii::$app->user->identity->EmployeeId,'TicketStatus'=>1])->one();
        if(!$isavail)
        {

            $count=$TicketOrder::find()->count()+1;
            $Token='AMD'.str_pad($count,9,"0",STR_PAD_LEFT);


            $TicketOrder->UserID=Yii::$app->user->identity->EmployeeId;
            $TicketOrder->Token=$Token;
            $TicketOrder->TicketStatus=1;
            if($TicketOrder->save()){
               Yii::$app->session->set('Tickettoken',$Token);
           }
           else 
               var_dump($TicketOrder->getErrors());
       }
       else
       {

          $Token=$isavail->Token;
          Yii::$app->session->set('Tickettoken',$Token);
      }
  }
  else
  {


    $Token=Yii::$app->session->get('Tickettoken');
}

echo $Token;
}

public function actionMessagesend()
{
    $message=Yii::$app->request->get()['message'];
    $TicketOrder=new TicketOrder();
    $TicketChat=new TicketChat();

    $TicketToken=Yii::$app->session->get('Tickettoken');
    $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
    $to=$Ticket->Operator;
    $ticketid=$Ticket->TicketID;
    $result=$TicketChat->send(Yii::$app->user->identity->EmployeeId,$message,$ticketid,$to);
    echo $result;
}

public function actionFetchmessage()
{
    $TicketOrder=new TicketOrder();
    $TicketChat=new TicketChat();

    $TicketToken=Yii::$app->session->get('Tickettoken');
    if($TicketToken!=''){
        $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
        $to=Yii::$app->user->identity->EmployeeId;
        $ticketid=$Ticket->TicketID;
		    //if($Ticket->OrderStatus!=0){
        if($Ticket->TicketStatus==2){
            Yii::$app->session->set('Tickettoken','');
            echo json_encode(array());
        }
        else{
            $lasttime=Yii::$app->request->get()['lasttime'];
            $allmessage=$TicketChat->fetchmessage($ticketid,$to,$lasttime);

            echo json_encode($allmessage);
        }
    }
    else{
     echo json_encode(array());
 }
}
public function actionViewoperatorcart($crtid){
  $TicketCartMap = TicketCartMap::find()->where(['CartIdentifire'=>$crtid])->one();
  $checkOrder = Orders::find()->where(['CartIdentifire'=>$crtid])->one();
  if($checkOrder){
     $btnstatus = "disabled";
     $btnname = "Order Confirmed";
 }else{
     $btnstatus = "";
     $btnname = "Confirm";
 }
 if($TicketCartMap){
     $payment_deatils = array('0'=>'Not Set','1'=>'COD(Self)','2'=>'Online(Self)','3'=>'Direct-Debit(Company)','4'=>'EMI(Company)');
     $Cart = $TicketCartMap->cartIdentifire;
     $CartItems = $Cart->cartItems;
     if($CartItems){
        $cartitem = $CartItems;
    }else{
        $cartitem = "";
    }
    $CartDetails = array();
    $CartDetails['CartRegularPrice'] = $Cart->RegularTotalPrice;
    $CartDetails['DiscountedTotalPrice'] = $Cart->DiscountedTotalPrice;
    $CartDetails['PaymentMethod'] = $payment_deatils[$Cart->PaymentType];
    $CartDetails['PaymentMethods'] = $Cart->PaymentType;
    $CartDetails['EmiAmount'] = $Cart->EmiAmount;
    $CartDetails['CreditBalanceUsed'] = $Cart->CreditBalanceUsed;

    if($Cart->AddressID!=0){
        $employeeaddress = EmployeeAddress::find()->where(['EmployeeAddressId'=>$Cart->AddressID])->one();
        $address['EmployeeAddressId'] = $employeeaddress->EmployeeAddressId;
        $address['AddressLine1'] = $employeeaddress->AddressLine1;
        $address['AddressLine2'] = $employeeaddress->AddressLine2;
        $address['LandMark'] = $employeeaddress->LandMark;
        $address['State'] = $employeeaddress->State;
        $address['City'] = $employeeaddress->City;
        $address['Zipcode'] = $employeeaddress->Zipcode;
        $address['ContactNo'] = $employeeaddress->ContactNo;
        $address['EmployeeName'] = $Cart->employee->EmployeeName;
    }else{
        $address = "";
    }
    $EmployeeAddress = $address;

}else{
 $cartitem = "";
 $CartDetails = "";
 $EmployeeAddress = "";
}
return $this->render('viewoperatorcart',[
 'CartItems'=>$cartitem,
 'CartDetails'=>$CartDetails,
 'EmployeeAddress'=>$EmployeeAddress,
 'CartIdentifire'=>$crtid,
 'btnstatus'=>$btnstatus,
 'btnname'=>$btnname

]);
}

public function actionOrderconfirm(){

  $post = Yii::$app->request->post();
  $carttoken = $post['Order']['CartIdentifire'];

  $TicketCartMap = TicketCartMap::find()->where(['CartIdentifire'=>$carttoken])->one();
  if($TicketCartMap){
     $Cart = $TicketCartMap->cartIdentifire;
     if($Cart){
        $Cart->CartStatus = 1;
        if($Cart->save()){
           $checkOrder = Orders::find()->where(['CartIdentifire'=>$carttoken])->one();
           if(!$checkOrder){
              $neworder = new Orders();
              $neworder->CartIdentifire = $Cart->CartIdentifire;
              $order_obj = new Orders();
              $neworder->OrderIdentifier = $order_obj->getOrderidentifier();
              $neworder->EmployeeId = $Cart->EmployeeId;
              $neworder->CompanyId = $Cart->employee->CompanyId;
              $neworder->PaymentType = $Cart->PaymentType;
              $neworder->DeliveryAddressID = $Cart->AddressID;
              $neworder->OrderStatus = 1;
              $neworder->ConfirmDate = date("Y-m-d");
              if($Cart->PaymentType!=4){
                 $neworder->OrderTotalPrice = $Cart->DiscountedTotalPrice;
             }else{
                 $neworder->OrderTotalPrice = $Cart->RegularTotalPrice;
             }
             $neworder->CreditBalanceUsed = $Cart->CreditBalanceUsed;

             $neworder->EmiPlanId = $Cart->EmiPlanId;
             $neworder->EmiPlanPeriod = $Cart->EmiPlanPeriod;
             $neworder->EmiAmount = $Cart->EmiAmount;

             if($Cart->PaymentType==3){

                 $neworder->EmiAmount = $Cart->DiscountedTotalPrice;
             }

             $neworder->OrderType = $Cart->OrderType;

             if($neworder->save()){
                 $CartItems = $Cart->cartItems;
                 if($CartItems){
                    foreach($CartItems as $keyitm => $valueitm){
                       $orderitem_create = new OrderItems();
                       $orderitem_create->CartIdentifire = $valueitm->CartIdentifire;
                       $orderitem_create->OrdersID = $neworder->OrderId;
                       $orderitem_create->OrderMedicineID = $valueitm->CartMedicineId;
                       $orderitem_create->OrderItemName = $valueitm->CartItemName;
                       $orderitem_create->OrderItemQty = $valueitm->CartItemQty;
                       if($Cart->PaymentType!=4){
                          $OrderItemPrice = $valueitm->CartItemDiscountedPrice;
                          $OrderItemTotalPrice = $valueitm->CartItemDiscountedRowTotal;
                      }else{
                          $OrderItemPrice = $valueitm->CartItemRegularPrice;
                          $OrderItemTotalPrice = $valueitm->CartItemRegularRowTotal;
                      }
                      $orderitem_create->OrderItemPrice = $OrderItemPrice;
                      $orderitem_create->OrderItemTotalPrice = $OrderItemTotalPrice;
                      $orderitem_create->IsPrescription = $valueitm->IsPrescription;
                      $orderitem_create->save();

                  }
              }
              /********Employe Balance update*******/
              $employee = $Cart->employee;
              if($employee){
                $CreditBalance = $employee->CreditBalance;
                if($Cart->PaymentType == 4 || $Cart->PaymentType == 3){
                   $updatebalance = $CreditBalance - $neworder->OrderTotalPrice;
                   $employee->CreditBalance = $updatebalance;
                   $employee->CreditOnHold = $employee->CreditOnHold + $neworder->OrderTotalPrice;
               }																
               $employee->save();
           }
           /*TicketOrder Close*/
           $ticketorder = TicketOrder::find()->where(['Token'=>$TicketCartMap->TicketOrderToken])->one();
           if($ticketorder){
            $ticketorder->TicketStatus = 2;
            $ticketorder->save();
        }

        $ShipRocket =new ShipRocket();

        if($ShipRocket->Connect()){
            $data=$ShipRocket->PrepareOrder($neworder->OrderIdentifier);

            if($data)
             $ShipRocket->PlaceOrder($data);


     }


     Yii::$app->session->setFlash('success', 'Order confirm Successfully');
 }else{
     Yii::$app->session->setFlash('error', 'Order not Placed due to some error...');
 }
}else{
  Yii::$app->session->setFlash('error', 'Order already placed...');
}
}else{
   Yii::$app->session->setFlash('error', 'Cart not converted to order ..');
}
}else{
    Yii::$app->session->setFlash('error', 'Cart data not available..');
}

}else{
 Yii::$app->session->setFlash('error', 'CartIdentifire not available...');
}
return $this->redirect(['viewoperatorcart', 'crtid' => $carttoken]);
}

public function actionOrders()
{ 
    $order=Orders::find()->where(['IsDelete'=>0,'EmployeeId' => Yii::$app->user->identity->EmployeeId])->orderBy(['OrderIdentifier' => SORT_DESC])->all();
    return $this->render('orders',['order'=>$order]);
}

public function actionProfile()
{
 $profile=Employee::find()->where(['IsDelete'=>0,'EmployeeId' => Yii::$app->user->identity->EmployeeId])->all();
 return $this->render('profile',['profile'=>$profile]);
}

public function actionPrescription()
{
 $prescription=WebPrescription::find()->where(['IsDelete'=>0,'EmployeeId' => Yii::$app->user->identity->EmpId])->all();
 return $this->render('prescription',['prescription'=>$prescription]);
}

public function actionSubscriptions()
{
    return $this->render('subscriptions');
}
public function actionAddnewaddress(){

    if(Yii::$app->user->identity!=''){
        $employeeid = Yii::$app->user->identity->EmployeeId;
    }else{
        $employeeid = '';
    }

    $model = new EmployeeAddress();
    if($model->load(Yii::$app->request->post()))
    {
        $model->EmployeeId = $employeeid;  
        $model->IsDelete = 0;
        $model->OnDate = date('Y-m-d H:i:s'); 
        if($model->save()){
            Yii::$app->session->setFlash('success', 'New Address added successfully..');
            return $this->redirect(['addnewaddress']);
        }else{
            Yii::$app->session->setFlash('error', 'Employee address not added..');
        }
    }



    return $this->render('addnewaddress',['model'=>$model]);
}

public function actionEmi()
{
    $results=array();
    $data=array();
    $res=EmiSchedules::find()->where(['EmployeeId'=>Yii::$app->user->identity->EmployeeId])->orderBy(['OrderIdentifier'=>SORT_DESC,'EmiSchedulesId'=>SORT_ASC ])->all();
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


        $data['EMI']['Current']['Total']=0;
        $data['EMI']['Current']['Month']=$current_Month;
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
return $this->render('emi',['emi'=>$results]);
}
}
