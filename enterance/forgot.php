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
			<h2>Forgot Password</h2>
			<form id="myForm" action="process" method="post">
				<div class="inside-container">
					<div class="fontuser">
						<label><b>Username</b></label>
						<input type="text" placeholder="Enter Username" name="ntUsername" required>
						<i class="fa fa-user fa-lg"></i>
					</div>

					<div class="register-container">
						<div class="register-button-container">
						  <button type="submit" name="ntResetPassButton" value="ntResetPassButton">Reset Password</button>
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
    formData += '&ntResetPassButton=' + encodeURIComponent('ntResetPassButton'); // Button değerini ekleyin

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