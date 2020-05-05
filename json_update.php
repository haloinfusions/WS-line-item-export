
<?php
    //Credentials should be pre-loaded from main file.


    function json_update($response_id){
       
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
    
    function woo_json_update($response_id){
        
        if ($response_id){
            try {
                // Array of response results.
                //print_r($woocommerce->post('orders', $data));
                sleep(10);
                $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
                $sql = "Update json2 set parsed ='1' where jsonID = $response_id;";
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

