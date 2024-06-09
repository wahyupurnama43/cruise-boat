<?php

//mengecek apakah ada session atau tidak di aplikasi
if (!session_id()) {
	session_start();
}

require_once 'app/init.php';

$app = new App;
