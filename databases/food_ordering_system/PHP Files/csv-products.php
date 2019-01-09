<html>
<head>
<title>All products</title>
</head>
<body>
	<?php	require('config.php'); 
	
		print "ProductId, Product_Name, Description, Price, Product_Image, Num_In_Stock, Calories, Fats<br>";
		
		$pdo->beginTransaction();
		
		$pdo->exec('LOCK TABLES `Product` WRITE');
		try{
			$sql = 'SELECT * FROM Product ORDER BY ProductID';
			foreach ($pdo->query($sql) as $row) {
				print $row['ProductId'] . ", ";
				print '"' . $row['Product_Name'] . '", ';
				print '"' . $row['Description'] . '", ';
				print '"' . $row['Price'] . '", ';
				print '"' . $row['Product_Image'] . '", ';
				print $row['Num_In_Stock'] . ", ";
				print $row['Calories'] . ", ";
				print $row['Fats'] . "<br>";
			}
			$pdo->commit();
			
			$pdo->exec('UNLOCK TABLES');
			
			$pdo = null;
		}
		catch(PDOException $e){
			$pdo->rollBack();
		}
	?>
</body>
</html>