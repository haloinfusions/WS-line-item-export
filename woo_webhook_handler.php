<?php
    
    
    INCLUDE 'creds.php';
    // This file currently uses a sample webhook response captured by the webite
    //webhook.site (Yes that is a url.)
    $json_response = file_get_contents('php://input');
    
    
    $json_reponse = str_replace("'", "", $json_response);
    //$myfile = fopen("json.txt", "wr") or die("Unable to open file!");
    //fwrite($myfile, $json_reponse);
    //fclose($myfile);
    
    $json_reponse = json_decode($json_reponse);
    
    	$json_response = '{"data": {"payment_due_date": null, "notes": "", "internal_notes": null, "user": "Brianna", "external_id_seller": "54d932d94", "paid": false, "shipping_charge": "0.00", "discount_amount": "0.0000", "ship_date": "2019-10-29T05:00:00-07:00", "discount_type": "%", "sales_reps": [], "number": "93f5e0eb-64ee-4e1c-a248-0b7e43dfd645", "total": "342.00", "external_id_buyer": "4464c286", "created_on": "2019-10-23T17:17:08.957511-07:00", "paid_date": null, "payment_term": null, "tax_amount": "0.000000", "orderedproduct_set": [{"sale_price": "0.00", "is_sample": false, "quantity": "24.0000", "unit_multiplier": 24, "id": 4846034, "product": {"name": "Hemp CBD Ointment (\u00bd oz) [80mg]", "sku": "CH-W-0029"}, "ordered_unit_price": "114.00"}, {"sale_price": "0.00", "is_sample": false, "quantity": "24.0000", "unit_multiplier": 24, "id": 4846035, "product": {"name": "High CBD Ointment (\u00bd oz) [40mg/40mg THC/CBD]", "sku": "CH-W-0028"}, "ordered_unit_price": "114.00"}, {"sale_price": "0.00", "is_sample": false, "quantity": "24.0000", "unit_multiplier": 24, "id": 4846036, "product": {"name": "Pain Re-Leaf Ointment (\u00bd oz) [80mg]", "sku": "CH-W-0027"}, "ordered_unit_price": "114.00"}], "shipping_details": null, "manual": false, "buyer": {"id": 4462, "name": "Natures AZ Medicines", "licenses": [{"id": 4288, "number": "00000088DCXB00897085", "type": {"has_medical_line_items": false, "state": "AZ", "display_type": "Medical", "id": 28, "type": "Medical", "classification": "Marijuana"}}]}, "delivery_preferences": "\r\nHello, If I can please get a heads up on the delivery day/time at least 24-48 hour prior so I can ensure Ill be available to receive the order. Thank You! - Brianna 623-388-1222\r\n", "status": "Accepted", "customer": {"delinquent": false, "partner": {"id": 4462, "name": "Natures AZ Medicines", "licenses": [{"id": 4288, "number": "00000088DCXB00897085", "type": {"has_medical_line_items": false, "state": "AZ", "display_type": "Medical", "id": 28, "type": "Medical", "classification": "Marijuana"}}]}, "business_identifier": null, "state": "Arizona", "notes": null, "phone": null, "zipcode": "85009", "unit_number": null, "county": null, "service_zone": null, "name": "Natures AZ Medicines", "next_contact_date": null, "dba": "Natures AZ Medicines", "email": null, "license_type": {"has_medical_line_items": false, "state": "AZ", "display_type": "Medical", "id": 28, "type": "Medical", "classification": "Marijuana"}, "owner": {"id": 8117, "name": "Halo Infusions", "licenses": [{"id": 8984, "number": "00000026DCOM00344803", "type": {"has_medical_line_items": false, "state": "AZ", "display_type": "Medical", "id": 28, "type": "Medical", "classification": "Marijuana"}}]}, "ein": null, "price_schedule": null, "archived": false, "tier": null, "managers": [], "external_id": null, "city": "Phoenix", "created_on": "2019-09-30T12:38:06.479722-07:00", "description": ".", "nickname": null, "shipping_charge": "0.00", "payment_term": null, "business_license_name": null, "address": "2439 W. McDowell Rd.", "id": 298481, "delivery_preferences": "", "status": null, "license_number": "00000088DCXB00897085", "discount_percent": "0.00", "tags": [], "website": null}, "tax_type": "%", "seller": {"id": 8117, "name": "Halo Infusions", "licenses": [{"id": 8984, "number": "00000026DCOM00344803", "type": {"has_medical_line_items": false, "state": "AZ", "display_type": "Medical", "id": 28, "type": "Medical", "classification": "Marijuana"}}]}}, "type": "order", "action": "edit"}';
        //$json_data = json_decode($json_example,true);
	echo constant("db");
    
    
        //Print data
        //var_dump($json_example);
    
        //Connect to Database. This database is meant to store responses. This is recommended to distribute the actual parsing and item manipulation of the json in a separate time controlled script.
    
    
        // Drop Table json;
        //CREATE TABLE json (jsonID INT AUTO_INCREMENT PRIMARY KEY, response VARCHAR(10000), parsed BOOLEAN);
    	$conn = new mysqli(constant("host"), constant("user"), constant("password"),constant("db"));
        //$conn = new mysqli("localhost", "root", "BustZX1yTeoY", "leafwoo");


        if ($conn->connect_error) {
        	    die("Connection failed: " . $conn->connect_error);
        }
    
        $json_str =json_encode($json_response);
    
        $sql = "INSERT INTO json2 (response, parsed) VALUES ('$json_str', 0)";
    
        echo (strlen ( $json_str));
        // Drop Table json;
        //CREATE TABLE json (jsonID INT AUTO_INCREMENT PRIMARY KEY, response VARCHAR(10000), parsed BOOLEAN);
    
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    

    
        #echo($json_data);

?>

