<?php
    function woo_leaf_array(){
        $conn = new mysqli(constant("host"), constant("user"), constant("password"), constant("db"));

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        //Woo Product/ Leaflink Product ID matching. We pull all records and load them into a hashmap structure for rapid recall during the payload product line generation.

        $woo_lookup = Array();
        $sql_ids = "SELECT woo_id, leaf_id FROM leaf_conversion;";
        // single parse string 'Select * from json where parsed ='0' and response !='null' limit 1';
        $result = $conn->query($sql_ids);
        //"SELECT woo_id, leaf_id FROM leaf_conversion";

        //Iterate through the records if there is more than one
        // With the limit in place, we should be able to only have a single order record being processed at a time.
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                
                //Checking to see if the parsed flag has been modified after creation
                //The parsed flag should only be changed to 1 after the order creation sucess response is returned from WOO.
                $woo_lookup[$row['leaf_id']] = $row['woo_id'];
                
            }
        }
        else {
            echo "0 new responses";
        }

        $conn->close();
        return $woo_lookup;
    }
    
?>
