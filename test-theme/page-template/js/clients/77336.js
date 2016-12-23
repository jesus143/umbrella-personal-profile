
				function load_chat(){
					var chatURL, po, s;
					var acct = 77336;
					var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
					var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
					
					po = document.createElement('script');
					po.type = 'text/javascript'; 
					po.async = true;
					s = document.getElementsByTagName('script')[0];
					
					chatURL = '//livewebchatcode.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true/(disable_pro_active)/true/(theme)/2?r='+referrer+'&l='+location+'&idn='+acct;

					po.src = chatURL;					 
					s.parentNode.insertBefore(po, s);
					
					bind_exitpop();
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
		