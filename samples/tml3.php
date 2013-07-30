<?php
 $server = "http://geni.berkovich.net";
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:tml="http://www.tr8nhub.com/2013/tml">

<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	
	<script type="text/javascript">
	<!--
	function addTr8nCSS(doc, src) {
	  var css = doc.createElement('link');
	  css.setAttribute('type', 'application/javascript');
	  css.setAttribute('href', src);css.setAttribute('type', 'text/css');
	  css.setAttribute('rel', 'stylesheet');css.setAttribute('media', 'screen');
	  doc.getElementsByTagName('head')[0].appendChild(css);
	};
	 
	function addTr8nScript(doc, id, src, onload) {
	  var script = doc.createElement('script');
	  script.setAttribute('id', id);
	  script.setAttribute('type', 'application/javascript');
	  script.setAttribute('src', src);
	  script.setAttribute('charset', 'UTF-8');
	  if (onload) script.onload = onload;
	  doc.getElementsByTagName('head')[0].appendChild(script);
	};

	(function(doc) {
	    if (window.addEventListener) {
	        // Standard
	        window.addEventListener('load', initTr8n, false);
	    }
	    else if (window.attachEvent) {
	        // Microsoft
	        window.attachEvent('onload', initTr8n);
	    }
	    function initTr8n() {
			if (doc.getElementById('tr8n-jssdk')) return;

			var jQueryCurrentVersion = null;
	    	if (!(typeof jQuery === "undefined")) {
	    		jQueryCurrentVersion = $.noConflict(true);
	    	}

	    	addTr8nScript(doc, 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js', function() {
	    		var tr8nJQ = $.noConflict(true);
	    		if (jQueryCurrentVersion) {
		    		window.jQuery = window.$ = jQueryCurrentVersion;
// 		    		alert($.fn.jquery);
				}
// 	    		alert(tr8nJQ.fn.jquery);
				addTr8nCSS(doc, '<? echo $server ?>/assets/tr8n/tr8n.css?ext=true');
		    	addTr8nScript(doc, 'tr8n-jssdk', '<? echo $server ?>/assets/tr8n/tr8n-compiled.js', function() {
				    addTr8nScript(doc, 'tr8n-proxy', '<? echo $server ?>/tr8n/api/v1/proxy/init.js', function() {
				    	Tr8n.SDK.Proxy.initTml();
				    	
				    	tr8nJQ(document).ready(function() {
							tr8nJQ.ajax({
							  url: "<? echo $server ?>/tr8n/api/v1/language/test",
							  crossDomain: true,
							  xhrFields: {
								withCredentials: true
							  }
							})
						  	.done(function(data) { 
// 							  	alert($.parseJSON(data).message); 
							})
						    .fail(function(data) {
							     alert("error"); 
						    })
						    .always(function(data) {
							});
						});
					    
					});
		  	    });
	    	});
		}
	})(window.document);
	 
	//-->
	</script>
</head>

<body>
	<tml:label>	
	<?php
		echo "Hello World"
	?>
	</tml:label>
</body>
</html>