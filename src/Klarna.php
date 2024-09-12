<?php
class Klarna{

var $site_url = "http://localhost";

// Live
/*var $base_url = "https://api.klarna.com";
var $uid = "";
var $pass = "";*/

// Test
var $base_url = "https://api-na.playground.klarna.com";
var $uid = "52486ea2-c18c-41c7-8f12-dd1441bef6f6";
var $pass = "klarna_test_api_UVI1dmQ_S1VYSXFsQlhyVyNZUHBZZSgkKUFKQmdxWUosNTI0ODZlYTItYzE4Yy00MWM3LThmMTItZGQxNDQxYmVmNmY2LDEsQzJXL0h2UzNSa3VQclczSGROWktXYyttWWZsdmVWRW53Z1ladjhBWGxQOD0";

        function __construct(){
        }

        function createSession($order){
            $url = "{$this->base_url}/payments/v1/sessions";

            // Build order_lines
            // $contents = $order->cart;
            // foreach($contents as $line){
            //     $product_price = $line->product->grossprice * 100;

            $product_price = $order*100;
           
                $order_line = array(
                    //"image_url" => "{$this->site_url}/images/250/250/{$line->product->main_image}",
                    "type" => "physical",
                    "reference" => 4298478,//$line->product->gtin,
                    "name" => "Chocolate",//$line->product->name,
                    "quantity" => 1,//$line->quantity,
                    "unit_price" => $product_price,
                    "tax_rate" => 0,
                    "total_amount" => $product_price,
                   
                    "total_tax_amount" => 0
                );

                $tax_amount = $order_line['unit_price'] * $order_line['quantity'] * ($order_line['tax_rate'] / 100);
                $total_amount_with_tax = ($order_line['unit_price'] * $order_line['quantity']) + $tax_amount;
                $order_line['total_tax_amount'] = $tax_amount;
                $order_line['total_amount'] = $total_amount_with_tax;

                $order_lines[] = $order_line;
            // }
            
            $data = array(
                "purchase_country" => "US",
                "purchase_currency" => "USD",
                "locale" => "en-US",
                "order_amount" => $product_price,
                "order_lines" => $order_lines,
                "billing_address" => array(
                    "given_name" => "Anand",//$order->firstname,
                    "family_name" => "Aditya",//$order->lastname,
                    "email" => "aditya.word3090@gmail.com",//$order->email,
                    "street_address" => "Civil",//$order->addr1,
                    "street_address2" => "Lines",//$order->addr2,
                    "postal_code" => "212601",//$order->postcode,
                    "city" => "Fatehpur",//$order->city,
                    "region" => "UP",//$order->county,
                    "phone" => "8090633408",//$order->telephone,
                    "country" => "US"//$order->country
                )
            );

            $payload = json_encode($data);

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Cache-Control: no-cache",
                "Authorization: Basic ".base64_encode("{$this->uid}:{$this->pass}")
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            curl_close($ch);

            // Process the response
            if($response === false){
                // Request failed
                echo "cURL error: " . curl_error($ch) . "<br/>";
                echo "cURL error code: " . curl_errno($ch);
            }else{
                // Request succeeded
                $result = json_decode($response, true);
                return $result;
            }
        }
        
        function createHPPSession($session_id, $token){
            $url = "{$this->base_url}/hpp/v1/sessions";
        
            $data = array(
                "payment_session_url" => "{$this->base_url}/payments/v1/sessions/$session_id",
                "merchant_urls" => array(
                    "success" => "{$this->site_url}/thank-you.php?token={$token}&sid={{session_id}}&order_id={{order_id}}",
                    "cancel" => "{$this->site_url}/shop/display-basket.html",
                    "back" => "{$this->site_url}/shop/display-basket.html",
                    "failure" => "{$this->site_url}/shop/thank-you.html?token={$token}&sid={{session_id}}",
                    "error" => "{$this->site_url}/shop/thank-you.html?token={$token}&sid={{session_id}}"
                ),
                "options" => array(
                    "place_order_mode" => "PLACE_ORDER"
                )
            );
        
            $payload = json_encode($data);
        
            $ch = curl_init($url);
        
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Cache-Control: no-cache",
                "Authorization: Basic ".base64_encode("{$this->uid}:{$this->pass}")
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
            $response = curl_exec($ch);
            curl_close($ch);
        
            // Process the response
            if($response === false){
                // Request failed
                echo "cURL error: " . curl_error($ch) . "<br/>";
                echo "cURL error code: " . curl_errno($ch);
            }else{
                // Request succeeded
                $result = json_decode($response, true);
        
                if($result['redirect_url']){
                    header("Location: {$result['redirect_url']}");
                }
            }
        }

        function checkOrderStatus($orderid){
            $url = "{$this->base_url}/ordermanagement/v1/orders/$orderid";
        
            $ch = curl_init($url);
        
            // Set options
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Cache-Control: no-cache",
                "Authorization: Basic ".base64_encode("{$this->uid}:{$this->pass}")
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, true); // Use GET method
        
            // Execute the request
            $response = curl_exec($ch);
            curl_close($ch);
        
            // Check for errors
            if($response === false){
                // Error handling
                echo "Error occurred while fetching the data: " . curl_error($ch);
            }else{
                // Process the response
        
                return json_decode($response, true);
                //print_r(json_decode($response, true));
            }
        }
}