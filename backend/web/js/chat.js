var lasttime='';
var Chat_AutoTrigger;
var token_update;
var TotalScrollHeight;
var CurentScroll;
var ChatHeight;
function fetchmessage(token,t=0,initial=false)
{
    $.ajax({url:HOME_URL+"/ticket-order/fetchmessage?token="+token+"&lasttime="+lasttime,
               success:function(result)
               {
                var res=result;
                if(res)
                {
                    var html='';
                    $.each(res,function(i,it){
                        lasttime=it.FullTime;
                        var clname=(it.isme==0)?'chat-message-content-right':'chat-message-content-left';
                        var imgcl=(it.isme==0)?'right':'';
						var imgval=(it.isme==0)?'support.png':'user.png';
						
                        html+='<div class="chat-message clearfix"><img src="'+STORAGE_URL+'/default/'+imgval+'" class="'+imgcl+'" alt="" width="32" height="32"><div class="'+clname+' clearfix"><span class="chat-time">'+it.Time+'</span><h5>'+it.username+'</h5><p>'+it.message+'</p></div></div><hr>';
                    });
                    $('#token'+token).find('.chat-history').append(html);
                    
                    if($('#token'+token).find('.chat-history').html().trim()!='')
                    {
                        $('#token'+token).fadeIn(300);
                    }
					
					TotalScrollHeight=$('#token'+token).find('.chat-history').prop("scrollHeight");
					CurentScroll=$('#token'+token).find('.chat-history').scrollTop();
					ChatHeight=$('#token'+token).find('.chat-history').height();
					
					if(TotalScrollHeight-CurentScroll<ChatHeight+100)
					$('#token'+token).find('.chat-history').animate({ scrollTop: $('#token'+token).find('.chat-history').prop("scrollHeight")}, 1000);
					
					
					if(initial){
						$('#token'+token).find('.chat-history').animate({ scrollTop: $('#token'+token).find('.chat-history').prop("scrollHeight")}, 1000);
					}
                }
                
				
					
				
                if(t==0 && $('#token'+token).find('.chat-history').html()!='')
                {
					
                Chat_AutoTrigger=setTimeout(function(){ fetchmessage(token,0,false); },2000);
                }
               }
    });
}
var current_ticket='';
function tickethandle(ticketid,ticket_token)
{
		//$('#PaymentType').removeAttr('selected').find('option:first').attr('selected', 'selected');
		
		$('#operator_cart').hide();
		
		if(current_ticket!=''){
			closechat(current_ticket);
		}
		
		current_ticket=ticket_token;
		
        $.ajax({url:HOME_URL+"/ticket-order/tickethandle?ticketid="+ticketid,
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
               success:function(result)
               {
				   
				result=result.trim();
				
                if(result!='')
                {
					
				var html='<div class="live-chat" id="token'+result+'">'+
							'<header class="clearfix">'+
								'<a href="#" class="chat-close">x</a><h4>'+result+'</h4><span class="chat-message-counter">0</span>'+
							'</header>'+
							'<div class="chat">'+
								'<div class="chat-history"></div>'+
								'<form action="#/" method="get"><textarea class="form-control" rows="1" placeholder="Type your message…" id="chat_input'+result+'"> </textarea></form>'+
							'</div>'+
						'</div>';
		
				   $('#chatbox').html(html);
				   getEmployeeDetails(result);
				   viewCart(ticket_token);
                   openchatbox(result);

				   
				   $('#ticket_token').val(ticket_token);
				  
				   
                }
               },
			   complete:function(json)
			   {
					SimpleLoading.stop();
			   },
        });
}
	
	function getEmployeeDetails(token){
		
		$.ajax({url:HOME_URL+"/ticket-order/ticketemployee?token="+token,
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
                success:function(result)
                {				   
					if(result!='')
					{
						var res=result;
						$('#EmpId').html(			res.EmpId);
						$('#EmployeeName').html(	res.EmployeeName);
						$('#CompanyName').html(		res.CompanyName);
						$('#CreditLimit').html(		res.CreditLimit);
						$('#CreditBalance').html(	res.CreditBalance);
						$('#EmployeeId').val(res.EmployeeId);
						
					   $('#operator_cart').show();
					}
               },
			   complete:function(json)
			   {
					SimpleLoading.stop();
			   },
        });
	}
	
	
	function openchatbox(result)
	{
		
		$('#chat_input'+result).keypress(function(e) {
			if(e.which == 13) {
				var message=$(this).val();
				$.ajax({url:HOME_URL+"/ticket-order/messagesend?message="+message+"&token="+result,
					beforeSend:function(json)
				    { 
					   // SimpleLoading.start('hourglass'); 
				    },
				   success:function(res)
				   {
					$('#chat_input'+result).val('');
					if(res==1)
					{
						fetchmessage(result,1);
					}
				   },
				   complete:function(json)
				   {
						//SimpleLoading.stop();
				   }
				});
			}
		});
		
		$('.chat-close').on('click', function(e) {
			closechat(result);
		});
		
		fetchmessage(result,0,true);
	}
	
	function closechat(){
		clearTimeout(Chat_AutoTrigger);
		lasttime='';
		$("#chatbox").html("");
		$('#operator_cart').hide();
	}
	function viewCart(ticket_token){
		$('#cartData').html('');
		$('#ctra').html("");
		$('#ctda').html("");
		$('#payment_deatils').html("");
		$('#employee_address').html("");
		$('#emi_details').hide();
		$('#emi_details').html("");
		$('#cart_box_operation').hide();
		//$('#PaymentType').removeAttr('selected').find('option:first').attr('selected', 'selected');
		if(ticket_token!=''){
			
			$.ajax({
					url: HOME_URL+"/ticket-order/viewcart/",
					type: "POST",
					data: {
						ticket_token:ticket_token
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						//var data = data.trim();
						var result = data;
						if(result!=''){
							
							//alert(result.CartIdentifire);
							var htmldata = "";
							var slno = 1;
							$.each(result.CartItems, function( i, it )
                            {
								htmldata += '<tr>';
								htmldata += '<td>'+slno+'</td>';
								htmldata += '<td>'+it.CartItemName+'</td>';
								htmldata += '<td><a class="pointer"  onClick="cartUpdate(0,'+it.CartMedicineId+',\''+result.TicketToken+'\');"><i class="fa fa-minus" aria-hidden="true"></i></a><span class="qty">'+it.CartItemQty+'</span><a class="pointer" onClick="cartUpdate(1,'+it.CartMedicineId+',\''+result.TicketToken+'\');"><i class="fa fa-plus" aria-hidden="true"></i></a></td>';
								htmldata += '<td>'+it.CartItemRegularPrice+'</td>';
								htmldata += '<td>'+it.CartItemDiscountedPrice+'</td>';
								htmldata += '<td>'+it.CartItemRegularRowTotal+'</td>';
								htmldata += '<td>'+it.CartItemDiscountedRowTotal+'</td>';
								htmldata += '<td><a class="pointer" onClick="cartUpdate(2,'+it.CartMedicineId+',\''+result.TicketToken+'\');"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
								htmldata += "</tr>";
								
								slno++;
							});
							if(result.CartItems.length == 0){
								htmldata += "<tr><td colspan='8'>Cart is Empty</td></tr>";
								$('.generate_link').hide();
							}
							if(result.CartItems.length!=0){
								$('#ctra').html("Rs. "+result.RegularTotalPrice.toFixed(2));
								$('#ctda').html("Rs. "+result.DiscountedTotalPrice.toFixed(2));
								$('#payment_deatils').html(result.PaymentType);
								$('#cart_box_operation').show();
							}
							var address_data = "";
							var emi_details = "";
							if(result.EmployeeAddress!=""){
								var add_res = result.EmployeeAddress;
								address_data = add_res.AddressLine1+"<br/>"+add_res.AddressLine2+"<br/>"+add_res.LandMark+"<br/>"+add_res.State+"<br/>"+add_res.City+"<br/>"+add_res.Zipcode+"<br/>"+add_res.ContactNo;
							}else{
								address_data = "Not Set";
							}
							$('#employee_address').html(address_data);
							
							if(result.EmiDetails!=""){
								var emi_res = result.EmiDetails;
								emi_details = "EMI Plan : "+emi_res.EmiPlanName+"<br/>"+"EMI Amount: Rs. "+emi_res.EmiAmount;
								$('#emi_details').show();
								
							}else{
								emi_details = "Not Set";
							}
							//alert(123);
							if(result.generatebtn_status == 1){
								$('.generate_link').show();
							}else{
								$('.generate_link').hide();
							}
							$('#emi_details').html(emi_details);
							
							
						}else{
							htmldata += "<tr><td colspan='8'>Cart is Empty</td></tr>";
						}
						/************ Reset the all value  ***************/
						$('#PaymentType').prop('selectedIndex',0);
						$('#emiplandetails').html("");
						$('#medicine_name').val("");
						$('#medicine_id').val("");
						$('#med_add_btn').attr("disabled",true);
						
						$('#cartData').html(htmldata);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else{
			console.log('Error: viewCart() => ticket_token cannot be empty...');
			return false;
		}
		//alert(ticket_token);
	}
token_update = setInterval(function(){ 
	getAlltoken();
}, 2000);
function getAlltoken(){
	$.ajax({
		url: HOME_URL+'/ticket-order/getalltoken',
		type: "POST",
		data: {},
		success: function (result) {
			if(result.status == 1){
				$('#alltoken').html(result.data);
			}
		},
	});
}