
<?php
    include "creds.php";
function is_order(){
    try {
        // Array of response results.
        //print_r($woocommerce->post('orders', $data));
        sleep(2);
        $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));
        $sql = "select * from order_track;";
        echo $sql;
        $result = $conn->query($sql);
        // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
        var_dump($result->num_rows > 0);
        echo $result->num_rows;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                //Checking to see if the parsed flag has been modified after creation
                //The parsed flag should only be changed to 1 after the order creation sucess response is returned from WOO.
                $post_id = $row["woo_post_id"];
                $order_total = $row["order_total"];
                $customer_id = $row["leaf_customer_id"];
                $shipping = $row["shipping"];
                $new_time = $row["new_time"];
                echo $post_id."\n".$order_total."\n".$customer_id."\n".$shipping."\n".$new_time;
                

            }
        }
        else{
            return false;
        }
    
    } catch (HttpClientException $e) {
        
        echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
        echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
        echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
    }
    
    }
    is_order();
?>

