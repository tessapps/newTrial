<?php

include ("../konfig/dbKonnDetails.php");
include ("../pols/polAndFuncs.php");
include ("../pols/statics.php");

  if (isset($_GET["uM"]) && isset($_GET["uI"])){
    $tableName = 'userBasicTable';
    $whereData = array(
        'userMail' => $_GET["uM"],
        'userActivationCode' => $_GET["uI"]
    );

    $dataExists = checkDataExists($tableName, $konn, $whereData);
    if ($dataExists) {
        $whereArray = array(
            'userMail' => $_GET["uM"],
            'userActivationCode' => $_GET["uI"]
        );
        $data = selectTableWithWhere('userBasicTable', $konn, $whereArray);

        foreach ($data as $row) {
            $unixTime = strtotime($row['userActivationTime']);

            // 3 saat ekleyerek sonraki tarih ve saati hesapla
            $futureTime = $unixTime + (3 * 60 * 60); // 3 saat = 3 * 60 dakika * 60 saniye

            // Geçerli tarih ve saati al
            $currentDateTime = time();

            if ($currentDateTime <= $futureTime) {
                // İşlem yapılacaksa
                //echo "İşlem yapılıyor...";
            } else {
                // İşlem yapılmayacaksa
                alert("Your activation period has expired. Get new activation code");
                echo yonlendir(0,"login");
            }
        }
        

        $dataPos = true;
        $messageList = array("Your account is updating...", 
                            "Please wait...", 
                            "Creating tables...",
                            "Your account has been updated.",
                            "You are being redirected.");
    } else {
        $dataPos = false;
        $messageList = array("This account is already active.", 
                            "You are being redirected.");
    }
  }
  else {
    echo yonlendir("1","index");
  }
?>

<!DOCTYPE html>
<html>

<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">

</head>

<body>
	<div class="container">
		<div class="form-container">
			<h2>Reset Password</h2>
			<form id="myForm" action="process" method="post">
				<div class="inside-container">

					<div class="fontpassword">
						<label><b>New Password</b></label>
						<input type="password" placeholder="Enter Password" name="ntPassword" required>
						<i class="fa fa-key fa-lg"></i>
					</div>

                    <div class="fontpassword">
						<label><b>New Password Again</b></label>
						<input type="password" placeholder="Enter Password Again" name="ntPasswordRe" required>
						<i class="fa fa-key fa-lg"></i>
					</div>

                    <input type="hidden" name="ntUsername" value="<?php echo ($_GET["uM"]); ?>">
                    <input type="hidden" name="ntRegisterCode" value="<?php echo ($_GET["uI"]); ?>">

					<div class="register-container">
						<div class="register-button-container">
						  <button type="submit" name="ntResetPasswordButton" value="ntResetPasswordButton">Reset Password</button>
						</div>
					</div>

					<div class="register-forgot">
						<a href="login">Login</a> | <a href="register">Register</a>
					</div>
				</div>
				<div class="divider"></div>
				<div class="google-buttons">
					<button><i class="fa fa-google"></i>Google</button>
                </div>
                <div class="facebook-buttons">
					<button><i class="fa fa-facebook"></i>Facebook</button>
                </div>
                <div class="twitter-buttons">
					<button><i class="fa fa-twitter"></i>Twitter</button>
				</div>
			</form>
		</div>
		<div class="image-container">
			<img src="images/login-main.jpg" alt="Resim">
		</div>
	</div>

	<!-- İşlem sırasında ekrana karartma efekti için modal -->
	<div id="loadingModal" style="display: none;">
		<div id="loadingOverlay"></div>
		<div id="loadingSpinner"></div>
	</div>

	<!-- İşlem tamamlandıktan sonra bilgilendirme mesajı göstermek için modal -->
	<div id="resultModal" style="display: none;">
		<div id="resultMessage"></div>
	</div>
</body>

</html>

<script>

$(document).ready(function() {
  $('#myForm').submit(function(event) {
    event.preventDefault(); // Formun otomatik submit olmasını engeller

    // Ekrana karartma efektini göster
    $('#loadingModal').show();

    // AJAX isteği gönder
    var formData = $(this).serialize();
    formData += '&ntResetPasswordButton=' + encodeURIComponent('ntResetPasswordButton'); // Button değerini ekleyin

    $.ajax({
      url: $(this).attr('action'),
      type: $(this).attr('method'),
      data: formData,
      success: function(response) {
        // AJAX isteği başarılı olduğunda çalışır
        // İşlem tamamlandığı için karartma efektini gizle
        $('#loadingModal').hide();

        // Sonuç modalını göster
        $('#resultModal').show();

		// Mesajları satır satır yazdırmak için diziye böl
		var messages = response.split('<br>');

		// Döngüyle her bir mesajı belirli bir süre aralığıyla yazdır
		var delay = 1000; // 1 saniye
		var i = 0;
		var interval = setInterval(function() {
		// Mevcut mesajı al
		var currentMessage = messages[i];

		// Mesajı modal içine ekleyerek alt alta yazdır
		$('#resultMessage').append(currentMessage + '<br>');

		// Döngüyü bir sonraki mesaja geçmek için arttır
		i++;

		// Döngü sona erdiğinde veya mesajlar tamamlandığında süreci durdur
		if (i >= messages.length) {
			clearInterval(interval);

			// Belirli bir süre sonra yönlendirme yap
			setTimeout(function() {
			window.location.href = 'login';
			}, 1000); // 3000 milisaniye (3 saniye) sonra yönlendirir
		}
		}, delay);
      },
      error: function() {
        // AJAX isteği başarısız olduğunda çalışır
        alert('İsteğiniz gerçekleştirilemedi. Lütfen tekrar deneyin.');
      }
    });
  });
});


</script>
