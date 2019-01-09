<html>
<head>
<title>Customer table</title>
<style type="text/css">
	table, th, td {
		border: 1px solid black;
	}
	table tr:nth-child(odd) {
		background-color: #ccc;
	}
</style>
</head>
<body>
    
	<?php require('config.php');?>
	<h2>All Customer Records</h2>
	
	<p><a href="http://localhost:8888/form-customer.php">&lt;Add a Customer&gt;</a></p>
    
	<?php
        echo "<table style=width:100%><tr><th>ID No.</th><th>Username</th><th>Password</th><th>Name</th>
        <th>Address</th><th>Phone No.</th><th>Email</th><th>Created by</th><th>Credits</th></tr>";
        
		echo '<form action="delete-customer.php" method="get">';
		
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
                echo "<td>" . $row['Credits'] . "</td>";
				echo "<td><a href='delete-customer.php?id=$id'>Delete</a></td></tr>";
            }
			echo "</table><br></form>";
            $pdo->commit();
			
			$pdo->exec('UNLOCK TABLES');
			
			$pdo = null;
        }
        catch(PDOException $e){
            $pdo->rollBack();
        }
    ?>
    
	<p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>
</body>
</html>