<?php
class db {
	private ?mysqli $conn = null;
	private array $credentials = [
		"host" => null,
		"user" => null,
		"pwd" => null,
		"name" => null
	];
	
	public array $errordocs = [
		"dbconnect" => "errors/db_connect.html",
		"dbquery" => "errors/db_query.html"
	];
	
	function __construct(?string $host, ?string $user, ?string $pwd, ?string $name) {
		$this->credentials["host"] = $host;
		$this->credentials["user"] = $user;
		$this->credentials["pwd"] = $pwd;
		$this->credentials["name"] = $name;
		
		if(!is_null($this->credentials["host"])) {
			$this->conn = $this->connect();
		}
	}
	
	private function connect():mysqli {
		try {
			$conn_intern = new MySQLi(
				$this->credentials["host"],
				$this->credentials["user"],
				$this->credentials["pwd"],
				$this->credentials["name"]
			);
			if($conn_intern->connect_errno>0) {
				if(TESTMODUS) {
					die("Fehler im Verbindungsaufbau. Abbruch");
				}
				else {
					header("Location: ".$errordocs["dbconnect"]);
				}
			}
			$conn_intern->set_charset("utf8mb4");
		}
		catch(Exception $e) {
			if(function_exists("ta")) { ta("Fehler im Verbindungsaufbau: ".$conn_intern->connect_error); }
			if(TESTMODUS) {
				die("Fehler im Verbindungsaufbau. Abbruch");
			}
			else {
				header("Location: ".$errordocs["dbconnect"]); //beispielhaft: eine Fehlerseite, die dem User erklärt, was denn geschehen ist UND wie er aus dieser verzwickten Situation wieder herauskommt
			}
		}
		
		return $conn_intern;
	}

	public function query(string $sql):mysqli_result|bool {
		try {
			$daten = $this->conn->query($sql);
			if($daten===false) {
				if(TESTMODUS) {
					if(function_exists("ta")) { ta($sql); }
					die("Fehler im SQL-Statement. Abbruch: " . $this->conn->error);
				}
				else {
					header("Location: ".$errordocs["dbquery"]);
				}
			}
		}
		catch(Exception $e) {
			if(TESTMODUS) {
				die("Fehler im SQL-Statement. Abbruch: " . $this->conn->error);
			}
			else {
				header("Location: ".$errordocs["dbquery"]);
			}
		}
		
		return $daten;
	}
}
?>