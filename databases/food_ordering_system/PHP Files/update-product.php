<?php
  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', 'root');
  define('DB_NAME', 'Online_Food_Ordering');

  function update_appetizer($pid, $name, $descrip, $price, $image, $stock, $calories, $fats, $size) {
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
		$pdo->exec('LOCK TABLES `Product` WRITE, `Appetizer` WRITE');
		print "Customer table is locked<br>";
		
		if($pid != ''){
			$stmt = $pdo->prepare("UPDATE `Product` SET `Product_Name`=:name,`Description`=:desc, `Price`=:price,`Product_Image`=:image,`Num_In_Stock`=:stock,`Calories`=:calories,`Fats`=:fats WHERE ProductId=:pid");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records UPDATED successfully<br>";
		}
		else{
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats) ON DUPLICATE KEY UPDATE `Description`=:desc, `Price`=:price, `Product_Image`=:image, `Num_In_Stock`=:stock, `Calories`=:calories, `Fats`=:fats;");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records INSERTED successfully<br>";
		}
		
		print("Finding ProductID to reference<br>");
		
		$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		$stmt->execute();
		$row = $stmt->fetch();
		$pid = $row['ProductId'];
		
		print("ProductID $pid found.<br>");
		
		$stmt = $pdo->prepare("INSERT INTO `Appetizer` (`Pid`, `Size`) VALUES (:pid, :size) ON DUPLICATE KEY UPDATE `Size`=:size;");
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':size', $size);
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
  
  function update_beverage($pid, $name, $descrip, $price, $image, $stock, $calories, $fats, $size) {
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
		$pdo->exec('LOCK TABLES `Product` WRITE, `Beverage` WRITE');
		print "Customer table is locked<br>";
		
		if($pid != ''){
			$stmt = $pdo->prepare("UPDATE `Product` SET `Product_Name`=:name,`Description`=:desc, `Price`=:price,`Product_Image`=:image,`Num_In_Stock`=:stock,`Calories`=:calories,`Fats`=:fats WHERE ProductId=:pid");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records UPDATED successfully";
		}
		else{
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats) ON DUPLICATE KEY UPDATE `Description`=:desc, `Price`=:price, `Product_Image`=:image, `Num_In_Stock`=:stock, `Calories`=:calories, `Fats`=:fats;");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records INSERTED successfully";
		}
		
		print("Finding ProductID to reference<br>");
		
		$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		$stmt->execute();
		$row = $stmt->fetch();
		$pid = $row['ProductId'];
		
		print("ProductID $pid found.<br>");
		
		$stmt = $pdo->prepare("INSERT INTO `Beverage` (`Pid`, `Size`) VALUES (:pid, :size) ON DUPLICATE KEY UPDATE `Size`=:size;");
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':size', $size);
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
  
  function update_maindish($pid, $name, $descrip, $price, $image, $stock, $calories, $fats, $size, $ishot) {
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
		$pdo->exec('LOCK TABLES `Product` WRITE, `MainDish` WRITE');
		print "Customer table is locked<br>";
		
		if($pid != ''){
			$stmt = $pdo->prepare("UPDATE `Product` SET `Product_Name`=:name,`Description`=:desc, `Price`=:price,`Product_Image`=:image,`Num_In_Stock`=:stock,`Calories`=:calories,`Fats`=:fats WHERE ProductId=:pid");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records UPDATED successfully";
		}
		else{
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats) ON DUPLICATE KEY UPDATE `Description`=:desc, `Price`=:price, `Product_Image`=:image, `Num_In_Stock`=:stock, `Calories`=:calories, `Fats`=:fats;");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records INSERTED successfully";
		}
		
		print("Finding ProductID to reference<br>");
		
		$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		$stmt->execute();
		$row = $stmt->fetch();
		$pid = $row['ProductId'];
		
		print("ProductID $pid found.<br>");
		
		$stmt = $pdo->prepare("INSERT INTO `MainDish` (`Pid`, `Size`, `IsHot`) VALUES (:pid, :size, :ishot) ON DUPLICATE KEY UPDATE `Size`=:size, `IsHot`=:ishot;");
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':ishot', $ishot, PDO::PARAM_INT);
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
  
  function update_salad($pid, $name, $descrip, $price, $image, $stock, $calories, $fats, $size, $hasdressing) {
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
		$pdo->exec('LOCK TABLES `Product` WRITE, `Salad` WRITE');
		print "Customer table is locked<br>";
		
		if($pid != ''){
			$stmt = $pdo->prepare("UPDATE `Product` SET `Product_Name`=:name,`Description`=:desc, `Price`=:price,`Product_Image`=:image,`Num_In_Stock`=:stock,`Calories`=:calories,`Fats`=:fats WHERE ProductId=:pid");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records UPDATED successfully";
		}
		else{
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats) ON DUPLICATE KEY UPDATE `Description`=:desc, `Price`=:price, `Product_Image`=:image, `Num_In_Stock`=:stock, `Calories`=:calories, `Fats`=:fats;");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records INSERTED successfully";
		}
		
		print("Finding ProductID to reference<br>");
		
		$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		$stmt->execute();
		$row = $stmt->fetch();
		$pid = $row['ProductId'];
		
		print("ProductID $pid found.<br>");
		
		$stmt = $pdo->prepare("INSERT INTO `Salad` (`Pid`, `Size`, `HasDressing`) VALUES (:pid, :size, :hasdressing) ON DUPLICATE KEY UPDATE `Size`=:size, `HasDressing`=:hasdressing;");
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':hasdressing', $hasdressing, PDO::PARAM_INT);
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
  
  function update_dessert($pid, $name, $descrip, $price, $image, $stock, $calories, $fats, $iscold) {
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
		
		if($pid != ''){
			$stmt = $pdo->prepare("UPDATE `Product` SET `Product_Name`=:name,`Description`=:desc, `Price`=:price,`Product_Image`=:image,`Num_In_Stock`=:stock,`Calories`=:calories,`Fats`=:fats WHERE ProductId=:pid");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records UPDATED successfully";
		}
		else{
			$stmt = $pdo->prepare("INSERT INTO `Product` (`Product_Name`, `Description`, `Price`, `Product_Image`, `Num_In_Stock`, `Calories`, `Fats`)  VALUES (:name, :desc, :price, :image, :stock, :calories, :fats) ON DUPLICATE KEY UPDATE `Description`=:desc, `Price`=:price, `Product_Image`=:image, `Num_In_Stock`=:stock, `Calories`=:calories, `Fats`=:fats;");
		   	$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		   	$stmt->bindParam(':desc', $descrip, PDO::PARAM_STR, 256);
		   	$stmt->bindParam(':price', $price);
		   	$stmt->bindParam(':image', $image);
		   	$stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
		   	$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
		   	$stmt->bindParam(':fats', $fats, PDO::PARAM_INT);
		   	$stmt->execute();
			echo $stmt->rowCount() . " records INSERTED successfully";
		}
		
		print("Finding ProductID to reference<br>");
		
		$stmt = $pdo->prepare("SELECT ProductId FROM Product WHERE Product_Name = :name;");
		$stmt->bindParam(':name', $name, PDO::PARAM_STR, 32);
		$stmt->execute();
		$row = $stmt->fetch();
		$pid = $row['ProductId'];
		
		print("ProductID $pid found.<br>");
		
		$stmt = $pdo->prepare("INSERT INTO `Dessert` (`Pid`, `IsCold`) VALUES (:pid, :iscold) ON DUPLICATE KEY UPDATE `IsCold`=:iscold;");
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':size', $iscold, PDO::PARAM_INT);
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