
				var LHCChatOptions = {};
				LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
				$(document).ready(function() {
					document.domain = 'learnicthere.xyz'; 
				});
				function trigger_click(){
					lh_inst.lh_openchatWindow();
					$('#lhc_remote_window').trigger('click');
				}
				function bind_exitpop(){
					$(window).bind('beforeunload', function(){
						return 'Please dont go!';
					});
				}
				
				function load_chat(urlsrc){
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
					var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
					po.src = urlsrc+referrer+'&l='+location+'&id='+16972;
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				}
		