<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
	<style>
		@import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
		@import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
			#myDiv {
            display: none; /* Hide the div by default */
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
        }
	</style>
	<link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
	<script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>
</head>
<body>
	<header class="site-header" id="header">
		<h1 class="site-header__title" data-lead-id="site-header-title">THANK YOU!</h1>
	</header>
	<div class="main-content">
		<i class="fa fa-check main-content__checkmark" id="checkmark"></i>
	</div>	
	<div>
	<footer class="" id="footer">
		<p class="site-footer__fineprint" id="fineprint">Copyright ©2014 | All Rights Reserved</p>
	</footer>
	</div>
	<a href="#" id="toggleButton">Order Status</a>
    
    <div id="myDiv" class="main-content">
		<?php 
		require_once 'Klarna.php';
		$klarna = new Klarna();
		$order_id = $_GET['order_id'];
		$GLOBALS['order_id'];
		$results = $klarna->checkOrderStatus($order_id);
		echo '<table border="1">'; // Start of table
		echo '<tr><th>Details</th><th>Values</th></tr>'; 
		foreach($results as $key => $result){
			echo '<tr>';
			echo '<td>' . htmlspecialchars($key) . '</td>';
			
			if (isset($result)) {
				if (is_array($result)) {
					
					$flattenedResult = array_map(function ($item) {
						
						return is_array($item) ? implode(',', $item) : $item;
					}, $result);
			
					
					echo '<td>' . htmlspecialchars(implode(',', $flattenedResult)) . '</td>';
				} else {
					
					echo '<td>' . htmlspecialchars($result) . '</td>';
				}
			} else {
				
				echo '<td></td>';
			}
			
			
		}
		?>
    </div>

</body>
<script>
        document.getElementById('toggleButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default action of the anchor tag
            var div = document.getElementById('myDiv');
            if (div.style.display === 'none') {
                div.style.display = 'block'; // Show the div
                this.textContent = 'Close Status'; // Change the link text
            } else {
                div.style.display = 'none'; // Hide the div
                this.textContent = 'Order Status'; // Change the link text
            }
        });
    </script>
</html>