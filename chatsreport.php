<?php 
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=findr", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$getData = $pdo->prepare("SELECT * FROM chatsessions");
$getData->execute();
$results = $getData->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){

    $delimiter = ",";
    $filename = "chat-session-data_".date('Y-m-d').".csv";

    //create a file pointer
    $f = fopen('php://memory', 'w');

    //set column headers
    $fields = array('SENDER', 'RECIPIENT', 'CHAT FILE LOCATION');
    fputcsv($f, $fields, $delimiter);

    foreach($results as $result => $r){
        $linedata = array($r['sender'], $r['recipient'], $r['chatlocation']);
        fputcsv($f, $linedata, $delimiter);
        
    }

    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than display
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    echo "<h1>You have an error</h1>";
    exit;
}