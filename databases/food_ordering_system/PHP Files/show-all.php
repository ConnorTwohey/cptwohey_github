<html>
<head>
<title>All Tables</title>
<style type="text/css">
	table, th, td {
		border: 1px solid black;
		text-align:center
	}
	table tr:nth-child(odd) {
		background-color: #ccc;
	}
</style>
</head>
<body>
	<?php	require('config.php'); ?>
    
	<h2>All Food Orders</h2>
    
    <?php
		print "<table style=width:100%><tr><th>OrderId</th><th>CmId</th><th>EmpId</th><th>Pid</th><th>OrderDate</th><th>Is_Delivery<br>(1 if true, 0 if false)</th></tr>";
		
		try{
			$pdo->beginTransaction();
			
        	$pdo->exec('LOCK TABLES `FoodOrder` READ');
			$stmt = $pdo->prepare('SELECT * FROM FoodOrder');
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				print "<tr>";
				print "<td>" . $row[0] . "</td>";
				print "<td>" . $row[1] . "</td>";
				print "<td>" . $row[2] . "</td>";
				print "<td>" . $row[3] . "</td>";
				print "<td>" . $row[4] . "</td>";
				print "<td>" . $row[5] . "</td>";
				print "</tr>";
			}
			print "</table><br>";
			$pdo->commit();
			$pdo->exec('UNLOCK TABLES');
		}
		catch(PDOException $error){
			$pdo->rollBack();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());

		}
	?>
    
    <h2>All Customer Payments</h2>
    
    <?php
		print "<table style=width:100%><tr><th>Oid</th><th>Cid</th><th>Payment Method</th><th>Total Price</th></tr>";
		
		try{
			$pdo->beginTransaction();
			
        	$pdo->exec('LOCK TABLES `Customer_Payments` READ');
			$stmt = $pdo->prepare('SELECT * FROM Customer_Payments');
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				print "<tr>";
				print "<td>" . $row[0] . "</td>";
				print "<td>" . $row[1] . "</td>";
				print "<td>" . $row[2] . "</td>";
				print "<td>" . $row[3] . "</td>";
				print "</tr>";
			}
			print "</table><br>";
			$pdo->commit();
			$pdo->exec('UNLOCK TABLES');
		}
		catch(PDOException $error){
			$pdo->rollBack();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());

		}
	?>
    
    <h2>All Customer Records</h2>
    
	<?php
        print "<table style=width:100%><tr><th>ID No.</th><th>Username</th><th>Password</th><th>Name</th>
        <th>Address</th><th>Phone No.</th><th>Email</th><th>Created by</th><th>Credits</th></tr>";
		
        try{
			$pdo->beginTransaction();
        
        	$pdo->exec('LOCK TABLES `Customer` READ');
			
            $sql = 'SELECT * FROM Customer ORDER BY IdNo';
            foreach ($pdo->query($sql) as $row) {
				$id = $row['IdNo'];
                echo "<tr>";
                echo "<td>" . $row['IdNo'] . "</td>";
                echo "<td>" . $row['UserName'] . "</td>";
                echo "<td>" . $row['Password'] . "</td>";
                echo "<td>" . $row['Finit'] . ". " . $row['Lname'] . "</td>";
                echo "<td>" . $row['Address'] . "</td>";
                echo "<td>" . $row['PhoneNo'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['CreatedDate'] . "</td>";
                echo "<td>" . $row['Credits'] . "</td></tr>";
            }
			echo "</table><br>";
            $pdo->commit();
			
			$pdo->exec('UNLOCK TABLES');
        }
        catch(PDOException $error){
            $pdo->rollBack();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());
        }
    ?>
    
    <h2>All Employee Records</h2>
	
	<?php
        echo "<table style=width:100%><tr><th>ID No.</th><th>Username</th><th>Password</th><th>Name</th>
        <th>Address</th><th>Phone No.</th><th>Email</th><th>Created by</th><th>Ssn</th><th>Salary</th></tr>";
		
        
        try{
			$pdo->beginTransaction();
        
        	$pdo->exec('LOCK TABLES `Employee` READ');
			
            $sql = 'SELECT * FROM Employee ORDER BY IdNo';
            foreach ($pdo->query($sql) as $row) {
				$id = $row['IdNo'];
                echo "<tr>";
                echo "<td>" . $row['IdNo'] . "</td>";
                echo "<td>" . $row['UserName'] . "</td>";
                echo "<td>" . $row['Password'] . "</td>";
                echo "<td>" . $row['Finit'] . ". " . $row['Lname'] . "</td>";
                echo "<td>" . $row['Address'] . "</td>";
                echo "<td>" . $row['PhoneNo'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['CreatedDate'] . "</td>";
                echo "<td>" . $row['Ssn'] . "</td>";
                echo "<td>" . $row['Salary'] . "</td></tr>";
            }
			echo "</table><br>";
			$pdo->commit();
			
			$pdo->exec('UNLOCK TABLES');
			
			$pdo->null;
        }
        catch(PDOException $error){
            $pdo->rollBack();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());
        }
	?>
    
    <h2>All Product Records</h2>
    
    <?php
		print "<table style=width:100%><tr><th>Product_Image</th><th>ProductId</th><th>Product_Name</th><th>Description</th><th>Price</th><th>Num_In_Stock</th><th>Calories</th><th>Fats</th><th>Size</th><th>Boolean<br>(1 if true, 0 if false)</th></tr>";
		
		try{
			$pdo->beginTransaction();
			
			$stmt = $pdo->prepare('SELECT P.*, A.*, B.*, S.*, M.*, D.* FROM `Product` as P LEFT OUTER JOIN `Appetizer` as A ON P.ProductId=A.Pid LEFT OUTER JOIN `Beverage` as B ON P.ProductId=B.Pid LEFT OUTER JOIN `Salad` as S ON P.ProductId=S.Pid LEFT OUTER JOIN `MainDish` as M ON P.ProductId=M.Pid LEFT OUTER JOIN `Dessert` as D ON P.ProductId=D.Pid ORDER BY P.ProductId');
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				print "<tr>";
				print "<td>" . '<img src="data:image/jpeg;base64,'.base64_encode( $row[4] ).'"/>' . "</td>";
				print "<td>" . $row[0] . "</td>";
				print "<td>" . $row[1] . "</td>";
				print "<td>" . $row[2] . "</td>";
				print "<td>" . $row[3] . "</td>";
				print "<td>" . $row[5] . "</td>";
				print "<td>" . $row[6] . "</td>";
				print "<td>" . $row[7] . "</td>";
				if($row[9] != ''){
					print "<td>Size: " . $row[9] . "</td>";
				}
				if($row[11] != ''){
					print "<td>Size: " . $row[11] . "</td>";
				}
				if($row[13] != ''){
					print "<td>Size: " . $row[13] . "</td>";
					print "<td>HasDressing: " . $row[14] . "</td>";
				}
				if($row[16] != ''){
					print "<td>Size: " . $row[16] . "</td>";
					print "<td>IsHot: " . $row[17] . "</td>";
				}
				if($row[19] != ''){
					print "<td></td>";
					print "<td>IsCold: " . $row[19] . "</td>";
				}
				print "</tr>";
			}
			print "</table><br>";
			$pdo->commit();
			
			$pdo = null;
		}
		catch(PDOException $error){
			$pdo->rollBack();
			print("Rollingback <br>");
			die("ERROR: Could not complete. " . $error->getMessage());

		}
	?>
</body>
</html>