<?php
    
    /*interacts with the following mysql table
    create table order_track(leaf_num varchar(20), leaf_order_num varchar(20), leaf_customer_id varchar(20), order_total varchar(20), woocommerce_order_num varchar(20));
    
    //order_total returns the total of previous orders from that same customer or false if a leaflink order of the same ID exists in the system.
     
     */
    function order_added($order_number, $leaf_order_num, $leaf_customer_id, $order_total, $woocommerce_order_num, $woo_post_id, $shipping){
            try {
                // Array of response results.
                //print_r($woocommerce->post('orders', $data));
                sleep(2);

                //call encapsulate defined in the conversion_handler.php
                $order_number = encapsulate($order_number);
                echo $order_number;
                $order_number = encapsulate($order_number);
                //echo $order_number;
                $leaf_order_num = encapsulate($leaf_order_num);
                $leaf_customer_id = encapsulate($leaf_customer_id);
                $order_total = encapsulate($order_total);
                $woocommerce_order_num = encapsulate($woocommerce_order_num);

                $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
                $sql = "Insert into order_track values('".$order_number."', '".$leaf_order_num."', '".$leaf_customer_id."', '".$order_total."', '".$woocommerce_order_num."', '".$woo_post_id."', '".$shipping."', "." now()".");";
                echo $sql;
                // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
                $conn->query($sql);
                
                //***** Massive amount of debugging *****
                // Example: ['customers' => [[ 'id' => 8, 'created_at' => '2015-05-06T17:43:51Z', 'email' => ...
                //echo '<pre><code>' . print_r( $data, true ) . '</code><pre>'; // JSON output.
                
                // Last request data.
                //$lastRequest = $woocommerce->http->getRequest();
                //
                //echo '<pre><code>' . print_r( $data->getUrl(), true ) . '</code><pre>'; // Requested URL (string).
                //echo '<pre><code>' . print_r( $data->getMethod(), true ) . '</code><pre>'; // Request method (string).
                //echo '<pre><code>' . print_r( $data->getParameters(), true ) . '</code><pre>'; // Request parameters (array).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Request headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Request body (JSON).
                
                // Last response data.
                //$lastResponse = $woocommerce->http->getResponse();
                //echo '<pre><code>' . print_r( $data->getCode(), true ) . '</code><pre>'; // Response code (int).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Response headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Response body (JSON).
                
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
        
        
    }
    
    //is_order returns boolean if the order already exist in teh woo of commerce.
    function is_order($leaf_order_num){
            try {
                // Array of response results.
                //print_r($woocommerce->post('orders', $data));
                sleep(2);
                $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
                $sql = "select * from order_track where leaf_num = "."'".$leaf_order_num."';";
                echo $sql;
                $result = $conn->query($sql);
                // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
                var_dump($result->num_rows > 0);
                echo $result->num_rows;
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        //Checking to see if the parsed flag has been modified after creation
                        //The parsed flag should only be changed to 1 after the order creation sucess response is returned from WOO.
                        $response_id = $row["woo_post_id"];
                        if ($response_id){
                            return $row["woo_post_id"];
                            break;
                        }
                    }
                }
                else{
                    return false;
                }

                
                //***** Massive amount of debugging *****
                // Example: ['customers' => [[ 'id' => 8, 'created_at' => '2015-05-06T17:43:51Z', 'email' => ...
                //echo '<pre><code>' . print_r( $data, true ) . '</code><pre>'; // JSON output.
                
                // Last request data.
                //$lastRequest = $woocommerce->http->getRequest();
                //
                //echo '<pre><code>' . print_r( $data->getUrl(), true ) . '</code><pre>'; // Requested URL (string).
                //echo '<pre><code>' . print_r( $data->getMethod(), true ) . '</code><pre>'; // Request method (string).
                //echo '<pre><code>' . print_r( $data->getParameters(), true ) . '</code><pre>'; // Request parameters (array).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Request headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Request body (JSON).
                
                // Last response data.
                //$lastResponse = $woocommerce->http->getResponse();
                //echo '<pre><code>' . print_r( $data->getCode(), true ) . '</code><pre>'; // Response code (int).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Response headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Response body (JSON).
                
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
        
    }
    
    
    
    // This function returns the total of the order attached to a singular customer ID
    function order_total($leaf_customer_ID){
        if ($response_id){
            try {
                // Array of response results.
                //print_r($woocommerce->post('orders', $data));
                sleep(10);
                $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
                $sql = "Update json set parsed ='1' where jsonID = $response_id;";
                echo $sql;
                // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
                $conn->query($sql);
                
                //***** Massive amount of debugging *****
                // Example: ['customers' => [[ 'id' => 8, 'created_at' => '2015-05-06T17:43:51Z', 'email' => ...
                //echo '<pre><code>' . print_r( $data, true ) . '</code><pre>'; // JSON output.
                
                // Last request data.
                //$lastRequest = $woocommerce->http->getRequest();
                //
                //echo '<pre><code>' . print_r( $data->getUrl(), true ) . '</code><pre>'; // Requested URL (string).
                //echo '<pre><code>' . print_r( $data->getMethod(), true ) . '</code><pre>'; // Request method (string).
                //echo '<pre><code>' . print_r( $data->getParameters(), true ) . '</code><pre>'; // Request parameters (array).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Request headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Request body (JSON).
                
                // Last response data.
                //$lastResponse = $woocommerce->http->getResponse();
                //echo '<pre><code>' . print_r( $data->getCode(), true ) . '</code><pre>'; // Response code (int).
                //echo '<pre><code>' . print_r( $data->getHeaders(), true ) . '</code><pre>'; // Response headers (array).
                //echo '<pre><code>' . print_r( $data->getBody(), true ) . '</code><pre>'; // Response body (JSON).
                
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
            
        }else{
            echo "There is no json response ID";
        }
        
    }
    
    
    
    
    
    ?>

