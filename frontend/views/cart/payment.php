<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Payment';
$random_num=rand();

?>
<div class="main-content">
    <div class="container cart-block-style"> 
		<div class="jumbotron text-xs-center">
			<form id='paymentform' class="dnt" name='myForm' action='https://qa.phicommerce.com/pg/api/sale?v=2' method='post' enctype="application/x-www-form-urlencoded">
            <!-- <form id='myForm' class="dnt" name='myForm' action='' method='post' enctype="application/x-www-form-urlencoded"> -->

                <div class="col-sm-12 form-group">
                    <div class="form-wrapper">                    	
                        <input type="text" class="form-control" id="customerName" name="customerName" value="<?=$empname?>"/>
                    	<input type="text" class="form-control" name="customerMobileNo" id="customerMobileNo" value="<?=$empnum?>"/>
                    	<input type="text" class="form-control" name="customerEmailID" id="customerEmailID" value="<?=$empmail?>"/>
                    	<input type="text" class="form-control" id="amount" name="amount" value="<?=$amount?>"/>
                    	
                    	<input type="text" class="form-control" id="returnURL" name="returnURL" value="http://onestoppharma.in/cart/success" />
                        <input type="text" class="form-control" id="merchantTxnNo" name="merchantTxnNo" value="<?php echo $random_num ?>" />
                        <input type="text" class="form-control" id="merchantID" name="merchantID" value="T_89100" />
                        <input type="text" class="form-control" id="payType" name="payType" value="0" />
                        <input type="text" class="form-control" id="transactionType" name="transactionType" value="SALE" />
                        <input type="text" class="form-control" id="currencyCode" name="currencyCode" value="356" />
                        <input type="text" class="form-control" id="secureHash" name="secureHash" value="" />
                        <input type="text" class="form-control" id="txnDate" name="txnDate" value="<?php echo date("YmdHis") ?>" />
                        <input type="text" class="form-control" id="hashtext" name="hashtext" value="" />
                        <input type="submit" id="submitbut" style="color:#fff !important;background-color:#fff;border:none !important;" onclick="getHMACResult()" value="submit" />
                    </div>
                </div>

            </form>
            
        </div>
		</div>
	</div>

<?php
$script = <<< JS
    window.onload=function(){
        getHMACResult();
        //document.getElementById("button1").click();
    };


function getHMACResult() {
    var customerEmailID = $('#customerEmailID').val();
    var customerMobileNo =$('#customerMobileNo').val();
    var hashInput = "";
    
    hashInput =
    $('#amount').val()+ 
    $('#currencyCode').val() +  customerEmailID + customerMobileNo +
    $('#customerName').val() +  
    $('#merchantID').val() +
    $('#merchantTxnNo').val() + 
    $('#payType').val() + 
    $('#returnURL').val() +
    $('#transactionType').val() +
    $('#txnDate').val();
    

    var shaObj = new jsSHA("SHA-256", 'TEXT');
        
    shaObj.setHMACKey("50955a1c33044019b2d9b2d1d02e5f11", "TEXT"); // use the key shared with the merchant
    //document.getElementById("hashtext").innerHTML = hashInput
    $('#hashtext').val(hashInput);
    shaObj.update(hashInput);

    var result = shaObj.getHMAC("HEX");
    //alert(result);
    $('#secureHash').val(result);
    //document.forms.myForm.secureHash.value = result;
    
    document.forms['paymentform'].submit();
   // $("form").submit();
}
JS;
$this->registerJs($script);
?>
