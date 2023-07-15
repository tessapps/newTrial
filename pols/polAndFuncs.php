<?php
function secureText($text){
	if ($text == ";"){
		alert("Login Error");
		echo yonlendir(1,"index.php");
	}
	else if ($text == ""){
		alert("Login Error");
		echo yonlendir(1,"index.php");
	}
	else if ($text == " "){
		alert("Login Error");
		echo yonlendir(1,"index.php");
	}
	else {
		// include("../konfigur/dbConnPage.php");
		// $text = mysqli_real_escape_string($konn,$text);
	}
	return $text;
	
}

function learnUserRank ($rankText, $rankOrder){
	$rankCode = substr($rankText,$rankOrder,1);
	return $rankCode;
}

function yonlendir ($sure,$sayfa){
	$deger = "<meta http-equiv=\"refresh\" content=\"$sure;url=$sayfa\">\n";
	return $deger;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function alert($message){
	echo ('<script language="javascript">');
	echo ('alert("'.$message.'")');
	echo ('</script>');
}

function substr_close_tags($code, $limit = 400)
{
    if ( strlen($code) <= $limit ){
        return $code;
    }

    $html = substr($code, 0, $limit);
    preg_match_all ( "#<([a-zA-Z]+)#", $html, $result );

    foreach($result[1] AS $key => $value){
        if ( strtolower($value) == 'br' ){
            unset($result[1][$key]);
        }
    }
    $openedtags = $result[1];

    preg_match_all ( "#</([a-zA-Z]+)>#iU", $html, $result );
    $closedtags = $result[1];

    foreach($closedtags AS $key => $value){
        if ( ($k = array_search($value, $openedtags)) === FALSE ){
            continue;
        }
        else{
            unset($openedtags[$k]);
        }
    }

    if ( empty($openedtags) ){
        if ( strpos($code, ' ', $limit) == $limit ){
            return $html."...";
        }
        else{
            return substr($code, 0, strpos($code, ' ', $limit))."...";
        }
    }

    $position = 0;
    $close_tag = '';
    foreach($openedtags AS $key => $value){   
        $p = strpos($code, ('</'.$value.'>'), $limit);

        if ( $p === FALSE ){
            $code .= ('</'.$value.'>');
        }
        else if ( $p > $position ){
            $close_tag = '</'.$value.'>';
            $position = $p;
        }
    }

    if ( $position == 0 ){
        return $code;
    }

    return substr($code, 0, $position).$close_tag."...";
}

function insertData($table_name, $data_array, $connection) {
    // Tablo adı ve veri dizisi alınır
    // Bağlantı parametresi olarak alınır
    
    // Sorgu oluşturulur
    $columns = implode(", ", array_keys($data_array));
    $values = implode(", ", array_fill(0, count($data_array), "?"));
    $query = "INSERT INTO $table_name ($columns) VALUES ($values)";
    
    // Sorgu hazırlanır ve çalıştırılır
    $statement = $connection->prepare($query);
    $statement->execute(array_values($data_array));
    
    // Bağlantı kapatılır
    $connection = null;
}

function checkDataExists($tableName, $connection, $whereData) {
    // where verilerini hazırla
    $where = '';
    $params = array();
    foreach ($whereData as $key => $value) {
        $where .= $key . ' = :' . $key . ' AND ';
        $params[':' . $key] = $value;
    }
    $where = rtrim($where, ' AND ');

    // Sorguyu hazırla ve yürüt
    $query = "SELECT COUNT(*) FROM $tableName WHERE $where";
    $statement = $connection->prepare($query);
    $statement->execute($params);

    // Sonucu al
    $count = $statement->fetchColumn();

    //$connection = null;

    // Sonucu değerlendir ve true/false döndür
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function updateTable($tableName, $connection, $updateData, $whereData) {
    // Güncellenecek verileri hazırla
    $set = '';
    $params = array();
    foreach ($updateData as $key => $value) {
        $set .= $key . ' = :' . $key . ', ';
        $params[':' . $key] = $value;
    }
    $set = rtrim($set, ', ');

    // Where cümlesini hazırla
    $where = '';
    foreach ($whereData as $key => $value) {
        $where .= $key . ' = :' . $key . ' AND ';
        $params[':' . $key] = $value;
    }
    $where = rtrim($where, ' AND ');

    // Sorguyu hazırla ve yürüt
    //echo "UPDATE $tableName SET $set WHERE $where";
    $query = "UPDATE $tableName SET $set WHERE $where";
    $statement = $connection->prepare($query);
    $statement->execute($params);

    // Güncelleme başarılıysa true döndür

    //$connection = null;
    return true;
}

function selectTableWithWhere($tableName, $connection, $whereData) {
    // Where cümlesini hazırla
    $where = '';
    $params = array();
    foreach ($whereData as $key => $value) {
        $where .= $key . ' = :' . $key . ' AND ';
        $params[':' . $key] = $value;
    }
    $where = rtrim($where, ' AND ');

    // Sorguyu hazırla ve yürüt
    $query = "SELECT * FROM $tableName WHERE $where";
    $statement = $connection->prepare($query);
    $statement->execute($params);

    // Verileri al
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Verileri dizi olarak döndür
    return $result;
}

?>