<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<style>
			table 
			{
			    font-family: arial, sans-serif;
			    border-collapse: collapse;
			    width: 100%;
			}
			
			.invoiceInfo-1
			{
				width: 400px;
				float: left;
				margin-left: 10px
			}
			
			.invoiceInfo-2
			{
				float: left
			}
		</style>
	</head>
	<body>
		<div>
			<img width='100' height='100'src="/var/www/html/TerasJuice/terasjuice/resources/imgs/Teras_Herbal_Juice_Logo.png">
		</div>
		<div style="height:195px">
			<div class="invoiceInfo-1">
				<h3>Payment Invoice</h3>
				<div style="margin-left:15px;margin-top:-5px">
					<p>BC419, The Fields<br>1066 Burnett Street<br>Hatfield, 0028<br>Pretoria<br>South Africa</p>
				</div>
			</div>
			<div class="invoiceInfo-2">
				<div>
					<p><b>Invoice Date:</b> 22 June 2018</p>
					<p><b>Account Number:</b> 62689268300</p>
					<p><b>Invoice Number:</b>gshhjsd</p>
				</div>
			</div>
		</div>
		<div>
			<table style="width:100%">
				<tr style="border-bottom: 1px solid black">
					<th style="width:400px;border-bottom:1px solid black">Description</th>
					<th style="border-bottom:1px solid black;padding:0px">Quantity</th>
					<th style="border-bottom:1px solid black">Unit Price</th>
					<th style="border-bottom:1px solid black">Amount (ZAR)</th>
				</tr>
				@foreach($data as $requestData)
				<tr>
					<td>Teras Juice Bottle (750ml)</td>
					<td>{{$requestData}}</td>
					<td>450</td>
					<td>{{$requestData * 450}}</td>
				</tr>
				@endforeach
			</table>
			<!--<div style="margin-left:400px;width:100%">
				<div>
					<div style="float:left">
						<p>Extra (description)<p>
					</div>
					<div style="float:left">
						<p style="margin-left:90px">350</p>
					</div>
				</div>
			</div>
			<div style="margin-left:400px;width:100%;border-bottom: 1px solid black">
			</div> -->
		</div>
	</body>
</html>