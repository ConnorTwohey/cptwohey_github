 <?php
 	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'Online_Food_Ordering');

	try{
		$pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
	
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		print "Connected to database. <br><br>";
	
	} catch(PDOException $error){
		die("ERROR: Could not connect. " . $error->getMessage());
	}
	
	try{
		$pdo->beginTransaction();
		print "Transaction has begun.<br>";
		
		print "Locking.<br>";
		$pdo->exec('LOCK TABLES `Customer` WRITE');
		print "Customer table is locked<br>";
		
		$stmt = $pdo->prepare("DELETE FROM `Customer` WHERE IdNo=:idno;");
		
		$stmt->bindParam(':idno', $_GET["id"]);
		$stmt->execute();
		$pdo->commit();
		
		$pdo->exec('UNLOCK TABLES');
		
		print "Table unlocked.<br>";
		print "Successful transaction<br>";
		$pdo->null;
	}
	catch(PDOException $error) {
		$pdo->rollback();
		print("Rollingback <br>");
		die("ERROR: Could not complete. " . $error->getMessage());
	}
?> 