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
    trim($_POST["ntUsername"]) != "" ) 
	{

        $key = $GLOBALS["keyWord"];

		echo ($key);

        $encrypted_username = openssl_encrypt($_POST["ntUsername"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
        //$decrypted_string = openssl_decrypt($encrypted_string, "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

        $encrypted_password = openssl_encrypt($_POST["ntPassword"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));

        $checkUser = "SELECT * FROM userBasicTable WHERE userMail=:userMail";
        $statement = $konn->prepare($checkUser);
        $statement->execute([':userMail' => $encrypted_username]); 
        $getUsers = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($getUsers){
        alert("User already registered");
        //echo yonlendir(1,"index.php?p=register");
        }
        else {
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
		
		// İşlevi çağırın
		insertData('userBasicTable', $data, $konn);

        // $myQuery = "INSERT INTO userBasicTable (userDetailID, userMail, userPassword, registerCode, activePos) VALUES(:userDetailID, :userMail, :userPassword, :registerCode, :activePos)";

        // $statement = $konn->prepare($myQuery);

        // $statement->execute([
        //     ':userDetailID' => $userDetailID,
        //     ':userMail' => $userMail,
        //     ':userPassword' => $userPass,
        //     ':registerCode' =>$registerCode,
        //     ':activePos' => $activePos
        // ]);

      //message
	// 			$message = '
	// 			<html>
	// 			<head>
	// 			<title>'."yaslibakim-portali.com".'</title>
	// 			</head>
	// 			<body>';
	// 			$message = $message . 
	// 			'<a href="'.$GLOBALS["globalLink"].'activate.php?uM='.$userMail.'&uI='.$registerCode.'">Activate Your Account</a>
    //             <h4>Please click link below to activate your account. </h4>
    //             <h4>Sevgilerimizle, </h4>
    //             <p>'.$GLOBALS["globalLink"].'activate.php?uM='.$userMail.'&uI='.$registerCode.'</p>
    //             <p> </p>
    //             <h4>New Portal</h4>
	// 			</body>
	// 			</html>
	// 			';
				
	// 			$mail = new PHPMailer(true);				// Passing `true` enables exceptions
	// 			try {
	// 				//Server settings
	// 				$mail->SMTPDebug = 0;					// SMTP hata ayıklama // 0 = mesaj göstermez // 1 = sadece mesaj gösterir // 2 = hata ve mesaj gösterir
	// 				$mail->isSMTP();										
	// 				$mail->SMTPAuth = true;					// SMTP doğrulamayı etkinleştirir
	// 				$mail->Username = 'noreply@yaslibakim-portali.com';	// SMTP kullanıcı adı (gönderici adresi)
	// 				$mail->Password = 'A*LkXszfRtCK';		// SMTP şifre
	// 				$mail->Host = 'server314.web-hosting.com';		// Mail sunucusunun adresi
	// 				$mail->Port = 465;						// Normal bağlantı için 587, güvenli bağlantı için 465 yazın
	// 				$mail->SMTPSecure = 'ssl';				// Enable TLS encryption, '' , 'ssl' , 'tls'
	// 				$mail->SMTPOptions = array(
	// 					'ssl' => [
	// 						'verify_peer' => false,
	// 						'verify_peer_name' => false,
	// 						'allow_self_signed' => true,
	// 					],
	// 				);
	// 				$mail->SetLanguage('tr', 'PHPMailer/language/');

	// 				//Recipients
	// 				$mail->setFrom('noreply@yaslibakim-portali.com', 'yaslibakim-portali.com');	// Mail atıldığında gorulecek isim ve email
	// 				$mail->addAddress($userMail);								// Mailin gönderileceği alıcı adresi
					
	// 				//Content
	// 				$mail->isHTML(true);      
    //       $mail->CharSet = 'UTF-8';  //UTF-8   iso-8859-9
    //       $mail->Encoding = 'quoted-printable';
    //       $mail->Subject = 'yaslibakim-portali.com - Hosgeldiniz';                            
	// 				//$mail->Subject = ;				// Email konusu
	// 				$mail->Body    = $message;								// Mailin içeriği
					
	// 				$mail->send();
	// 				echo ("</br>"."Aktivasyon Bekleniyor...");
	// 			} catch (Exception $e) {
	// 				//echo 'Error. Hata: ', $mail->ErrorInfo;
	// 			}
    //   echo yonlendir(0,"index.php?p=register0");
    }
      
}

?>