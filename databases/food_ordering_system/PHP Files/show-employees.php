<html>
<head>
<title>Employee table</title>
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
        catch(PDOException $e){
            $pdo->rollBack();
        }
        $pdo->exec('UNLOCK TABLES');
    ?>
    
	<p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>
</body>
</html>