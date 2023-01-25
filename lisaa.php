<?php
//hae data lomakkeelta
$mess = $_POST['mess'];
$kirj = $_POST['kirj'];

$dsn = "pgsql:host=localhost;dbname=knykanen";
$user = "db_knykanen";
$pass = getenv("DB_PASSWORD");
$options = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];

try {
	$yht = new PDO($dsn, $user, $pass, $options);
	if (!$yht) { die(); }
	
	//tyhjaa ei lisata
	if ( !empty($kirj)and !empty($mess) )
	{
		//lisataan lomakkeella oleva message vieraskirjan loppuun
		$ins = "insert into vieras ";
		$ins .= "values ( default, ?, ?, now(), now() )";
		
		// valmistellaan SQL-lause suoritusta varten
		$lause = $yht->prepare($ins);
		
		$lause->bindValue(1, $mess);
		$lause->bindValue(2, $kirj);
		
		// suorita lisays
		$lause->execute();
		
		//tyhjennetään muuttujat (jottei F5 lisaa samaa rivia yha uudelleen
		//fiksumpi tapa $_SESSION
		unset($mess);
		unset($kirj);
		unset($_POST);
		unset($_REQUEST);
		header('Location: index.php');	
	}
	
}catch (PDOException $e){
	echo $e->getMessage();
	die();
}

?>