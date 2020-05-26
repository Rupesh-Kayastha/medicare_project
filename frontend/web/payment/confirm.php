<?php
$req_dump = print_r($_REQUEST, TRUE);
$fp = fopen('log/log.txt', 'a+');
fwrite($fp, $req_dump);
fclose($fp);


    include('config.php');
    $merchatid=$_REQUEST['merchantId'];
    $responsecode=$_REQUEST['responseCode'];
    $transtatus=$_REQUEST['respDescription'];
    $transactionid=$_REQUEST['merchantTxnNo'];
    $amount=$_REQUEST['amount'];
    $paymentid=$_REQUEST['paymentID'];
    $merchantid=$_REQUEST['merchantId'];
    //$fetchdata=mysqli_query($conn,"select * from Donation where TransactionId = '$transactionid' and amount = '$amount'");
    //$chk=mysqli_num_rows($fetchdata);
    //$fetch=mysqli_fetch_assoc($run);
    //$date=date("d/m/y");
    
    $sql="select * from Donation where TransactionId = '$transactionid' and amount = '$amount'";
	$result=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);

	if(mysqli_num_rows($result) == 1)
	{
		
    	$sql = mysqli_query($conn,"update Donation set IsSuccess=1, PaymentID='$paymentid',MerchantID='$merchantid' where TransactionId='$transactionid'");
    	if($sql){
    	    
    	$to = $row['UserMail'];
        $subject = "PaymentSuccess Success";
        
        $message = "
       <div style='background: #eee;width: 800px;border: 1px solid #f00;'>
    	<img src='https://peoplesforum.in/img/logo.png' style='text-align:center;' width='200px'/><br />
    	<h3 style='text-align:center;'>Donate</h3>
    	<p style='padding:40px;'> Name : ".$row['UserName']."<br/><br/>
    	 Email : ".$row['UserMail']."<br/><br/>
    	 Amount : ".$row['amount']."<br/><br/>
    	 Mobile : ".$row['UserMob']."<br/><br/>
    	 
    	<br /><br />
    	Regards,
    	<br>
    	Peoplesforum
    	</>
    	</div>
        ";
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers
        $headers .= 'From: info@peoplesforum.net.in' . "\r\n";
        
        mail($to,$subject,$message,$headers);
    	    
    	    
    	    
    	/*function SendHTMLMail1($to,$subject,$mailcontent,$from)
    	{
    		$headers1  = "MIME-Version: 1.0\r\n";
    		$headers1 .= "Content-type: text/html; charset=iso-8859-1\r\n";
    		$headers1 .= "From: info@peoplesforum.net.in"."\r\n";
    		mail($to,$subject,$mailcontent,$headers1);
    	}
    	
        $contentAdmin="<div style='background: #eee;width: 800px;border: 1px solid #f00;'>
    	<img src='https://peoplesforum.in/img/logo.png' width='200px'/><br />
    	<h3 style='text-align:center;'>Peoplesforum</h3>
    	<p style='padding:40px;'> Name : ".$fetch['UserName']."<br/><br/>
    	 Email : ".$fetch['UserMail']."<br/><br/>
    	 Amount : ".$fetch['amount']."<br/><br/>
    	 Mobile : ".$fetch['UserMob']."<br/><br/>
    	 
    	<br /><br />
    	Regards,
    	<br>
    	Peoplesforum
    	</>
    	</div>";
    	$toEmail1=$fetch['email'];
    	
    	$subject1="PaymentSuccess Success";
    	$fromEmail1='info@peoplesforum.net.in';
    	
    	
    	//SendHTMLMail12($toEmail,$subject,$content,$fromEmail);
    	SendHTMLMail1($toEmail1,$subject1,$contentAdmin,$fromEmail1);*/
    	echo "<script>location.href='donate1.php';</script>";
    	}
    
	}

?>