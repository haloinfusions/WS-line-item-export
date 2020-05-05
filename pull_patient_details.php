<?php
//https://leaflink.com/api/v2/customers/{id}/

    function customer_contact_pull($customer_id){
        $curl = curl_init();
        
        echo $customer_id;
        curl_setopt_array($curl, array(
                                       CURLOPT_URL => "https://leaflink.com/api/v2/customers/$customer_id/",
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
        
        echo $response;

        
        
    }
    
    ?>
    
    
    ?>
