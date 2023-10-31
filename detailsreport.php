<?php 
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=findr", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$getData = $pdo->prepare("SELECT * FROM details ORDER BY userid ASC");
$getData->execute();
$results = $getData->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){

    $delimiter = ",";
    $filename = "details-data_".date('Y-m-d').".csv";

    //create a file pointer
    $f = fopen('php://memory', 'w');

    //set column headers
    $fields = array('UserID', 'Username', 'GENDER', 'AGE-RANGE', 'HOBBY 1', 'HOBBY 2',
                    'HOBBY 3', 'HOBBY 4', 'ABOUT', 'FOLLOWERS', 'FOLLOWING', 'LIKES');
    fputcsv($f, $fields, $delimiter);

    foreach($results as $result => $r){
        $linedata = array($r['userid'], $r['username'], $r['gender'], $r['preferredagerange'],
                        $r['hobby1'], $r['hobby2'], $r['hobby3'], $r['hobby4'], $r['about'],
                        $r['followers'], $r['following'], $r['likes']);
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