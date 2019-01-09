<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_NAME', 'Online_Food_Ordering');

    function insert_order($cid,$eid,$pid,$deliver){
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
			$pdo->exec('LOCK TABLES `FoodOrder` WRITE, `PRODUCT` WRITE');
			print "Customer table is locked<br>";
			
            $stmt = $pdo->prepare("INSERT INTO `FoodOrder` (`CmId`, `EmpId`, `Pid`, `Is_Delivery`) VALUES (:custid, :empid, :prodid, :deliv);");

            $stmt->bindParam(':custid', $cid, PDO::PARAM_INT);
            $stmt->bindParam(':empid', $eid, PDO::PARAM_INT);
            $stmt->bindParam(':prodid', $pid, PDO::PARAM_INT);
            $stmt->bindParam(':deliv', $deliver, PDO::PARAM_BOOL);
            $stmt->execute();
			
			$stmt = $pdo->prepare("SELECT Num_In_Stock FROM Product WHERE ProductId = :prodid");
            $stmt->bindParam(':prodid', $pid, PDO::PARAM_INT);
			$stmt->execute();
			$row = $stmt->fetch();
			$stock = $row['Num_In_Stock'];
			
			echo "Order inserted. Deincrementing product stock $stock.<br>";
			
			$stock--;

			$stmt = $pdo->prepare("UPDATE `Product` SET Num_In_Stock = :stock WHERE ProductId = :prodid;");
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':prodid', $pid, PDO::PARAM_INT);
			$stmt->execute();
			
			print "Product deincrmented.<br>";

            
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
