<?php
    
    
    INCLUDE 'creds.php';
    // This file currently uses a sample webhook response captured by the webite
    //webhook.site (Yes that is a url.)
    $json_response = file_get_contents('php://input');
    
    //$json_response = "'apple pies'";
    
    
    $json_response = str_replace("'", "", $json_response);
    //$myfile = fopen("json.txt", "wr") or die("Unable to open file!");
    //fwrite($myfile, $json_reponse);
    //fclose($myfile);
    echo "output".$json_response;
    
   
    
    //$json_reponse = json_decode($json_response);
    
    	
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
    
        $sql = "INSERT INTO json (response, parsed) VALUES ('$json_str', 0)";
    
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

