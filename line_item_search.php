<?php

    
function line_item_search($order_num){
        $curl = curl_init();

    //echo $order_num;
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://leaflink.com/api/v2/orders-received/".$order_num."/line-items/",
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

?>
