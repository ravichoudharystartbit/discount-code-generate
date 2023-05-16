#This code will help to generate the dynamic discound code and send to customer by phpmailer. We are using 3APIs(ShopifySDK, Omnisend and PHPmailer) to create and send discount code. You need to installl all these APIs on your PHP server.

a) You need to install PHP SHopifySDK on you PHP server.

	composer require phpclassic/php-shopify

b) You need to install PHP Omnisend on your PHP server.
	
	composer require omnisend/php-sdk

c) You need to install PHPmailer on your PHP server.	

d) You can get the ajax varaible value according to your requirement. You can send all varaibles value from ajax on your PHP server URL.

e) You can create a dynamic discount code using Shopify PHPSDK and Omnisend APIs.
		$discount_code = $shopify->PriceRule($price_rule_id)->DiscountCode()->post([      
		"code" => $discount_title   
		]);

	//Omnisend 
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

c) You can send email using phpmailer to register customer.
