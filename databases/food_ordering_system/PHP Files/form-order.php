<!DOCTYPE HTML>
<html>
<head>
<title> Form for Order</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
  include 'insert-order.php';

  // defining variables
  $cidErr = $eidErr = $pidErr = "";
  $cid = $eid = $pid = $deliver = 0;

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cid = $_POST["CustId"];
    $eid = $_POST["EmpId"];
    $pid = $_POST["ProdID"];
    $deliver = $_POST["Is_Delivery"];

    if($cid <= 0) {
      $cidErr = "Customer ID is required";
    }

    if($eid <= 0) {
      $eidErr = "Employee ID is required";
    }

    if($pid <= 0) {
      $pidErr = "Product ID is required";
    }

    if($cidErr == "" && $eidErr == "" && $pidErr == "") {
      echo "Form has been sent.<br>";
      insert_order($cid,$eid,$pid,$deliver);
      echo "Query has been sent<br>";
    }

  }

  ?>

  <h2>Order Input Form</h2>

  <p><span class="error">* required field.</span></p>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    Customer ID: <input type="number" name="CustId" value="<?php echo $cid;?>">
      <span class="error">* <?php echo $cidErr;?></span><br><br>

    Employee ID: <input type="number" name="EmpId" value="<?php echo $eid;?>">
      <span class="error">* <?php echo $eidErr;?></span><br><br>

	 Product ID: <select name = "ProdID">
   	<?php 
		include 'config.php';
		$pdo->beginTransaction();
        
        $pdo->exec('LOCK TABLES `Product` WRITE');
		
		try{
			$sql = 'SELECT Product_Name, ProductId FROM Product WHERE Num_In_Stock > 0 ORDER BY ProductID;';
            foreach ($pdo->query($sql) as $row) {
				echo '<option value=' . $row['ProductId'] . '>' . $row['Product_Name'] . '</option>';
			}
            $pdo->commit();
	  
		    $pdo->exec('UNLOCK TABLES');
		    print "Unlock table. Successful transaction<br>";
		
		    $pdo = null;
		}
		catch(PDOException $e){
            $pdo->rollBack();
        }
		
	?>
    </select>
    <span class="error">* <?php echo $pidErr;?></span><br><br>
	

    Is Delivery: <input type="number" name="Is_Delivery" value="<?php echo $deliver;?>" min="0" max="1">
    	&nbsp;&nbsp;&nbsp;&nbsp;1 if delivery, 0 if not. <br><br>

    <input type="submit" name="submit" value="Submit">
  </form>
  <p>Click the "Submit" button to input Order data.</p>

<?php include 'show-products.php'; ?>

  <p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>
  
</body>
</html>
