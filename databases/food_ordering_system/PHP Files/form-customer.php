<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
		include 'insert-customer.php';
		
		// define variables and set to empty values
		$nameErr = $passwordErr = $finitErr = $lnameErr = $addressErr = $phoneErr = $emailErr = "";
		$name = $password = $finit = $lname = $address = $phone  = $email = "";
		$credits = 0;
		
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			  $name = test_input($_POST["UserName"]);
			  $password = test_input($_POST["Password"]);
			  $finit = test_input($_POST["Finit"]);
			  $lname = test_input($_POST["LastName"]);
			  $address = test_input($_POST["Address"]);
			  $phone = test_input($_POST["Phoneno"]);
			  $email = test_input($_POST["Email"]);
			  $credits = $_POST["Credits"];
			  
			  if (empty($name)) {
				$nameErr = "Name is required";
			  }else if (strlen($name) < 8){
				  $nameErr = "Has to be between 8 to 12 characters.";
			  }  else {
				if (!preg_match("/^[a-zA-Z]*$/",$name)) {
				  $nameErr = "Only letters allowed"; 
				}
			  }
			  
			  if (empty($password)) {
				  $passwordErr = "Password is required";
			  } else if (strlen($password) < 8){
				  $passwordErr = "Has to be between 8 to 12 characters.";
			  } else {
				  if (!preg_match("/^[a-zA-Z0-9!?-]*$/",$password)) {
					$passwordErr = "Only numbers, letters, and special characters (! ? -) allowed allowed."; 
				  }
			  }
			  
			  if (empty($finit)) {
				$finitErr = "First initial is required";
			  } else {
				if (!preg_match("/^[a-zA-Z]*$/",$finit)) {
				  $finitErr = "Only one letter allowed"; 
				}
			  }
			  
			  if (empty($lname)) {
				$lnameErr = "Last Name is required";
			  } else {
				if (!preg_match("/^[a-zA-Z-]*$/",$lname)) {
				  $lnameErr = "Only letters and dashes allowed"; 
				}
			  }
			  
			  if (empty($address)) {
				$addressErr = "First initial is required";
			  }
			  
			  if (empty($phone) || strlen($phone) < 12) {
				$phoneErr = "Phone number is required";
			  } else {
				if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",$phone)) {
				  $phoneErr = "Invalid Phone-no format"; 
				}
			  }
			  
			  if (empty($_POST["Email"])) {
				$emailErr = "Email is required";
			  } else {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  $emailErr = "Invalid email format"; 
				}
			  }
			  if($nameErr == "" && $passwordErr == "" && $finitErr == "" && $lnameErr == "" && $addressErr == "" && $phoneErr == "" && $emailErr == ""){
					echo "Form has been sent.<br>";
				  	insert_customer($name, $password, $finit, $lname, $address, $phone , $email, $credits);
					echo "Query has been sent<br>";
					
			  }
		}
		
		function test_input($data) {
		  $data = trim($data);
		  $data = stripslashes($data);
		  $data = htmlspecialchars($data);
		  return $data;
		}
	?>
    <p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>
	
	<h2>Customer Input Form</h2>
    
    <p><span class="error">* required field.</span></p>   
    
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	Username: <input type="text" name="UserName" value="<?php echo $name;?>" maxlength="12">
    	<span class="error">* <?php echo $nameErr;?></span><br><br>
        
  Password: <input type="text" name="Password" value="<?php echo $password;?>" maxlength="12"> 
    	<span class="error">* <?php echo $passwordErr;?></span><br><br>
        
  First Initial: <input type="text" name="Finit" value="<?php echo $finit;?>" maxlength="1">
    	<span class="error">* <?php echo $finitErr;?></span><br><br>
        
  Last name: <input type="text" name="LastName" value="<?php echo $lname;?>" maxlength="32">
    	<span class="error">* <?php echo $lnameErr;?></span><br><br>
        
	Address: <input type="text" name="Address" value="<?php echo $address;?>" maxlength="256">
    	<span class="error">* <?php echo $addressErr;?></span><br><br>
        
	Phone no: <input type="text" name="Phoneno" value="<?php echo $phone;?>" maxlength="12">
    	<span class="error">* <?php echo $phoneErr;?></span><br><br>
        
  Email: <input type="text" name="Email" value="<?php echo $email;?>" maxlength="100">
    	<span class="error">* <?php echo $emailErr;?></span><br><br>
        
	Credits: <input type="number" name="Credits" value="0" min="0"><br>
	<input type="submit" name="submit" value="Submit">  
	</form>
	<p>Click the "Submit" button to input Customer data.</p>
    
    <?php include 'show-customers.php'; ?>
	
    
</body>
</html>
