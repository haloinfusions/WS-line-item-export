
<?php session_start();
if (!(isset($_SESSION['user_id']) && $_SESSION['user_id'] != '')) {
    header ("Location: login.php");
}
?>


<?php
    include 'creds.php';
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    //intialize Woocommerce API client
    require __DIR__ . '/vendor/autoload.php';
    use Automattic\WooCommerce\Client;
    $url =  constant("woocommerce_site");
    use Automattic\WooCommerce\HttpClient\HttpClientException;
    
    $woocommerce = new Client(constant("woocommerce_site"), constant("consumer_key"), constant("consumer_secret"));

    $product_output = array();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start = test_input($_POST["start"]);
        
        $end = test_input($_POST["end"]);
        
        
        if ($woocommerce){
            try {
                // Array of response results.
                $end = $end." 23:59:00";
                $start = $start." 00:01:00";
                
                //echo "end is ".$end;
                //echo "start is ".$start;
                $params = array("per_page"=>100, "after"=>$start, "before"=>$end);
                $responded = $woocommerce->get('orders', $params);
                $number_order = sizeof($responded);
                // print_r($responded[1]);
                for($x = 0; $x < $number_order; $x++){
                    $order = $responded[$x];
                    $order_num = $order->number;
                    $order_date = $order->date_created;
                    $order_items = $order->line_items;
                    $order_size = sizeof($order_items);
                    for($i = 0; $i < $order_size; $i++){
                        $product = $order_items[$i];
                        //print_r($product);
                        $product_name = $product->name;
                        $product_qty = $product->quantity;
                        $product_sku = $product->sku;
                        $meta_data = $product->meta_data;
                        $meta_size = sizeof($meta_data);
                        $meta_value = "";
                        for($w = 0; $w < $meta_size; $w++){
                            $meta_item = $meta_data[$w];
                            //print_r($meta_item);
                            $meta_key = $meta_data[0]->key;
                            if ($meta_key ==  "Dominance:" || $meta_key == "pa_dominance"){
                                $meta_value = $meta_data[0]->value;
                            }
                            
                        }
                        array_push($product_output, array($product_name,  $product_sku, $product_qty, $meta_value, $order_num, $order_date));
                    }
                }
                
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
        }
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        header('Cache-Control: max-age=0');
        //print_r($product_output);
        $df = fopen("php://output", 'w');
        
        foreach ($product_output as $fields) {
            fputcsv($df, $fields);
        }
        //output all remaining data on a file pointer
        fpassthru($df);
        fclose($df);
        
        //print_r($product_output);
    }
    else{
        $start = "2019-12-26";
        $end = "2019-12-30";
        if ($woocommerce){
            try {
                // Array of response results.
                $end = $end." 23:59:00";
                $start = $start." 00:01:00";
                
                //echo "end is ".$end;
                //echo "start is ".$start;
                $params = array("per_page"=>100, "after"=>$start, "before"=>$end);
                $responded = $woocommerce->get('orders', $params);
                $number_order = sizeof($responded);
                // print_r($responded[1]);
                for($x = 0; $x < $number_order; $x++){
                    $order = $responded[$x];
                    $order_num = $order->number;
                    $order_date = $order->date_created;
                    $order_items = $order->line_items;
                    $order_size = sizeof($order_items);
                    for($i = 0; $i < $order_size; $i++){
                        $product = $order_items[$i];
                        //print_r($product);
                        $product_name = $product->name;
                        $product_qty = $product->quantity;
                        $product_sku = $product->sku;
                        $meta_data = $product->meta_data;
                        $meta_size = sizeof($meta_data);
                        $meta_value = "";
                        for($w = 0; $w < $meta_size; $w++){
                            $meta_item = $meta_data[$w];
                            //print_r($meta_item);
                            $meta_key = $meta_data[0]->key;
                            if ($meta_key ==  "Dominance:" || $meta_key == "pa_dominance"){
                                $meta_value = $meta_data[0]->value;
                            }
                            
                        }
                        array_push($product_output, array($product_name,  $product_sku, $product_qty, $meta_value, $order_num, $order_date));
                    }
                }
                
                
            } catch (HttpClientException $e) {
                
                echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
                echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
                echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
            }
        }
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        header('Cache-Control: max-age=0');
        //print_r($product_output);
        $df = fopen("php://output", 'w');
        
        foreach ($product_output as $fields) {
            fputcsv($df, $fields);
        }
        //output all remaining data on a file pointer
        fpassthru($df);
        fclose($df);
        /*echo "export finished";
        
        $name = "export.csv";
        $fb = fopen($name, 'rb');

        header("Content-Type: text/csv");
        header("Content-Length: " . filesize($name));
        
        // dump the picture and stop the script
        fpassthru($fb);
        exit;
         
         */
    }
    
    
   
   

    
    
    
    ?>

