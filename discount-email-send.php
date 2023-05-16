<?php
header("Access-Control-Allow-Origin: *");

//install PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once './vendor/phpmailer/phpmailer/src/Exception.php';
require_once './vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once './vendor/phpmailer/phpmailer/src/SMTP.php';

//install composer and omnisend
require_once './vendor/autoload.php';
require_once './vendor/omnisend/php-sdk/src/Omnisend.php';

//get ajax varaible value
$email = 'xxx@gmail.com';
$phone =  '+91888xxxxx';
$car = 'AUDI/XX';
$enotification = 'true';
$mnotification = 'false';
$subject =  'Discount code';
$bodyline1 =  'Please use this dicount code';
$showhomepagelink =  'true';

$SMTP_HOST = 'smtp.xxx.com';
$SMTP_PASSWROD = 'xxxx';
$SMTP_PORT = 587;
$SMTP_USERNAME = 'user@gmail.com';    

date_default_timezone_set('America/Los_Angeles');
$date = date('Y-m-d\TH:i:s').'+00:00';
$discount_array = [];

$shopifyAPIData = array(
    'ShopUrl' => 'xxxxxxx.myshopify.com',
    'ApiKey' => 'xxxxxxxx',
    'SharedSecret' => 'xxxx',
    'Password' => 'xxxxxx'
);

$shopify = new PHPShopify\ShopifySDK($shopifyAPIData);

$discount_int = rand(0,9);
$discount_code = array("discount1","discount2","discount3");
$discount_rl = $discount_code[$discount_int ];

$final_discount_codes = $discount_rl;

//set price_rule_id 
$price_rule_id  = '00xxx';
$discount_title = $final_discount_codes;

$discount_code = $shopify->PriceRule($price_rule_id)->DiscountCode()->post([      
"code" => $discount_title   
]);

if($enotification == 'true'){ 
   $email_value = 'subscribed';   
}else{
  $email_value = 'nonSubscribed';
}

if($mnotification == 'true'){ 
   $phone_value = 'subscribed';   
}else{
  $phone_value = 'nonSubscribed';
} 

$omnisenddata = new Omnisend('xxxxxxx-xxxx-xxxxx');

$contacts = $omnisenddata->post(
  'contacts',
   array(        
        "email" => $email,   
        "phone" => $phone,
        "tags" => [
            $car  
        ],
        "identifiers" => array(
            [  
                "type" => "email",
                "id" => $email,
                "channels" => array(
                 "email" => array(
                      "status" => $email_value,
                      "statusDate" => $date
                    )
                )                         
            ],
            [  
                "type" => "phone",
                "id" => $phone,
                "channels" => array(
                 "sms" => array(
                      "status" => $phone_value,
                      "statusDate" => $date
                    )
                )
            
            ]
        )                      
    )
);

if ($contacts) {        
    $returndata['success'] = 'true'; 
    $returndata['discount_code'] = $final_discount_code;

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();      
        $mail->Host       = $SMTP_HOST;  
        $mail->SMTPAuth   = true;  
        $mail->Username   = $SMTP_USERNAME;  
        $mail->Password   = $SMTP_PASSWROD;
        $mail->SMTPSecure = 'tls';   
        $mail->Port       = $SMTP_PORT;
        $mail->setFrom('xxx@gmail.com', 'Lorem lorem');
        $mail->addAddress($email); 
        $mail->addReplyTo('xxx@gmail.com', 'Lorem lorem');   
        $mail->isHTML(true); 
        $mail->Subject = $subject;       
        $mail->Body    = $bodyline1.'<br><br>';
        $mail->Body    .= $bodyline2.' <h2>'.$final_discount_code.'</h2>';
        
        if($bodyline3 != ''){
          $mail->Body    .= $bodyline3.'<br><br>';
        }

        if($showhomepagelink == 'true'){
          $mail->Body    .= 'Lorem lorem';
        }    
        
        $mail->send();
        //    echo 'Message has been sent';
        //    echo '<br>';
    } catch (Exception $e) {
      //  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
     //   echo '<br>';
    }

    array_push($discount_array,$returndata);
    echo json_encode($discount_array);
} else {  
    print_r($omnisend->lastError());
    $returndata['success'] = 'false'; 
    $returndata['discount_code'] = ''; 
    array_push($discount_array,$returndata);
    echo json_encode($discount_array);
}
?>
