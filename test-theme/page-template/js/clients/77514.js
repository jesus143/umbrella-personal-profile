
				function load_chat(){
					var chatURL, po, s;
					var acct = 77514;
					
					var LHCChatOptions = {};
					LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
								
					var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
					var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
					
					po = document.createElement('script');
					po.type = 'text/javascript'; 
					po.async = true;
					s = document.getElementsByTagName('script')[0];
					
					chatURL = '//livewebchatcode.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true/(theme)/1?r='+referrer+'&l='+location+'&idn='+acct;

					po.src = chatURL;					 
					s.parentNode.insertBefore(po, s);
					
					
				}

				function trigger_click(){
					lh_inst.lh_openchatWindow();
					setTimeout(function() {
						$('#lhc_remote_window').trigger('click');
					}, 1000);
					
				}
				function bind_exitpop(){
					$(window).bind('beforeunload', function(){
						return 'Please dont go!';
					});
				}
		