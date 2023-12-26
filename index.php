<?php
echo "<center><h2>Paymet gateway</h2></center>";

$apiKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
$merchantId = 'PGTESTPAYUAT';
$keyIndex=1;

$redirectUrl = 'paymentsuccess.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
 
// Set transaction details
$order_id = uniqid(); 
$mobile=9999999999;
$amount = 500; 
$description = 'Payment for Service';
 
 
$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "MT7850590068188104",
    "merchantUserId"=>"MUID123",
    'amount' => $amount*100,
    'redirectUrl'=>$redirectUrl,
    'redirectMode'=>"POST",
    'callbackUrl'=>$redirectUrl,
    "merchantOrderId"=>"12345",
   "mobileNumber"=>$mobile,
   "message"=>$description,
   "email"=>$email,
   "shortName"=>$name,
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);
 
 
$jsonencode = json_encode($paymentData);
 $payloadMain = base64_encode($jsonencode);


$payload = $payloadMain . "/pg/v1/pay" . $apiKey;
$sha256 = hash("sha256", $payload);
$final_x_header = $sha256 . '###' . $keyIndex;
$request = json_encode(array('request'=>$payloadMain));


$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);
 
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
  
  //  echo "<pre>";
  //  print_r($res);
  //  echo "</pre>";
 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
echo $payUrl=$res->data->instrumentResponse->redirectInfo->url;
    echo "<a href='".$payUrl."'>Pay Now</a>;
 
//header('Location:'.$payUrl) ;
}
}
          
?>  
