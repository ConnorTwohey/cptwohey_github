<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
	<?php
        include 'insert-maindish.php';
        
		$pnameErr = $descripErr = $priceErr = $imageErr = $stockErr = $calErr = $fatsErr = $sizeErr = $ishotErr = "";
        $pname = $descrip = $image = $size = "";
        $price = $stock = $cal = $fats = $ishot = 0;
    
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$pname = $_POST["PName"];
			$descrip = $_POST["Description"];
			$price = $_POST["Price"];
			$image =file_get_contents($_FILES  ['Image']['tmp_name']);	
			$stock = $_POST["Stock"];
			$cal = $_POST["Calories"];
			$fats = $_POST["Fats"];
			$size= $_POST["Size"];
			$ishot = $_POST["Is_Hot"];
	
			if (empty($pname)) {
				$pnameErr = "Name is required";
			}
	
			if (empty($descrip)) {
				  $descripErr = "Description is required";
			}
	
			if ($price == "" || $price <= 0) {
				$priceErr = "Price has to be above 0";
			}
	
			if (empty($image)) {
				$imageErr = "Image is required";
			}
	
			  if ($price == "" || $stock < 0) {
				$stockErr = "Number in stock is required";
			  }
	
			  if ($cal == "" || $cal < 0) {
				$calErr = "Calories are required";
			  }
	
			  if ($fats == "" || $fats < 0) {
				$fatsErr = "Fats are required";
			  }
			  if (empty($size)) {
				  $sizeErr = "Size is required";
			  }
			  if ($ishot == "" ||$ishot > 1 || $ishot < 0) {
				$ishotErr = "Is Hot is required";
			  }
	
			  if($pnameErr == "" && $descripErr == "" && $priceErr == "" && $imageErr == "" && $stockErr == "" && $calErr == "" && $fatsErr == "" && $sizeErr == "" && $ishotErr == ""){
					echo "Form has been sent.<br>";
					insert_maindish($pname, $descrip, $price, $image, $stock, $cal, $fats, $size, $ishot);
					echo "Query has been sent<br>";
	
			  }
		}
	?>

  <h2>Main Dish Input Form</h2>

  <p><span class="error">* required field.</span></p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
	Product name: <input type="text" name="PName" value="<?php echo $pname;?>" maxlength="32">
    	<span class="error">* <?php echo $pnameErr;?></span><br><br>
        
  	Description: <input type="text" name="Description" value="<?php echo $descrip;?>" rows="3" cols="40" maxlength="256"> 
    	<span class="error">* <?php echo $descripErr;?></span><br><br>
        
  	Price: <input type="number" step="0.01" name="Price" value="<?php echo $price;?>" maxlength="1">
    	<span class="error">* <?php echo $priceErr;?></span><br><br>
        
  	Image: <input type="file" name="Image" value="Upload Image">
    	<span class="error">* <?php echo $imageErr;?></span><br><br>
        
	Stock: <input type="number" name="Stock" value="<?php echo $stock;?>" min="0">
    	<span class="error">* <?php echo $stockErr;?></span><br><br>
        
	Calories: <input type="number" name="Calories" value="<?php echo $cal;?>" min="0">
    	<span class="error">* <?php echo $calErr;?></span><br><br>
        
  	Fats: <input type="number" name="Fats" value="<?php echo $fats;?>" min="0">
    	<span class="error">* <?php echo $fatsErr;?></span><br><br>
   	Size: <select name="Size">
    	<option value="">Select...</option>
		<option value="S">S</option>
		<option value="M">M</option>
		<option value="L">L</option>
 	</select><span class="error">* <?php echo $sizeErr;?></span><br><br>
    		
    Is Hot: <select name = "Is_Hot">
    	<option value="">Please select as size</option>
    	<option value="1">True</option>
    	<option value="0">False</option>
     </select><br><br><span class="error">* <?php echo $ishotErr;?></span><br><br>
	<input type="submit" name="submit" value="Submit">  
	</form>
	<p>Click the "Submit" button to input Dessert data.</p>
<p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>

</body>
</html>
