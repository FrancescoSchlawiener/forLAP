<?php
function dbConnect():mysqli {
	try {
		$conn_intern = new MySQLi(DB["host"],DB["user"],DB["pwd"],DB["name"]);
		if($conn_intern->connect_errno>0) {
			if(TESTMODUS) {
				die("Fehler im Verbindungsaufbau. Abbruch");
			}
			else {
				header("Location: errors/db_connect.html");
			}
		}
		$conn_intern->set_charset("utf8mb4");
	}
	catch(Exception $e) {
		ta("Fehler im Verbindungsaufbau: ".$conn_intern->connect_error);
		if(TESTMODUS) {
			die("Fehler im Verbindungsaufbau. Abbruch");
		}
		else {
			header("Location: errors/db_connect.html"); //beispielhaft: eine Fehlerseite, die dem User erklärt, was denn geschehen ist UND wie er aus dieser verzwickten Situation wieder herauskommt
		}
	}
	
	return $conn_intern;
}

function dbQuery(mysqli $conn_intern, string $sql):mysqli_result|bool {
	try {
		$daten = $conn_intern->query($sql);
		if($daten===false) {
			if(TESTMODUS) {
				ta($sql);
				die("Fehler im SQL-Statement. Abbruch: " . $conn_intern->error);
			}
			else {
				header("Location: errors/db_query.html");
			}
		}
	}
	catch(Exception $e) {
		if(TESTMODUS) {
			die("Fehler im SQL-Statement. Abbruch: " . $conn_intern->error);
		}
		else {
			header("Location: errors/db_query.html");
		}
	}
	
	return $daten;
}
?>