var homeurl='http://onestoppharma.in/';
(function() {
setTimeout(function(){ fetchmessage(); },500);
	//$('#live-chat header').on('click', function() {
	//
	//	$('.chat').slideToggle(300, 'swing');
	//	$('.chat-message-counter').fadeToggle(300, 'swing');
	//
	//});

	$('.chat-close').on('click', function(e) {
        e.preventDefault();
        $('.chat').slideToggle(300, 'swing');
		$('.chat-message-counter').fadeToggle(300, 'swing');
		//$('#live-chat').fadeOut(300);

	});
    
    $('#chat_input').keypress(function(e) {
        if(e.which == 13) {
            var message=$(this).val();
            $.ajax({url:homeurl+"/site/messagesend?message="+message,
               success:function(result)
               {
                $('#chat_input').val('');
                if(result==1)
                {
                    fetchmessage(1);
                }
               }
            });
        }
    });

}) ();
var lasttime='';
function fetchmessage(t=0)
{
	
    $.ajax({url:homeurl+"/site/fetchmessage?lasttime="+lasttime,
               success:function(result)
               {
                var res=JSON.parse(result);
                if(res)
                {
                    var html='';
                    $.each(res,function(i,it){
                        lasttime=it.FullTime;
                        var clname=(it.isme==0)?'chat-message-content-right':'chat-message-content-left';
                        var imgcl=(it.isme==0)?'right':'';
                        html+='<div class="chat-message clearfix"><img src="'+homeurl+'storage/default/user.png" class="'+imgcl+'" alt="" width="32" height="32"><div class="'+clname+' clearfix"><span class="chat-time">'+it.Time+'</span><h5>'+it.username+'</h5><p>'+it.message+'</p></div></div><hr>';
                    });
                    $('.chat-history').append(html);
                    
                    if($('.chat-history').html().trim()!='')
                    {
                        $('#live-chat').fadeIn(300);
                    }
					$(".chat-history").animate({ scrollTop: $('.chat-history').prop("scrollHeight")}, 1000);
                }
                
				
				
                if( (t==0 && $('.chat-history').html().trim()!='') || (t==0 && lasttime==''))
                {
				
					setTimeout(function(){ fetchmessage(); },10000);
                }
               }
    });
}
function orderbysupport()
    {
        $.ajax({url:homeurl+"/site/orderbysupport",
               success:function(result)
               {
                if(result!='')
                {
                    if($('.chat-history').html().trim()=='')
                    {
                        var d = new Date();
                        var time=d.getHours()+':'+d.getMinutes();
                        $('.chat-history').html('<div class="chat-message clearfix"><img src="'+homeurl+'storage/default/user.png" alt="" width="32" height="32"><div class="chat-message-content-left clearfix"><span class="chat-time">'+time+'</span><h5>Operator</h5><p>We are live and ready to chat with you now. Say something to start a live chat.</p></div></div>');
                    }
                    $('#live-chat').fadeIn(300);
					$(".chat-history").animate({ scrollTop: $('.chat-history').prop("scrollHeight")}, 1000);
					
                }
               }
        });
    }