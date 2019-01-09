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
  include 'update-product.php';

  // defining variables
  $catErr = "";
  $cat = $pid = $pname = $descrip = $image = $size = $price = $stock = $cal = $fats = $istrue = "";

  if($_SERVER["REQUEST_METHOD"] == "POST"){
	  	$cat = $_POST["Category"];
	  	$pid = $_POST["ProductID"];
	  	$pname = $_POST["PName"];
		$descrip = $_POST["Description"];
		$price = $_POST["Price"];
		$image =file_get_contents($_FILES['Image']['tmp_name']);
		$stock = $_POST["Stock"];
		$cal = $_POST["Calories"];
		$fats = $_POST["Fats"];
		$size= $_POST["Size"];
		$istrue = $_POST["IsTrue"];
		if($cat == ""){
			$catErr = "A category must be given.";
		}
		else if($cat == "Appetizer"){
			update_appetizer($pid, $pname, $descrip, $price, $image, $stock, $cal, $fats, $size);
		}
		else if($cat == "Beverage"){
			update_beverage($pid, $pname, $descrip, $price, $image, $stock, $cal, $fats, $size);
		}
		else if($cat == "Salad"){
			update_salad($pid, $pname, $descrip, $price, $image, $stock, $cal, $fats, $size, $istrue);
		}
		else if($cat == "MainDish"){
			update_maindish($pid, $pname, $descrip, $price, $image, $stock, $cal, $fats, $size, $istrue);
		}
		else if($cat == "Dessert"){
			update_dessert($pid, $pname, $descrip, $price, $image, $stock, $cal, $fats, $istrue);
		}
    }

  ?>

  <h2>Order Input Form</h2>

  <p><span class="error">* required field.</span></p>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"  enctype="multipart/form-data">
  	Size: <select name="Category">
    	<option value="">Select...</option>
		<option value="Appetizer">Appetizer</option>
		<option value="Beverage">Beverage</option>
		<option value="Salad">Salad</option>
		<option value="MainDish">Main Dish</option>
		<option value="Dessert">Dessert</option>
 	</select><span class="error">* <?php echo $catErr;?></span><br><br>
  
    Product ID: <input type="number" name="ProductID" value="<?php echo $pid;?>"><br><br>
    
    Product name: <input type="text" name="PName" value="<?php echo $pname;?>" maxlength="32"><br><br>

  	Description: <input type="text" name="Description" value="<?php echo $descrip;?>" rows="3" cols="40" maxlength="256"><br><br>

  	Price: <input type="number" step="0.01" name="Price" value="<?php echo $price;?>" maxlength="1"><br><br>

  	Image: <input type="file" name="Image" value="Upload image"><br><br>

	Stock: <input type="number" name="Stock" value="<?php echo $stock;?>" min="0"><br><br>

	Calories: <input type="number" name="Calories" value="<?php echo $cal;?>" min="0"><br><br>

  	Fats: <input type="number" name="Fats" value="<?php echo $fats;?>" min="0"><br><br>
    
    Size: <select name="Size">
    	<option value="">Select...</option>
		<option value="S">S</option>
		<option value="M">M</option>
		<option value="L">L</option>
 	</select><br><br>
    Is True: <select name="IsTrue">
    	<option value="">Select...</option>
		<option value="1">True</option>
		<option value="0">False</option>
        </select>
        For Desert, IsCold. For MainDish, IsHot. For Salda, HasDressing.<br><br>

	<input type="submit" name="submit" value="Submit">
	</form>
  <p>Click the "Submit" button to input Order data.</p>

	<?php include 'show-products.php'; ?>
  
</body>
</html>
