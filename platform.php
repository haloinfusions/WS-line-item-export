<?php
    // init the resource
    $ch = curl_init();
    
    // set a single option...
    //curl_setopt($ch, OPTION, $value);
    // ... or an array of options
    curl_setopt_array($ch, array(
                                 "Accept" => "application/json" ,
                                "Content-Type" => "application/json",
                                 "x-mjf-api-key" => "507eea5b-7da2-11e9-863c-0260bbb1038a",
                                 "x-mjf-organization-id" => "1377",
                                 "x-mjf-facility-id" => "2926",
                                 "x-mjf-user-id" => "15406"
                                 ));
    
    // execute
    
    
    curl_setopt($ch, CURLOPT_URL, "https://partner-gateway-staging.mjplatform.com//v1/consumers
");
    
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    
    $output = curl_exec($ch);
    
    // free
    curl_close($ch);

?>
