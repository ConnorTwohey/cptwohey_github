 <?php
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'Online_Food_Ordering');
	
 	function insert_customer($name, $password, $finit, $lname, $address, $phoneno, $email, $credits){
		print("$name, $password, $finit, $lname, $address, $phoneno, $email, $credits<br>");
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
		
			$stmt = $pdo->prepare("INSERT INTO `Customer` (`UserName`, `Password`, `Finit`, `Lname`, `Address`, `PhoneNo`, `Email`, `Credits`) VALUES (:uname, :pass, :init, :last, :addr, :phone, :mail, :cred);");
			
			$stmt->bindParam(':uname', $name, PDO::PARAM_STR, 12);
			$stmt->bindParam(':pass', $password, PDO::PARAM_STR, 12);
			$stmt->bindParam(':init', $finit, PDO::PARAM_STR, 1);
			$stmt->bindParam(':last', $lname, PDO::PARAM_STR, 32);
			$stmt->bindParam(':addr', $address, PDO::PARAM_STR, 256);
			$stmt->bindParam(':phone', $phoneno, PDO::PARAM_STR, 12);
			$stmt->bindParam(':mail', $email, PDO::PARAM_STR, 100);
			$stmt->bindValue(':cred', $credits, PDO::PARAM_INT);
			$stmt->execute();
			$pdo->commit();
			
			$pdo->exec('UNLOCK TABLES');
			
			print "Successful transaction<br>";
			$pdo->null;
		}
		catch(PDOException $error) {
			$pdo->rollback();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());
		}
	}
	?> 