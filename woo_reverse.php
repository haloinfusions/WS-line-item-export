/*


{
    "external_id_seller": "string",
    "external_id_buyer": "string",
    "ext_acct_id": "string",
    "created_on": "2019-10-24T18:10:37Z",
    "buyer": 0,
    "seller": 0,
    "customer": {
        "id": 0,
        "ext_acct_id": "string",
        "delinquent": true
    },
    "brand": 0,
    "status": "string",
    "manual": true,
    "discount_amount": 0,
    "discount_type": "string",
    "tax_amount": 0,
    "tax_type": "string",
    "shipping_charge": {
        "amount": "string",
        "currency": "string"
    },
    "total": {
        "amount": "string",
        "currency": "string"
    },
    "payment_due_date": "2019-10-24T18:10:37Z",
    "paid": true,
    "paid_date": "2019-10-24",
    "ship_date": "2019-10-24T18:10:37Z",
    "shipping_details": "string",
    "notes": "string",
    "internal_notes": "string",
    "delivery_preferences": "string",
    "line_items": [
    {
        "id": 0,
        "ordered_unit_price": {
            "amount": "string",
            "currency": "string"
        },
        "quantity": "string",
        "order": "string",
        "product": "string",
        "notes": "string",
        "is_sample": true,
        "sale_price": {
            "amount": "string",
            "currency": "string"
        },
        "unit_multiplier": 0,
        "is_medical_line_item": true,
        "is_packed": true,
        "packed_at": "2019-10-24T18:10:37Z",
        "packed_by": 0,
        "marked_for_deletion": true,
        "position": 0,
        "bulk_units": 0,
        "on_sale": true,
        "is_backorder": true,
        "batch": 0
    }
    ],
    "classification": "string",
    "modified": "2019-10-24T18:10:37Z",
    "has_special_requests": true,
    "delivery_provider": 0,
    "order_taxes": [
    {
        "order": 0,
        "tax": 0
    }
    ],
    "delivery_info": {
        "acquire_date": "2019-10-24T18:10:37Z",
        "acquire_type": 1,
        "manifest_number": "string"
    }
}

 This is going to be hard to do. Given the fact that we do not have a way to compare the incoming request with the customer id.
 

*/
<?php
    include "creds.php";
    include "line_item_search.php";
    include "woo_leaf_load.php";
    include "product_format.php";
    include "json_update.php";
    include "order_tracking.php";
    require __DIR__ . '/vendor/autoload.php';
    use Automattic\WooCommerce\Client;
    $url =  constant("woocommerce_site");
    
    //startsWith just returns the first character
    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
    
    use Automattic\WooCommerce\HttpClient\HttpClientException;
    
    $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    
    //first we pull the new woocommerce based request.
    $sql = "select * from json2 where parsed = '0' and response !='null';";
    // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
    
    $result = $conn->query($sql);
    //var_dump($result);
    //Iterate through the records if there is more than one
    // With the limit in place, we should be able to only have a single order record being processed at a time.
    
    if ($result->num_rows > 0) {
        echo "result being parsed";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //Checking to see if the parsed flag has been modified after creation
            //The parsed flag should only be changed to 1 after the order creation sucess response is returned from WOO.
            $decode = $row["response"];
            //$products = [];
            $response_id = $row["jsonID"];
            
            
            echo "\n";
            var_dump($decode);
            echo "response id this is generated from is as follows".$response_id."\n\n";
            $dump = substr($decode, 2, -2);
            var_dump($dump);
            $json = json_decode($decode,true);
            var_dump($json);
            
            
            
            
            $enc = mb_detect_encoding($decode);
            
            if($enc == 'UTF-8') {
                $response = preg_replace('/[^(\x20-\x7F)]*/','', $decode);
            }
            echo "<pre>";
            print_r(json_decode($decode,true));
            if (startsWith( $decode, '"' )){
                $decode = substr($decode, 1);
                $decode = substr($decode, 0, -1);
                echo "decode start is ".$decode;
            }
            
            if (startsWith( $decode, '"' )){
                $decode = substr($decode, 1);
                $decode = substr($decode, 0, -1);
                echo "decode start is ".$decode;
            }
            
            
            $json = json_decode($decode, true);
            
            
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    echo ' - No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    echo ' - Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    echo ' - Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    echo ' - Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    echo ' - Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    echo ' - Unknown error';
                    break;
            }
            
            
            
            if (empty($json)){
                echo " Json Response value is null\n";
                woo_json_update($response_id);
            }
            else{
                echo "decoded array response\n";
                print_r($json);
                
                
            }
        }
    }
    
    ?>
        /*
            else{
         
                //Decode the json response
                $json =  json_decode($decode, true);
                var_dump($json);
        

                $display_number = $json['data']['external_id_seller'];
                $order_exists =  is_order($display_number);
                    //if the order doesn't exsit. then allow it be createdsud
                if (!$order_exists){
                    woo_order_update($json,  $response_id);
                }
                else{
                    woo_json_update($response_id);
                }
                    
                    
                    
                
                //order edits - Order update API call
                
            }
        }
    }
    else {
        echo "0 new responses";
    }
    
    $conn->close();
    
    function woo_order_update($json, $response_id, $order_number){
        $curl = curl_init();
        
        //echo $order_num;
        curl_setopt_array($curl, array(
                                       CURLOPT_URL => "https://leaflink.com/api/v2/orders-received/".$order_num."/",
                                       CURLOPT_RETURNTRANSFER => true,
                                       CURLOPT_ENCODING => "",
                                       CURLOPT_MAXREDIRS => 10,
                                       CURLOPT_TIMEOUT => 30,
                                       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                       CURLOPT_CUSTOMREQUEST => "GET",
                                       CURLOPT_HTTPHEADER => array(
                                                                   "Accept: */*",
                                                                   "Accept-Encoding: gzip, deflate",
                                                                   "Authorization: Token 9bc76e3e6e377194eee3d2c97a4c20f60573aee6",
                                                                   "Cache-Control: no-cache",
                                                                   "Connection: keep-alive",
                                                                   "Host: leaflink.com",
                                                                   "Postman-Token: 4910b3e7-d2eb-4e39-b558-6b2aaa00d754,f9d96a88-9fde-4ff6-b00a-ba2be345ef8f",
                                                                   "User-Agent: PostmanRuntime/7.16.3",
                                                                   "cache-control: no-cache"
                                                                   ),
                                       ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } //else {
        //echo $response;
        //}
        $product_line_items = array();
        $line_items = json_decode($response, true);
        //go into nest json response
        $line_items = $line_items['results'];
        $lines = sizeof($line_items);
        for($i = 0; $i < $lines; $i++){
            $product_line_items[$line_items[$i]['id']] = $line_items[$i]['product'];
        }
        var_dump($product_line_items);
        return $product_line_items;
        
        
    }
    


