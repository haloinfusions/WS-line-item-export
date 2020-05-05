<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="export.csv"');
header('Cache-Control: max-age=0');

$out = fopen('php://output', 'w');

while($row = mysql_fetch_assoc($results)){
    if($first){
        $titles = array();
        foreach($row as $key=>$val){
            $titles[] = $key;
        }
        fputcsv($out, $titles);
        $first = false;
    }
    fputcsv($out, $row);
}

fclose($out);
?>
