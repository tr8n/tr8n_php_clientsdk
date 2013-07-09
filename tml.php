<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:tml="http://www.tr8nhub.com/2013/tml">

<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://geni.berkovich.net/tr8n/api/v1/proxy/boot.js?debug=true&tml=true"></script>
</head>

<body>
	<p>
		<tml:label>Hello World</tml:label>
	</p>
	
	<br><br>
	<?php
		for ($i=1; $i<=10; $i++) {
	?>
	<p>
		<tml:label>You have <tml:token type="data" name="count"><?php echo $i ?></tml:token> messages</tml:label>
	</p>
	<?php
  		}
	?>
	
	
	<br><br>
	<tml:label>You have <tml:token type="data" name="count">1</tml:token> messages from <tml:token type="data" name="num">1001</tml:token> people</tml:label>
	<br><br>
	
	
	
</body>
</html>