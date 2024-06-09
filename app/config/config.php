<?php

define('BASEURL', 'http://localhost/cruise_v2/');
define('ASSETS', 'http://localhost/cruise_v2/public');

//DB
define('DB_HOST', 'localhost');
// define('DB_USER', 'sutisnacompany_cruise');
// define('DB_PASS', 'xDDBBn3Wv4JD');
// define('DB_NAME', 'sutisnacompany_cruise');
define('DB_USER', 'root');
define('DB_PASS', 'wahyup');
define('DB_NAME', 'elcruise');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sql = "SELECT name,value FROM tb_setting";

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$mysqli->close();

// payment
foreach ($data as $d) {
    define($d['name'], $d['value']);
}

// mail
define('MAILHOST', 'sis.elreyfastcruise.com');
define('MAILUSERNAME', 'info@sis.elreyfastcruise.com');
define('MAILPASSWORD', '!d[}}S_s{oSS');
define('MAILFROM', 'info@sis.elreyfastcruise.com');
