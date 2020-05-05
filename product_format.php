include "woo_leaf_load.php";
include "line_item_search.php";
<?php
    function product_format($products, $product_line_items, $woo_lookup){
        //var_dump($products);
        
        $product_payload = array();
        
        $last = 0;
        for ($i = 0; $i < sizeof($products); $i++){
            $product = $products[$i][0];
            print_r($product);
            
            $product_sku = $product[0];
            $sku_ending = substr($product_sku, -1);
            $product_qty = (int) $product[1];
            $product_price = (float)$product[2];
            $line_item_id = (int) $product[3];
            
            try {
                $leaf_id = $product_line_items[$line_item_id];
                
                $woo_id = $woo_lookup[$leaf_id];
                if ($woo_lookup[$leaf_id] == null){
                   echo $leaf_id."\n".$product_sku."  leaf_id \n\n\n\n\n not found on woo lookup table";
                }
                else{
                    echo $leaf_id."\n".$product_sku."  leaf_id   is woo_id = ".$woo_id. "\n\n\n\n\n";
                }
                
            } catch (Exception $e) {
                echo 'Caught exception: LEAF ID OR WOO ID UNABLE TO REFERENCED',  $e->getMessage(), "\n";
            }

            
            if ($sku_ending == 'i'){
                array_push($product_payload,
                [
                'product_id' => $woo_id,
                'quantity' => $product_qty,
                'meta_data' => [
                [
                'key' => 'Dominance:',
                'value' => 'INDICA'
                ]
                ],
                'price' => $product_price
                ]);
            }
            elseif($sku_ending == 's'){
                array_push($product_payload,
                [
                'product_id' => $woo_id,
                'quantity' => $product_qty,
                'meta_data' => [
                [
                'key' => 'Dominance:',
                'value' => 'Sativa'
                ]
                ],
                'price' => $product_price
                ]);
            }
            else{
                array_push($product_payload,
                [
                'product_id' => $woo_id,
                'quantity' => $product_qty,
                'price' => $product_price
                ]);
            }
            
        }
        
        //$product_payload.="],";
       // ECHO "payload =";
        //print_r($product_payload);
        return $product_payload;
    }
?>


