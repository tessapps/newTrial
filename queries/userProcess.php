<?php

include ("../konfig/dbKonnDetails.php");
include ("../pols/statics.php");
include ("../pols/polAndFuncs.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require ('../PHPMailer/src/Exception.php');
require ('../PHPMailer/src/PHPMailer.php');
require ('../PHPMailer/src/SMTP.php');

if (isset($_POST["ntRegisterButton"])  && 
    ($_POST["ntPassword"] == $_POST["ntPasswordRe"]) &&
    trim($_POST["ntPassword"]) != "" &&
    trim($_POST["ntUsername"]) != "" ) {

        $key = $GLOBALS["keyWord"];

        $encrypted_username = openssl_encrypt($_POST["ntUsername"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
        //$decrypted_string = openssl_decrypt($encrypted_string, "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

        $encrypted_password = openssl_encrypt($_POST["ntPassword"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

        $checkUser = "SELECT * FROM userBasicTable WHERE userMail=:userMail";
        $statement = $konn->prepare($checkUser);
        $statement->execute([':userMail' => $encrypted_username]); 
        $getUsers = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($getUsers){
			echo ("User Already Registered");
			//alert("User already registered");
			//echo yonlendir(1,"index.php?p=register");
        }
        else {
			echo ("Registration Begins.."."<br>");

			$registerCode = generateRandomString(99);
			$activePos = 0;  //0 olmalı
			$creationDate = date("Y-m-d H:i:s");
			$userSocial = 0;
			$socialCode = "111222";

			$data = array(
				'userMail' => $encrypted_username,
				'userPassword' => $encrypted_password,
				'creationDate' => $creationDate,
				'userSocial' => $userSocial,
				'userSocialCode' => $socialCode,
				'userActivationCode' => $registerCode,
				'userActivationTime' => $creationDate,
				'activePos' => $activePos
			);

			echo ("Your values are saved..."."<br>");
		
		// İşlevi çağırın
		insertData('userBasicTable', $data, $konn);
			echo ("Your transactions are in progress....."."<br>");

      //message
		$message = '
		<html>
		<head>
		<title>'."DenemeFirma.com".'</title>
		</head>
		<body>';
		$message = $message . 
		'<a href="'.$GLOBALS["globalLink"].'activate?uM='.$encrypted_username.'&uI='.$registerCode.'">Activate Your Account</a>
		<h4>Please click link below to activate your account. </h4>
		<h4>Sevgilerimizle, </h4>
		<p>'.$GLOBALS["globalLink"].'activate?uM='.$encrypted_username.'&uI='.$registerCode.'</p>
		<p> </p>
		<h4>New Portal</h4>
		</body>
		</html>
		';
		
		$mail = new PHPMailer(true);				// Passing `true` enables exceptions
		try {
			//Server settings
			$mail->SMTPDebug = 0;					// SMTP hata ayıklama // 0 = mesaj göstermez // 1 = sadece mesaj gösterir // 2 = hata ve mesaj gösterir
			$mail->isSMTP();										
			$mail->SMTPAuth = true;					// SMTP doğrulamayı etkinleştirir
			$mail->Username = 'noreply@semetu.com';	// SMTP kullanıcı adı (gönderici adresi)
			$mail->Password = '4g3i21^Xq';		// SMTP şifre
			$mail->Host = 'mail.semetu.com';		// Mail sunucusunun adresi
			$mail->Port = 465;						// Normal bağlantı için 587, güvenli bağlantı için 465 yazın
			$mail->SMTPSecure = 'ssl';				// Enable TLS encryption, '' , 'ssl' , 'tls'
			$mail->SMTPOptions = array(
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				],
			);
			$mail->SetLanguage('tr', 'PHPMailer/language/');

			//Recipients
			$mail->setFrom('noreply@semetu.com', 'semetu.com');	// Mail atıldığında gorulecek isim ve email
			$mail->addAddress($_POST["ntUsername"]);								// Mailin gönderileceği alıcı adresi
			
			//Content
			$mail->isHTML(true);      
			$mail->CharSet = 'UTF-8';  //UTF-8   iso-8859-9
			$mail->Encoding = 'quoted-printable';
			$mail->Subject = 'semetu.com - Hosgeldiniz';                            
			//$mail->Subject = ;				// Email konusu
			$mail->Body    = $message;								// Mailin içeriği
			
			$mail->send();
			//echo ("</br>"."Aktivasyon Bekleniyor...");
		} catch (Exception $e) {
			//echo 'Error. Hata: ', $mail->ErrorInfo;
		}
    //   echo yonlendir(0,"index.php?p=register0");

	echo ("Pending activation......"."<br>");
    }
      
}

if (isset($_POST["ntResetPassButton"]) && 
trim($_POST["ntUsername"]) != ""){

	echo ("Checking your settings.."."<br>");
	$key = $GLOBALS["keyWord"];

    $encrypted_username = openssl_encrypt($_POST["ntUsername"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

	$whereArray = array(
		'userMail' => $encrypted_username
	);

	$dataExists = checkDataExists('userBasicTable', $konn, $whereArray);
	if ($dataExists){
		echo ("Please check your mail box.."."<br>");
		$registerCode = generateRandomString(99);
		$updateArray = array(
			'userActivationCode' => $registerCode,
			'userActivationTime' => date("Y-m-d H:i:s")
		); 
		updateTable('userBasicTable',$konn, $updateArray, $whereArray);

		$message = '
		<html>
		<head>
		<title>'."DenemeFirma.com".'</title>
		</head>
		<body>';
		$message = $message . 
		'<a href="'.$GLOBALS["globalLink"].'resetpassword?uM='.$encrypted_username.'&uI='.$registerCode.'">Reset Your Account</a>
		<h4>Please click link below to reset your account. </h4>
		<h4>Kind Regards, </h4>
		<p>'.$GLOBALS["globalLink"].'resetpassword?uM='.$encrypted_username.'&uI='.$registerCode.'</p>
		<p> </p>
		<h4>New Portal</h4>
		</body>
		</html>
		';
		
		$mail = new PHPMailer(true);				// Passing `true` enables exceptions
		try {
			//Server settings
			$mail->SMTPDebug = 0;					// SMTP hata ayıklama // 0 = mesaj göstermez // 1 = sadece mesaj gösterir // 2 = hata ve mesaj gösterir
			$mail->isSMTP();										
			$mail->SMTPAuth = true;					// SMTP doğrulamayı etkinleştirir
			$mail->Username = 'noreply@semetu.com';	// SMTP kullanıcı adı (gönderici adresi)
			$mail->Password = '4g3i21^Xq';		// SMTP şifre
			$mail->Host = 'mail.semetu.com';		// Mail sunucusunun adresi
			$mail->Port = 465;						// Normal bağlantı için 587, güvenli bağlantı için 465 yazın
			$mail->SMTPSecure = 'ssl';				// Enable TLS encryption, '' , 'ssl' , 'tls'
			$mail->SMTPOptions = array(
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				],
			);
			$mail->SetLanguage('tr', 'PHPMailer/language/');

			//Recipients
			$mail->setFrom('noreply@semetu.com', 'semetu.com');	// Mail atıldığında gorulecek isim ve email
			$mail->addAddress($_POST["ntUsername"]);								// Mailin gönderileceği alıcı adresi
			
			//Content
			$mail->isHTML(true);      
			$mail->CharSet = 'UTF-8';  //UTF-8   iso-8859-9
			$mail->Encoding = 'quoted-printable';
			$mail->Subject = 'semetu.com - Hosgeldiniz';                            
			//$mail->Subject = ;				// Email konusu
			$mail->Body    = $message;								// Mailin içeriği
			
			$mail->send();
			//echo ("</br>"."Aktivasyon Bekleniyor...");
		} catch (Exception $e) {
			//echo 'Error. Hata: ', $mail->ErrorInfo;
		}

		//echo yonlendir(1, "login");
	}
	else {
		echo ("Please check your mail box...."."<br>");
		//echo yonlendir(1, "login");
	}
}

if (isset($_POST["ntResetPasswordButton"]) &&
($_POST["ntPassword"] == $_POST["ntPasswordRe"]) &&
	trim($_POST["ntUsername"]) != "" &&
	trim($_POST["ntRegisterCode"]) != "" ){
	
	echo ("Checking your settings.."."<br>");

	$key = $GLOBALS["keyWord"];
    $encrypted_username = $_POST["ntUsername"];
	$whereArray = array(
		'userMail' => $encrypted_username,
		'userActivationCode' => $_POST["ntRegisterCode"]
	);

	$dataExists = checkDataExists('userBasicTable',$konn,$whereArray);
	if($dataExists){
		echo ("Updating.."."<br>");
		$registerCode = generateRandomString(99);
		$encrypted_password = openssl_encrypt($_POST["ntPassword"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
		$updateArray = array(
			'userActivationCode' => $registerCode,
			'userActivationTime' => date("Y-m-d H:i:s"),
			'activePos' => 1,
			'userPassword' => $encrypted_password
		);
		$whereArray = array(
			'userMail' => $encrypted_username
		);

		$isUpdated = updateTable('userBasicTable', $konn, $updateArray, $whereArray);
		echo ("Complete. Please Login"."<br>");
	}
}

if (isset($_POST["ntLoginButton"]) &&
trim($_POST["ntPassword"]) != "" &&
    trim($_POST["ntUsername"]) != "" ){
		echo ("Checking your settings.."."<br>");
		$key = $GLOBALS["keyWord"];

        $encrypted_username = openssl_encrypt($_POST["ntUsername"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
        $encrypted_password = openssl_encrypt($_POST["ntPassword"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

		$whereArray = array(
			'userMail' => $encrypted_username,
			'userPassword' => $encrypted_password
		);

		$dataExists = checkDataExists('userBasicTable',$konn,$whereArray);
		if ($dataExists){
			$data = selectTableWithWhere('userBasicTable',$konn,$whereArray);
			foreach ($data as $row) {
				if ($row['activePos'] == 1){
					echo ("Login successful"."<br>");
					echo ("You are being redirected"."<br>");

					$_SESSION['userName'] = $encrypted_username;
				}
				else if ($row['activePos'] == 0) {
					echo ("Your account is not active"."<br>");
					echo ("Activate your account. Please check your mail."."<br>");

					$message = '
					<html>
					<head>
					<title>'."DenemeFirma.com".'</title>
					</head>
					<body>';
					$message = $message . 
					'<a href="'.$GLOBALS["globalLink"].'activate?uM='.$encrypted_username.'&uI='.$row['userActivationCode'].'">Activate Your Account</a>
					<h4>Please click link below to activate your account. </h4>
					<h4>Sevgilerimizle, </h4>
					<p>'.$GLOBALS["globalLink"].'activate?uM='.$encrypted_username.'&uI='.$row['userActivationCode'].'</p>
					<p> </p>
					<h4>New Portal</h4>
					</body>
					</html>
					';
					
					$mail = new PHPMailer(true);				// Passing `true` enables exceptions
					try {
						//Server settings
						$mail->SMTPDebug = 0;					// SMTP hata ayıklama // 0 = mesaj göstermez // 1 = sadece mesaj gösterir // 2 = hata ve mesaj gösterir
						$mail->isSMTP();										
						$mail->SMTPAuth = true;					// SMTP doğrulamayı etkinleştirir
						$mail->Username = 'noreply@semetu.com';	// SMTP kullanıcı adı (gönderici adresi)
						$mail->Password = '4g3i21^Xq';		// SMTP şifre
						$mail->Host = 'mail.semetu.com';		// Mail sunucusunun adresi
						$mail->Port = 465;						// Normal bağlantı için 587, güvenli bağlantı için 465 yazın
						$mail->SMTPSecure = 'ssl';				// Enable TLS encryption, '' , 'ssl' , 'tls'
						$mail->SMTPOptions = array(
							'ssl' => [
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true,
							],
						);
						$mail->SetLanguage('tr', 'PHPMailer/language/');

						//Recipients
						$mail->setFrom('noreply@semetu.com', 'semetu.com');	// Mail atıldığında gorulecek isim ve email
						$mail->addAddress($_POST["ntUsername"]);								// Mailin gönderileceği alıcı adresi
						
						//Content
						$mail->isHTML(true);      
						$mail->CharSet = 'UTF-8';  //UTF-8   iso-8859-9
						$mail->Encoding = 'quoted-printable';
						$mail->Subject = 'semetu.com - Hosgeldiniz';                            
						//$mail->Subject = ;				// Email konusu
						$mail->Body    = $message;								// Mailin içeriği
						
						$mail->send();
						//echo ("</br>"."Aktivasyon Bekleniyor...");
					} catch (Exception $e) {
						//echo 'Error. Hata: ', $mail->ErrorInfo;
					}
				}
			}
		}
		else {
			echo ("Username or password is wrong.."."<br>");
			echo ("Try again."."<br>");
		}
	}

?>