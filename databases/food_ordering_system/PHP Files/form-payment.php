<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
  include 'insert-payment.php';

  // defining variables
  $oid = $cid = 0;
  $payment = "";

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cid = $_POST["CustId"];
    $oid = $_POST["Oid"];
    $payment = $_POST["Payment_Method"];

    if(empty($cid)) {
      $cidErr = "Customer ID is required";
    }

    if(empty($oid)) {
      $oidErr = "Order ID is required";
    }

    if(empty($payment)) {
      $payErr = "Method of Payment is required";
    }

    if($cidErr == "" && $oidErr == "" && $payErr == "") {
      echo "Form has been sent.<br>";
      insert_payment($oid,$cid,$payment);
      echo "Query has been sent<br>";
    }

  }

  ?>

  <h2>Payment Form</h2>

  <p><span class="error">* required field.</span></p>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    Customer ID: <input type="text" name="CustId" value="<?php echo $cid;?>">
      <span class="error">* <?php echo $cidErr;?></span><br><br>

    Order ID: <input type="text" name="Oid" value="<?php echo $eid;?>">
      <span class="error">* <?php echo $oidErr;?></span><br><br>

    Method of Payment: <input type="text" name="Payment_Method" value="<?php echo $pid;?>">
      <span class="error">* <?php echo $payErr;?></span><br><br>

    <input type="submit" name="submit" value="Submit">
  </form>
  <p>Click the "Submit" button to input Order data.</p>
  <p><a href="http://localhost:8888/">&lt;Back to index&gt;</a></p>

</body>
</html>
