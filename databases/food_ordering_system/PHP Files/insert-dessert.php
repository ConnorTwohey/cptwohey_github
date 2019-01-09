 <?php
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'Online_Food_Ordering');
	
	function insert_dessert($name, $descrip, $price, $image, $stock, $cal, $fats, $iscold) {
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
			$pdo->exec('LOCK TABLES `Product` WRITE, `Dessert` WRITE');
			print "Customer table is locked<br>";
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats);");
			
			$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
			$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
			$stmt->bindParam(':price', $price);
			$stmt->bindParam(':image', $image);
			$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
			$stmt->bindParam(':calories', $cal, PDO::PARAM_INT);
			$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
			$stmt->execute();
			
			print("Finding ProductID to reference<br>");
			
			$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
			$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
			$stmt->execute();
			$row = $stmt->fetch();
			$pid = $row['ProductId'];
			
			
			print("ProductID $pid found.<br>");
			
			$stmt = $pdo->prepare("INSERT INTO `Dessert` (`Pid`, `IsCold`) VALUES (:pid, :iscold);");
			$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
			$stmt->bindParam(':iscold', $iscold, PDO::PARAM_INT);
			$stmt->execute();
			
			$pdo->commit();
	  
			$pdo->exec('UNLOCK TABLES');
			print "Unlock table. Successful transaction<br>";
		
			$pdo = null;
		}
		catch(PDOException $error) {
			$pdo->rollback();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());
		}
	}
?> 