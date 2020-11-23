
<html>
	<head>
		<title>API Example</title>
		<style type="text/css">
			body{
				text-align: center;
			}
			#container{
				width: 200px;
				position:absolute;
				top:50px;
				left:50%;
				margin-left:-100px;
			}
			div{
				margin-top: 10px;
			}
			select{
				width: 90%;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<h2>API Form POST</h2>
			<form method="POST" action="api/">
				<div>
					<label for="name">Order_ID:</label>
					<input type="text" name="order_id" id="name" value="12345" placeholder=""/>
				</div>
				<div>
					<label for="age">prepayment:</label>
					<input type="text" name="prepayment" id="prepayment" placeholder="" value="true"/>
				</div>
				<div>
					<label for="age">Paysum:</label>
					<input type="text" name="paysum" id="paysum" placeholder="" value="1"/>
				</div>

				<div>
					<input type="submit" value="Send" name="btn"  /> 
				</div>
			</form>
		</div>
	</body>
</html>