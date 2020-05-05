 <?php
     
     /*
      Program Overview
      
      This program is meant to convert new orders placed into Leaflink automatically into Woocommerce.
      
      A json response is sent from woocommerce via a webhook every time a new order is placed or an order is updated. These json responses are stored in a database for later parsing.
      
      This program is meant to parse the leaflink order information into the same order in our woocommerce site. The conversion_handler file is the primary execution file, with calls to many other smaller scripts.
      
      A general overview of execution is as follows.
      1. Load the supporting files and creditials into memory. This program is designed to run in a lamp server on AWS.
      2. Initialize a local database connection to pull a single non-null leaflink order.
      3. Parse the json order information for a dynamic sized product set and identifying order information
      4. For each product line on a Leaflink order, we only have access to line-item ID instead of product ID. Line items Ids are order specific, while product ID are the unique entities relating to singular products. We call the function line_item_search() loaded from line_item_search.php which takes an order number and calls each of the product id's attached to the line items in that order. These productId's and there corresponding lineItemId's are returned as a keyed array called $product_line_items.
      5. Pull a key based array from the woo_leaf_load() function in "woo_leaf_load.php" that contains woocommerce product ids and their corresponding leaflink product ID equivalents for later look-up. This information is loaded from a manually-created and maintained database table called leaf_woo that stores this relationship. If these references are incorrect then the product will show up null or with the wrong product in woocommerce.
      6. Product_format is a function stored in the product_format.php file. It takes the order product information, looks up the leaflink line item id with the key based array returned from line_item_search() function. Then we use the key based array loaded from woo_leaf_load() to find the product id of each woocommerce product based on their life link id. This function is iterative and also parse the sku attached to each product to determine dominance. This information is then formatted into an array.
      7. The product formatted array is then embedded into an array for the overall order to be inserted into Woocommerce.
      8. The order is then sent to woocommerce to be inserted
      9. If the order is inserted, then we then update the original webhook response that the json response has been parsed.
      
      
      //json webhook response setup
      CREATE TABLE `json2` (
      `jsonID` int(11) NOT NULL AUTO_INCREMENT,
      `response` varchar(10000) DEFAULT NULL,
      `parsed` tinyint(1) DEFAULT NULL,
      PRIMARY KEY (`jsonID`)
      ) ENGINE=InnoDB AUTO_INCREMENT=672 DEFAULT CHARSET=utf8
      
      
      Dependencies.
      
      This uses the WP PHP API
      https://github.com/woocommerce/wc-api-php
      We have PHP curl isntalled running on the lamp server
      
      The webhooks are set on the leaflink settings and point to webhook_handler.php
      
      To allow external HTTPS post request into Woo you must edit htaccess and httpd-app.conf
      you must allow for basic authentication to flow through.
      https://stackoverflow.com/questions/38610599/woocommerce-rest-api-woocommerce-rest-cannot-create
      https://github.com/WP-API/Basic-Auth/issues/35
      https://github.com/woocommerce/woocommerce/issues/10829
      
      */
     
    //Loads credentials and supporting files
    include "creds.php";
    include "line_item_search.php";
    include "woo_leaf_load.php";
    include "product_format.php";
    include "json_update.php";
    include "order_tracking.php";
    //include "pull_patient_details.php";
    //include "woo_customer_id_lookup.php";
     
     
     $shipping = 0;
    //Loads Woo Client
    require __DIR__ . '/vendor/autoload.php';
    use Automattic\WooCommerce\Client;
    $url =  constant("woocommerce_site");
    
    use Automattic\WooCommerce\HttpClient\HttpClientException;
    
    $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    
     //helper function to test if the json response has an extra "" attached.
     function startsWith ($string, $startString)
     {
         $len = strlen($startString);
         return (substr($string, 0, $len) === $startString);
     }

    
    $sql = "Select * from json where parsed ='0' and response !='null' limit 1;";
    // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
    $result = $conn->query($sql);
    $response_id = 0;
	//Iterate through the records if there is more than one
    // With the limit in place, we should be able to only have a single order record being processed at a time.
     if ($result->num_rows > 0) {
         // output data of each row
         while($row = $result->fetch_assoc()) {
             
             //Checking to see if the parsed flag has been modified after creation
             //The parsed flag should only be changed to 1 after the order creation sucess response is returned from WOO.
             $decode = $row["response"];
             $products = [];
             $response_id = $row["jsonID"];
             
             
             if (startsWith( $decode, '"' )){
                 $decode = substr($decode, 1);
                 $decode = substr($decode, 0, -1);
                 //echo "decode start is ".$decode;
             }
             if (empty($decode)){
                 echo "response is null";
                 json_update($response_id);
             }
             else{
                 //Decode the json response
                 //echo "json will be";
                 //var_dump($decode);
                 $json =  json_decode($decode, true);
                 //var_dump($json);
                 $ordertype = $json['action'];
                 $orderstatus = $json['data']['status'];
                 
                 
                 //filter out and delete if the order was deleted from leaflink
                 
                 
                 
                 //var_dump($json);
                 echo "order status is ".$orderstatus;
                 echo "order type is ".$ordertype;
                 var_dump($orderstatus == 'Cancelled');
                 if ($orderstatus == 'Cancelled'){
                     $display_number = $json['data']['external_id_seller'];
                     $order_number =  is_order($display_number);
                     woo_cancel_order($order_number,$response_id);
                     json_update($response_id);
                     
                 }
                 //if the order type is creation
                 if ($ordertype  == 'create'){
                     
                     //filter out the non-submitted calls. There is a submitted and accepted create response. We only want the initial.
                     $order_status = $json['data']['status'];
                     $submitted = strcmp("Submitted", $order_status);
                     echo
                     $submitted;
                     var_dump($submitted);
                     
                     if ($submitted != 0){
                         json_update($response_id);
                         exit();
                     }
                     $display_number = $json['data']['external_id_seller'];
                     $order_exists =  is_order($display_number);
                     //if the order doesn't exsit. then allow it be createdsud
                     if (!$order_exists){
                         woo_conversion_create($json,  $response_id);
                     }
                     else{
                         json_update($response_id);
                     }
                     
                     
                     
                 }
                 //order edits - Order update API call
                 else{
                     
                     
                     $display_number = $json['data']['external_id_seller'];
                     $order_number =  is_order($display_number);
                     //if the order doesn't exsit. then allow it be createdsud
                     if ($order_number){
                         woo_conversion_update($json,  $response_id, $order_number);
                     }
                     else{
                         json_update($response_id);
                     }
                     
                     
                     echo "This is just an order update.";
                     //var_dump($json);
                     json_update($response_id);
                     exit();
                     
                     
                     //woo_conversion_create($json);
                     //woo_conversion_create($json, $response_id);
                 }
             }
         }
     }
     else {
         echo "0 new responses";
     }
    
    $conn->close();
    //echo sizeof($products);

    
    function woo_conversion_create($json, $response_id){
        $orders = $json['data']['orderedproduct_set'];
        $orderCount = sizeof($orders);
        $product_set = array();
        
        $total = 0;
        //Build product set
        for ($i = 0; $i < $orderCount; $i++){
            $order = $orders[$i];
            $product_id = $order['id'];
            $product_sku =  $order['product']['sku'];
            $product_qty =  $order['quantity'];
            $product_price =  $order['ordered_unit_price'];
            $unit_multiplier = $order['unit_multiplier'];
            $line_item_price =($product_qty/$unit_multiplier) * $product_price;
            $total += $line_item_price;
            //echo "product_id:".$product_id."\n";
            $product_set = array($product_sku,  $product_qty, $product_price, $product_id);
            //print_r ($product_set);
            $products[$i][] = $product_set;
            //echo sizeof($product_set);
        };
        
        //echo $total;
        
        
        //retrieve customer data
        $order_number = $json['data']['number'];
        $display_number = $json['data']['external_id_seller'];
        $customer = $json['data']['customer'];
        print_r($customer);
        $name = $customer['name'];
        $zipcode = $customer['zipcode'];
        $address = $customer['address'];
        $email = $customer['email'];
        $license_number = $customer['license_number'];
        $city= $customer['city'];
        $phone = $customer['phone'];
        //Not working properly, unable to query woo based on user email
        //$customer_id = $customer['id'];
        $customer_email = $json['data']['user'];
        
        //Sometimes leaflink won't provide a full email, only username
        if (strpos($customer_email, '@') == false){
            $customer_email = $customer_email."@noemail-leaflink.com";
            }
        //var_dump($json);
        //leaflink echo $customer_id;
       // $customer_id = return_customer_id($customer_email);
        
        //filter out the statuses that may be duplicate. We recieve a submitted response and a confirmed response. We only want to parse the submitted as the completed has a null status.
        
        
        //A simple funcition to sanitize each input
        function encapsulate ($str){
            $newstr =$str;
            $fstr = trim($newstr, '"');
            return $fstr;
        }
        
        
        //Format the order information with '' for outbound json to woocommerce
        $order_number = encapsulate($order_number);
        //split name formatting, some may have a business title with multiple space
        $names = explode(" ", $name);
        if (sizeof($names)>2){
            $fname = encapsulate($name);
            $lname = "";
        }
        else{
            $fname = encapsulate($names[0]);
            $lname = encapsulate($names[1]);
        }
        $zip = encapsulate($zipcode);
        $address =  encapsulate($address);
        $email = encapsulate($email);
        $license_number = encapsulate($license_number);
        $city = encapsulate($city);
        $phone = encapsulate($phone);
        
        
        //$customer_email = customer_contact_pull($customer_id);
        
        //If email or phone number weren't provide on order level information, pull generic information.
        //echo "Customer is as follows: ";
        //echo $customer_email;
        if ($customer_email  == ''){
            $customer_email  = "peterjmace@gmail.com";
        }
        if ($phone == ''){
            $phone = "(000) 000-0000";
        }
        
        //Formatting Shipping charge
        if ($total > 750){
            $shipping = 1;
            $shipping_type = 'Free Delivery';
            $shipping_value = "free_shipping:0";
        }else{
            $shipping = 1;
            $shipping_type = 'Delivery Fee (Orders under $750)';
            $shipping_value = "free_shipping:50";
        }
        
        
        // This takes an order number, pulls the line items and returns them as array named product_line items
        $product_line_items = line_item_search($order_number);
        
        //woo_leaf_array() pull a key based array with each of the product ID in leaflink and their wocommerce equivalent.
        $woo_lookup =  woo_leaf_array();
        
        
        $product_payload =  product_format($products, $product_line_items, $woo_lookup);
    
        
            $data = [
            'status' => 'pending',
            'payment_method' => 'Cash on Delivery',
            'payment_method_title' => 'Cash on Delivery',
            'set_paid' => 'false',
            'billing' => [
            'first_name' =>  $fname,
            'last_name' => $lname,
            'address_1' => $address,
            'address_2' => '',
            'city' => $city,
            'state' => 'AZ',
            'postcode' => $zip,
            'country' => 'US',
            'email' => $customer_email,
            'phone' => $phone
            ],
            'shipping' => [
            'first_name' => $fname,
            'last_name' => $lname,
            'address_1' => $address,
            'address_2' => '',
            'city' => $city,
            'state' => 'AZ',
            'postcode' => $zipcode,
            'country' => 'US'
            ], 'line_items' => $product_payload,
            'shipping_lines' => [
            [
            'method_id' => '40339',
            'method_title' => $shipping_type,
            'total' =>  $shipping_value
            ]
            ],
        'customer_note' => "This order was automatically generated from Leaflink Order: ".$display_number." "
            ];
        
        
        
        // Print Payload
        //var_dump($data);
        
        $url =  "https://52.35.154.161.xip.io";
        sleep(5);
        $woocommerce = new Client(constant("woocommerce_site"), constant("consumer_key"), constant("consumer_secret"));
        if ($woocommerce){
            try {
                // Array of response results.
                $responded = $woocommerce->post('orders', $data);
                //sleep(10);
                //var_dump($responded);
                $woo_post_id = $responded->id;
                $woo_order_id = $responded->number;

                echo "Woocommerce order id is ".$woo_order_id;
                
                
                json_update($response_id);
                //echo "Original Record should be completed";
                $customer_number = $json['data']['external_id_buyer'];
                
                //This function updates woocommerce with order_track table information. This should be separate from the shipping total, and a date object should be created when needed.
                //
                
                order_added($display_number, $order_number, $customer_number, $total, $woo_order_id, $woo_post_id, $shipping);
            
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
            
            
        
         
        }
        
    }
    
     
     
     function woo_conversion_update($json, $response_id, $order_number){
         $orders = $json['data']['orderedproduct_set'];
         $orderCount = sizeof($orders);
         $product_set = array();
         
         $total = 0;
         //Build product set
         for ($i = 0; $i < $orderCount; $i++){
             $order = $orders[$i];
             $product_id = $order['id'];
             $product_sku =  $order['product']['sku'];
             $product_qty =  $order['quantity'];
             $product_price =  $order['ordered_unit_price'];
             $unit_multiplier = $order['unit_multiplier'];
             $line_item_price =($product_qty/$unit_multiplier) * $product_price;
             $total += $line_item_price;
             //echo "product_id:".$product_id."\n";
             $product_set = array($product_sku,  $product_qty, $product_price, $product_id);
             //print_r ($product_set);
             $products[$i][] = $product_set;
             //echo sizeof($product_set);
         };
         
         //echo $total;
         
         
         //retrieve customer data
         $order_number = $json['data']['number'];
         $display_number = $json['data']['external_id_seller'];
         $customer = $json['data']['customer'];
         print_r($customer);
         $name = $customer['name'];
         $zipcode = $customer['zipcode'];
         $address = $customer['address'];
         $email = $customer['email'];
         $license_number = $customer['license_number'];
         $city= $customer['city'];
         $phone = $customer['phone'];
         //Not working properly, unable to query woo based on user email
         //$customer_id = $customer['id'];
         $customer_email = $json['data']['user'];
         
         //Sometimes leaflink won't provide a full email, only username
         if (strpos($customer_email, '@') == false){
             $customer_email = $customer_email."@noemail-leaflink.com";
         }
         //var_dump($json);
         //leaflink echo $customer_id;
         // $customer_id = return_customer_id($customer_email);
         
         //filter out the statuses that may be duplicate. We recieve a submitted response and a confirmed response. We only want to parse the submitted as the completed has a null status.
         
         
         //A simple funcition to sanitize each input
         function encapsulate ($str){
             $newstr =$str;
             $fstr = trim($newstr, '"');
             return $fstr;
         }
         
         
         //Format the order information with '' for outbound json to woocommerce
         $order_number = encapsulate($order_number);
         //split name formatting, some may have a business title with multiple space
         $names = explode(" ", $name);
         if (sizeof($names)>2){
             $fname = encapsulate($name);
             $lname = "";
         }
         else{
             $fname = encapsulate($names[0]);
             $lname = encapsulate($names[1]);
         }
         $zip = encapsulate($zipcode);
         $address =  encapsulate($address);
         $email = encapsulate($email);
         $license_number = encapsulate($license_number);
         $city = encapsulate($city);
         $phone = encapsulate($phone);
         
         
         //$customer_email = customer_contact_pull($customer_id);
         
         //If email or phone number weren't provide on order level information, pull generic information.
         //echo "Customer is as follows: ";
         //echo $customer_email;
         if ($customer_email  == ''){
             $customer_email  = "peterjmace@gmail.com";
         }
         if ($phone == ''){
             $phone = "(000) 000-0000";
         }
         
         //Formatting Shipping charge
         if ($total > 750){
             $shipping = 0;
             $shipping_type = 'Free Delivery';
             $shipping_value = "free_shipping:0";
         }else{
             $shipping = 1;
             $shipping_type = 'Delivery Fee (Orders under $750)';
             $shipping_value = "free_shipping:50";
         }
         
         
         // This takes an order number, pulls the line items and returns them as array named product_line items
         $product_line_items = line_item_search($order_number);
         
         //woo_leaf_array() pull a key based array with each of the product ID in leaflink and their wocommerce equivalent.
         $woo_lookup =  woo_leaf_array();
         
         
         $product_payload =  product_format($products, $product_line_items, $woo_lookup);
         
         
         $data = [
         'id' => $order_number,
         'payment_method' => 'Cash on Delivery',
         'payment_method_title' => 'Cash on Delivery',
         'set_paid' => 'false',
         'billing' => [
         'first_name' =>  $fname,
         'last_name' => $lname,
         'address_1' => $address,
         'address_2' => '',
         'city' => $city,
         'state' => 'AZ',
         'postcode' => $zip,
         'country' => 'US',
         'email' => $customer_email,
         'phone' => $phone
         ],
         'shipping' => [
         'first_name' => $fname,
         'last_name' => $lname,
         'address_1' => $address,
         'address_2' => '',
         'city' => $city,
         'state' => 'AZ',
         'postcode' => $zipcode,
         'country' => 'US'
         ], 'line_items' => $product_payload,
         'shipping_lines' => [
         [
         'method_id' => '40339',
         'method_title' => $shipping_type,
         'total' =>  $shipping_value
         ]
         ],
         'customer_note' => "This order was automatically generated from Leaflink Order: ".$display_number." "
         ];
         
         
         
         // Print Payload
         //var_dump($data);
         
         $url =  "https://52.35.154.161.xip.io";
         sleep(5);
         $woocommerce = new Client(constant("woocommerce_site"), constant("consumer_key"), constant("consumer_secret"));
         if ($woocommerce){
             try {
                 // Array of response results.
                 $responded = $woocommerce->put('orders', $data);
                 //sleep(10);
                 
                 $woo_post_id = $responded->id;
                 $woo_order_id = $responded->number;

                 //echo "Original Record should be completed";
                 var_dump($responded);
                 
                 
             } catch (HttpClientException $e) {
                 
                 echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                 echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                 echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
             }
             
             
             
             
         }
         
     }
     
     
     function woo_cancel_order($order_number,$jsonID){
         
        
         sleep(10);
        $woocommerce = new Client(constant("woocommerce_site"), constant("consumer_key"), constant("consumer_secret"));
         echo $order_number." ";
         print_r($woocommerce->delete("orders/$order_number", ['force' => true]));
     }
   
        
   
    
?>

