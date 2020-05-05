
<?php
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
                                   CURLOPT_URL => "https://partner-gateway-staging.mjplatform.com/v1/orders/",
                                   CURLOPT_RETURNTRANSFER => true,
                                   CURLOPT_ENCODING => "",
                                   CURLOPT_MAXREDIRS => 10,
                                   CURLOPT_TIMEOUT => 30,
                                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                   CURLOPT_CUSTOMREQUEST => "GET",
                                   CURLOPT_POSTFIELDS => "{\"data\": {\"payment_due_date\": null, \"notes\": \"\", \"internal_notes\": null, \"user\": \"Brianna\", \"external_id_seller\": \"54d932d94\", \"paid\": false, \"shipping_charge\": \"0.00\", \"discount_amount\": \"0.0000\", \"ship_date\": \"2019-10-29T05:00:00-07:00\", \"discount_type\": \"%\", \"sales_reps\": [], \"number\": \"93f5e0eb-64ee-4e1c-a248-0b7e43dfd645\", \"total\": \"342.00\", \"external_id_buyer\": \"4464c286\", \"created_on\": \"2019-10-23T17:17:08.957511-07:00\", \"paid_date\": null, \"payment_term\": null, \"tax_amount\": \"0.000000\", \"orderedproduct_set\": [{\"sale_price\": \"0.00\", \"is_sample\": false, \"quantity\": \"24.0000\", \"unit_multiplier\": 24, \"id\": 4846034, \"product\": {\"name\": \"Hemp CBD Ointment (\\u00bd oz) [80mg]\", \"sku\": \"CH-W-0029\"}, \"ordered_unit_price\": \"114.00\"}, {\"sale_price\": \"0.00\", \"is_sample\": false, \"quantity\": \"24.0000\", \"unit_multiplier\": 24, \"id\": 4846035, \"product\": {\"name\": \"High CBD Ointment (\\u00bd oz) [40mg/40mg THC/CBD]\", \"sku\": \"CH-W-0028\"}, \"ordered_unit_price\": \"114.00\"}, {\"sale_price\": \"0.00\", \"is_sample\": false, \"quantity\": \"24.0000\", \"unit_multiplier\": 24, \"id\": 4846036, \"product\": {\"name\": \"Pain Re-Leaf Ointment (\\u00bd oz) [80mg]\", \"sku\": \"CH-W-0027\"}, \"ordered_unit_price\": \"114.00\"}], \"shipping_details\": null, \"manual\": false, \"buyer\": {\"id\": 4462, \"name\": \"Nature's AZ Medicines\", \"licenses\": [{\"id\": 4288, \"number\": \"00000088DCXB00897085\", \"type\": {\"has_medical_line_items\": false, \"state\": \"AZ\", \"display_type\": \"Medical\", \"id\": 28, \"type\": \"Medical\", \"classification\": \"Marijuana\"}}]}, \"delivery_preferences\": \"\\r\\nHello, If I can please get a heads up on the delivery day/time at least 24-48 hour prior so I can ensure I'll be available to receive the order. Thank You! - Brianna 623-388-1222\\r\\n\", \"status\": \"Accepted\", \"customer\": {\"delinquent\": false, \"partner\": {\"id\": 4462, \"name\": \"Nature's AZ Medicines\", \"licenses\": [{\"id\": 4288, \"number\": \"00000088DCXB00897085\", \"type\": {\"has_medical_line_items\": false, \"state\": \"AZ\", \"display_type\": \"Medical\", \"id\": 28, \"type\": \"Medical\", \"classification\": \"Marijuana\"}}]}, \"business_identifier\": null, \"state\": \"Arizona\", \"notes\": null, \"phone\": null, \"zipcode\": \"85009\", \"unit_number\": null, \"county\": null, \"service_zone\": null, \"name\": \"Nature's AZ Medicines\", \"next_contact_date\": null, \"dba\": \"Nature's AZ Medicines\", \"email\": null, \"license_type\": {\"has_medical_line_items\": false, \"state\": \"AZ\", \"display_type\": \"Medical\", \"id\": 28, \"type\": \"Medical\", \"classification\": \"Marijuana\"}, \"owner\": {\"id\": 8117, \"name\": \"Halo Infusions\", \"licenses\": [{\"id\": 8984, \"number\": \"00000026DCOM00344803\", \"type\": {\"has_medical_line_items\": false, \"state\": \"AZ\", \"display_type\": \"Medical\", \"id\": 28, \"type\": \"Medical\", \"classification\": \"Marijuana\"}}]}, \"ein\": null, \"price_schedule\": null, \"archived\": false, \"tier\": null, \"managers\": [], \"external_id\": null, \"city\": \"Phoenix\", \"created_on\": \"2019-09-30T12:38:06.479722-07:00\", \"description\": \".\", \"nickname\": null, \"shipping_charge\": \"0.00\", \"payment_term\": null, \"business_license_name\": null, \"address\": \"2439 W. McDowell Rd.\", \"id\": 298481, \"delivery_preferences\": \"\", \"status\": null, \"license_number\": \"00000088DCXB00897085\", \"discount_percent\": \"0.00\", \"tags\": [], \"website\": null}, \"tax_type\": \"%\", \"seller\": {\"id\": 8117, \"name\": \"Halo Infusions\", \"licenses\": [{\"id\": 8984, \"number\": \"00000026DCOM00344803\", \"type\": {\"has_medical_line_items\": false, \"state\": \"AZ\", \"display_type\": \"Medical\", \"id\": 28, \"type\": \"Medical\", \"classification\": \"Marijuana\"}}]}}, \"type\": \"order\", \"action\": \"edit\"}",
                                   CURLOPT_HTTPHEADER => array(
                                                               "Accept: */*",
                                                               "Accept-Encoding: gzip, deflate",
                                                               "Authorization: Token 9bc76e3e6e377194eee3d2c97a4c20f60573aee6",
                                                               "Cache-Control: no-cache",
                                                               "Connection: keep-alive",
                                                               "Content-Length: 3505",
                                                               "Content-Type: application/json",
                                                               "Cookie: __cfduid=d6180200cb3f82c090c3f5535a30452221571854429",
                                                               "Host: partner-gateway-staging.mjplatform.com",
                                                               "Postman-Token: f368a143-93e0-48ef-881a-a5016acb94a1,931acd8d-2390-42a0-978a-f3069d12444c",
                                                               "User-Agent: PostmanRuntime/7.19.0",
                                                               "cache-control: no-cache",
                                                               "x-mjf-api-key: 507eea5b-7da2-11e9-863c-0260bbb1038a",
                                                               "x-mjf-facility-id: 2926",
                                                               "x-mjf-organization-id: 1377",
                                                               "x-mjf-user-id: 15406"
                                                               ),
                                   ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
    
?>
