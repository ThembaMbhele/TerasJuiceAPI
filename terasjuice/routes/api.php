<?php

require_once(dirname(__FILE__) . '/2checkout/2checkout-php-master/lib/Twocheckout.php');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mailgun\Mailgun;
//use Mds\Mds;
//use PDF;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/delivery', function()
{
	require_once(dirname(__FILE__) .'/mds/collivery/src/Mds/Collivery.php');
	
	$config = array(
	'app_name'      => 'Default App Name', // Application Name
	'app_version'   => '0.0.1',            // Application Version
	'app_host'      => '', // Framework/CMS name and version, eg 'Wordpress 3.8.1 WooCommerce 2.0.20' / 'Joomla! 2.5.17 VirtueMart 2.0.26d'
	'app_url'       => '', // URL your site is hosted on
	'user_email'    => 'api@collivery.co.za',
	'user_password' => 'api123',
	'demo'          => false,
	);

	$collivery = new Collivery($config);

	$towns = $collivery->getTowns();

	return print_r( $towns );
	});

Route::post('/sendMail', function(Request $request)
{
	require_once(dirname(__FILE__) .'/..'.'/vendor'.'/autoload.php');
	
	$requestData = $request->json()->all();
	$data = array("quantity"=>$requestData['quantity']);
	
	//return $data['quantity'];

	$mgClient = new Mailgun('f463abb2c00ae80d856ec36c280696fc-47317c98-9a0d58cc');
	$domain = "sandboxd2e6e174058d43f7af1f8e0fcb791d07.mailgun.org";
	
	 PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('test',compact('data'))->save(public_path().'test.pdf');
	

	$result = $mgClient->sendMessage("$domain",
          array('from'    => 'Mailgun Sandbox <postmaster@sandboxd2e6e174058d43f7af1f8e0fcb791d07.mailgun.org>',
                'to'      => 'Themba Mbhele <mbhelethemba4@gmail.com>',
                'subject' => 'Hello Themba Mbhele',
                'text'    => 'Invoice email')/*,
		array('attachment' => array(public_path().'test.pdf'))*/);
		
		unlink(public_path().'test.pdf');
	
	return json_encode($result);
	
});

Route::post('/sms/send/', function(\Nexmo\Client $nexmo, Request $request){
	$data = $request->json()->all();
	$partNumber = substr($data['number'], 1, 9);
	$number = "27".$partNumber;
	$message = $nexmo->message()->send([
		'to' => $number,
		'from' => "NEXMO",
		'text' => $data['reference']
	]);
});

Route::post('/charge', function(Request $request)
{
	$data = $request->json()->all();
	//return $data['cardNumber'];
	$amount = strval(470 * $data['quantity']);
	//return 0;
	/*Peach Payments*/
	$url = "https://test.oppwa.com/v1/payments";
	$paymentData = "authentication.userId=8a82941763da0a720163edd2d1033ca4" .
								 "&authentication.password=QxgXMpxje4" .
								 "&authentication.entityId=8a82941763da0a720163edd39bb33caa" .
								 "&amount=1000".
								 "&currency=ZAR" .
								 "&paymentBrand=VISA" .
								 "&paymentType=DB" .
								 "&card.number=4242424242424242" .
								 "&card.holder=Themba Mbhele" .
								 "&card.expiryMonth=11" .
								 "&card.expiryYear=2020" .
								 "&card.cvv=123".
								 "&shopperResultUrl=https://testingmyapi.com";



	 $charge = curl_init();
	 curl_setopt($charge, CURLOPT_URL, $url);
	 curl_setopt($charge, CURLOPT_POST, 1);
	 curl_setopt($charge, CURLOPT_POSTFIELDS, $paymentData);
	 curl_setopt($charge, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
	 curl_setopt($charge, CURLOPT_RETURNTRANSFER, true);

	 $responseData = curl_exec($charge);

	 if(curl_errno($charge)) {
		return curl_error($charge);
	}
	curl_close($charge);
	return $responseData;
});

Route::post('/cardPayment', function(Request $request)
{
	$requestData = $request->json()->all();
		
	$cardnumber = $requestData['cardnumber'];
		//return json_encode($cardnumber);

	ini_set('display_errors', 1);
	ini_set('default_socket_timeout', 300);
	$result   = '';
	$err      = '';

	session_name('paygate_payhost_testing_sample');
	session_start();
	/**
		 *  disabling WSDL cache
		 */
		ini_set("soap.wsdl_cache_enabled", "0");

		/*
		 * Using PHP SoapClient to handle the request
		 */
		$soapClient = new SoapClient('https://secure.paygate.co.za/PayHost/process.trans?wsdl', array('trace' => 1)); //point to WSDL and set trace value to debug
		require_once(dirname(__FILE__) .'/..'.'/vendor'.'/autoload.php');
	
	
		$cvv = $requestData['cvv'];
		$cardExpiryDate = $requestData['cardExpiryDate'];
		$amount = $requestData['amount'];
		/*$budgetPeriod = $requestData['BudgetPeriod'];
		$title = $requestData['title'];
		$lastName = $requestData['lastName'];
		$firstName = $requestData['firstName'];*/
		
		$singlePaymentRequest = '<ns1:SinglePaymentRequest>
    <ns1:CardPaymentRequest>
        <ns1:Account>
           <ns1:PayGateId>10011072130</ns1:PayGateId>
            <ns1:Password>test</ns1:Password>
        </ns1:Account>
        <ns1:Customer>
            <!-- Optional: -->
            <ns1:Title>{$title}</ns1:Title>
            <ns1:FirstName>Calvin</ns1:FirstName>
            <ns1:LastName>Mogodi</ns1:LastName>
            <!-- Zero or more repetitions: -->
            <ns1:Telephone></ns1:Telephone>
            <!-- Zero or more repetitions: -->
            <ns1:Mobile></ns1:Mobile>
            <!-- Zero or more repetitions: -->
            <ns1:Fax></ns1:Fax>
            <!-- 1 or more repetitions: -->
            <ns1:Email>itsupport@paygate.co.za</ns1:Email>
        </ns1:Customer>
        <ns1:CardNumber>4578965002272936</ns1:CardNumber>
        <ns1:CardExpiryDate>042021</ns1:CardExpiryDate>
        <!-- 0 or more repetitions: -->
        <!--  <ns1:CardIssueDate>?</ns1:CardIssueDate>  -->
        <ns1:CVV>2936</ns1:CVV>
        <ns1:BudgetPeriod>0</ns1:BudgetPeriod>
        <!-- 3D secure redirect object -->
        <ns1:Redirect>
            <ns1:NotifyUrl>http://gatewaymanagementservices.com/ws/gotNotify.php</ns1:NotifyUrl>
            <ns1:ReturnUrl>http://www.gatewaymanagementservices.com/payhost/result.php</ns1:ReturnUrl>
        </ns1:Redirect>
        <ns1:Order>
            <ns1:MerchantOrderId>pgtest_20171130100029</ns1:MerchantOrderId>
            <ns1:Currency>ZAR</ns1:Currency>
            <ns1:Amount>1</ns1:Amount>
        </ns1:Order>
    </ns1:CardPaymentRequest>
</ns1:SinglePaymentRequest>';

		try{
			/*
			 * Send SOAP request
			 */
			$result = $soapClient->__soapCall('SinglePayment', array(
				new SoapVar($singlePaymentRequest, XSD_ANYXML)
			));
			
			
			
			return json_encode($result);
		} catch(SoapFault $sf){
			/*
			 * handle errors
			 */
			$err = $sf->getMessage();
			return  json_encode($err);
		}
	
});
