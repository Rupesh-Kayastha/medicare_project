 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Peoplesforum</title>
<?php 
$random_num=rand();
//$random_encrypt = sha1($random_num); 
?>
</head>
<body>
     <!--onload="document.forms['myForm'].submit()"-->
<div id="preloader"></div>
<div id="wrapper">
  <div class="clearfix"></div>
  <!-- content begin -->
  <div id="content_w">
     <div class="wrapper">
        <div class="inner1" style="padding-top:20px;">

             <form id='myForm' class="dnt" name='myForm' action='https://qa.phicommerce.com/pg/api/sale?v=2' method='post' enctype="application/x-www-form-urlencoded">

                <div class="col-sm-12 form-group">
                    <div class="form-wrapper">
                        <?php 
                        if (isset($_POST['submitbut'])){
                            include('config.php');
                            	$username=$_POST['customerName'];
                            	$usercontact=$_POST['customerMobileNo'];
                            	$useremail=$_POST['customerEmailID'];
                            	$amount=$_POST['amount'];
                            	
                            	$stmt = $conn->prepare("INSERT INTO Donation(UserName,UserMob,UserMail,amount,TransactionId) VALUES (?,?,?,?,?)");
                            	$stmt->bind_Param("sssss", $username,$usercontact,$useremail,$amount,$random_num); 
                            	$stmt->execute();?>
                            	
                            	
                            	<input type="hidden" class="form-control" id="customerName" name="customerName" value="<?php echo $username?>"/>
                            	<input type="hidden" class="form-control" name="customerMobileNo" id="customerMobileNo" value="<?php echo $usercontact?>"/>
                            	<input type="hidden" class="form-control" name="customerEmailID" id="customerEmailID" value="<?php echo $useremail?>"/>
                            	<input type="hidden" class="form-control" id="amount" name="amount" value="<?php echo $amount?>"/>
                            	
                            	<input type="hidden" class="form-control" id="returnURL" name="returnURL" value="https://peoplesforum.in/success.php" />
                                <input type="hidden" class="form-control" id="merchantTxnNo" name="merchantTxnNo" value="<?php echo $random_num ?>" />
                                <input type="hidden" class="form-control" id="merchantID" name="merchantID" value="T_21301" />
                                <input type="hidden" class="form-control" id="payType" name="payType" value="0" />
                                <input type="hidden" class="form-control" id="transactionType" name="transactionType" value="SALE" />
                                <input type="hidden" class="form-control" id="currencyCode" name="currencyCode" value="356" />
                                <input type="hidden" class="form-control" id="secureHash" name="secureHash" value="" />
                                <input type="hidden" class="form-control" id="txnDate" name="txnDate" value="<?php echo date("YmdHis") ?>" />
                                <input type="hidden" class="form-control" id="hashtext" name="hashtext" value="" />
                                
                                
                                <input type="submit" id="submitbut" style="color:#fff !important;background-color:#fff;border:none !important;" onclick="getHMACResult()" value="submit" />
                        	    
                     <?php   }
                        ?>
                        
                    </div>
                </div>

        </form>
            
        </div>
    </div>
  </div>
</div>





<?php include("footer_scripts.php");?>
<script type="text/javascript" src="js/sha.js"></script>
<script>
    window.onload=function(){
        getHMACResult();
        document.getElementById("button1").click();
};
</script>
<script>
        /*function getHMACResult() {
            var hashInput = "";
            
            hashInput =
			document.forms["myForm"].amount.value +
			document.forms["myForm"].currencyCode.value+
            document.forms["myForm"].merchantID.value +
			document.forms["myForm"].merchantTxnNo.value;
            var shaObj = new jsSHA("SHA-256", 'TEXT');
				
            shaObj.setHMACKey("286f7eff19bd4de0bba322e097a79891", "TEXT"); // use the key shared with the merchant
            document.getElementById("hashtext").innerHTML = hashInput
            shaObj.update(hashInput);

            var result = shaObj.getHMAC("HEX");
            //alert(result);
            document.forms.myForm.secureHash.value = result;
        }*/

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
				
            shaObj.setHMACKey("286f7eff19bd4de0bba322e097a79891", "TEXT"); // use the key shared with the merchant
            //document.getElementById("hashtext").innerHTML = hashInput
            $('#hashtext').val(hashInput);
            shaObj.update(hashInput);

            var result = shaObj.getHMAC("HEX");
            //alert(result);
            $('#secureHash').val(result);
            //document.forms.myForm.secureHash.value = result;
            
            //document.forms['myForm'].submit();
            $("form").submit();
        }
    </script>
</body>
</html>


