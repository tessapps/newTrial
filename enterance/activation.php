<?php

include ("../konfig/dbKonnDetails.php");
include ("../pols/polAndFuncs.php");
include ("../pols/statics.php");

  if (isset($_GET["uM"]) && isset($_GET["uI"])){
    $tableName = 'userBasicTable';
    $whereData = array(
        'userMail' => $_GET["uM"],
        'userActivationCode' => $_GET["uI"],
        'activePos' => 0
    );

    $dataExists = checkDataExists($tableName, $konn, $whereData);
    if ($dataExists) {
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
  <meta charset="UTF-8">
  <title>Aktivasyon Sayfası</title>
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
    }

    .container {
      width: 400px;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }

    h2 {
      text-align: center;
      margin-top: 0;
    }

    p {
      text-align: center;
      margin-bottom: 20px;
    }

    .activation-link {
      display: block;
      text-align: center;
      font-size: 16px;
      color: #007bff;
      text-decoration: none;
    }

    .activation-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Deneme Sayfa</h2>
    <?php
      if ($dataPos) {
        for ($i=0;$i<count($messageList);$i++){
          echo "<p>$messageList[$i]</p>";
          flush(); // Tamponu boşalt
          ob_flush(); // Tamponu boşalt
          sleep(1);
        }
        $tableName = 'userBasicTable';
          $updateData = array(
              'activePos' => 1
          );
          $whereData = array(
            'userMail' => $_GET["uM"],
            'userActivationCode' => $_GET["uI"]
          );

          $result = updateTable($tableName, $konn, $updateData, $whereData);
          if ($result) {
            $key = $GLOBALS["keyWord"];
            $decrypted_string = openssl_decrypt($_GET["uM"], "AES-256-CBC", $key, 0, substr(md5($key), 0, 16));
            $insertArray = array(
              'userMail' => $_GET["uM"],
              'userName ' => $decrypted_string
            );
              insertData("userDetailTable",$insertArray,$konn);
          } else {
              //echo "Tablo güncellenirken bir hata oluştu.";
          }

          echo yonlendir(1,"login");
      }
      else {
        for ($i=0;$i<count($messageList);$i++){
          echo "<p>$messageList[$i]</p>";
          flush();
          ob_flush(); 
          sleep(1);
          echo yonlendir(1,"login");
        }
      }
    ?>
  </div>
</body>

</html>
