<?php error_reporting(0);
session_start();
$_SESSION['message']="";
$ppic="";
include("connection.php");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Empoyee Details</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>


<?php
	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$record = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
		$rowcount=mysqli_num_rows($record);
		if ($rowcount == 1 ) {
			$n = mysqli_fetch_array($record);
			$name = $n['name'];
			$email = $n['email'];
			$phone = $n['phone'];
			$ppic = $n['pic'];
			$dob = $n['dob'];
			$address = $n['address'];
			$code = $n['code'];
			$dobr=explode("/",$dob);
		}
	}

	if (isset($_POST['update'])) {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
    $err_msg=1;
		$id=$_POST['id'];
		$code = $_POST['code'];
		$ppicS = $_POST['ppicS'];
		$address = $_POST['address'];


	$query = mysqli_query($conn, "SELECT * FROM users where email='$email'");
	$emailC = mysqli_fetch_row($query);

//print_r($emailC);
	if($emailC[0]!=null)
	{
		if($emailC[0]!=$id)
		{
	$_SESSION['message']=$_SESSION['message']."<br>Email id Already Exists";
		$err_msg=0;
	}
	}

	$query = mysqli_query($conn, "SELECT * FROM users where phone='$phone'");
	$phoneC = mysqli_fetch_row($query);
	$phoneA = mysqli_fetch_array($query);

	if($phoneC[0]!=null)
	{
		if($phoneC[0]!=$id)
		{
	$_SESSION['message']=$_SESSION['message']."<br>Phone number Already Exists";
		$err_msg=0;
	}
	}
		if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
		$_SESSION['message'] =$_SESSION['message']. "<br>Please Enter valid name";
		$err_msg=0;
		//$name="";
	?><script> document.empForm.name.focus();</script> <?php
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['message'] =$_SESSION['message']. "<br> Invalid email format";
		//$email="";
		$err_msg=0;
	}
	if (!preg_match('#[0-9]{10}#', $phone))
		{
			$_SESSION['message'] =$_SESSION['message']." <br>Enter 10 digit Phone number";
			//$phone="";
	    $err_msg=0;

		}

		$targetFilePath = 'images/' . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    $var1 = rand(1111,9999);
		$pic = $_FILES["ppic"]["name"];
	  $dst = "./images/".$var1.$pic;
		$dst_db = "images/".$var1.$pic;
if($ppicS==''|| $ppicS=='0')
  {
  $ppic=$dst_db;
	}
	else {
		$ppic=$ppicS;
	}


		if ($_FILES["ppic"]["name"]!='')
		{

 		move_uploaded_file($_FILES["ppic"]["tmp_name"],$dst);
		}

$dobm = $_POST['dobMonth'];
$dobd = $_POST['dobDay'];
$doby = $_POST['dobYear'];
$dob = $dobd.'/'.$dobm.'/'.$doby;

if (checkdate($dobm,$dobd,$doby)!='true')
{
$_SESSION['message'] = $_SESSION['message']. "<br>Invalid Date";
$err_msg=0;
}

if($err_msg==1)
{


		mysqli_query($conn, "UPDATE users SET name='$name',email='$email',phone='$phone',code='$code',dob='$dob',address='$address' WHERE id=$id");
		$_SESSION['message'] = "Details updated!";
		header('location: index.php?up=s');
	}
	}



?>
<body>


	<?php if (isset($_SESSION['message']) && $_SESSION['message']!="") {?>
		<div class="msg">
			<?php
				echo $_SESSION['message'];
				unset($_SESSION['message']);
		?>
		</div>
	<?php } ?>

	<form method="post" action="edit_user.php">

<h1 style="color:blue;">Edit Employee</h1>
		<input type="hidden" name="id" value="<?php echo $id; ?>">


		<div class="input-group">
			<label>Name *</label>
			<input type="text" name="name" value="<?php echo $name; ?>" required >
		</div>
		<div class="input-group">
			<label>Code *</label>
			<input type="text" name="code" readonly  value="<?php echo $code;?>">
		</div>

		<div class="input-group">
			<label>Email *</label>
			<input type="text" name="email" value="<?php echo $email; ?>" required>
		</div>
		<div class="input-group">
			<label>Phone No *</label>
			<input type="text" name="phone" value="<?php echo $phone; ?>" maxlength="10" required>
		</div>


		<div class="input-group">
			<label>Address *</label>
			<input type="text" name="address" value="<?php echo $address; ?>" required>
		</div>

		<div class="input-group">
			<label>Profile pic *</label>


			 <?php if ($ppic!="") { ?><img src="<?php echo $ppic; ?>" height="100" width="100">
       <input type="hidden" name="ppicS" value="<?php echo $ppic; ?>"> <a href="change.php?id=<?php echo $id;?>">Change </a>

			 <?php

			  }
     else { ?>
     	<input type="file" name="ppic" value="<?php echo $ppic; ?>" required>
  <?php   }
			  ?>
		</div>
		<div class="input-group">
			<label>DOB *</label>

				<select name="dobDay" required>
								<?php for($i=1;$i<=31;$i++) {
																		if($dobr[0]==$i) {
										echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
									 }
									 elseif($dobd==$i)
									 {

										 echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
									 }

									 else
									 {
										 echo '<option value='."$i".'>'."$i".'</option>';

									 }?>
  							<!--	echo '<option value='."$i".'>'."$i".'</option>'; -->

 <?php } ?>

 //echo selected="selected";
				</select>
				<!-- Birth Month -->
				<select name="dobMonth" required>

					<?php for($i=1;$i<=12;$i++) {
															if($dobr[1]==$i) {
							echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
						 }
						 elseif($dobm==$i)
						 {

							 echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
						 }
						 else
						 {
							 echo '<option value='."$i".'>'."$i".'</option>';

						 }
					 }?>

					</select>
				<!-- Birth Year -->

				<?php $doY=date('Y');
				$dispYear= $doY-16;?>
				<select name='dobYear' required>

					<?php for($i=1947;$i<=$dispYear;$i++) {
															if($dobr[2]==$i) {
							echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
						 }
						 elseif($doby==$i)
						 {

							 echo '<option selected =selected'.'value='."$i".'>'."$i".'</option>';
						 }
						 else
						 {
							 echo '<option value='."$i".'>'."$i".'</option>';

						 }
					 }?>

				</select>
		</div>

<button class="btn" type="submit" name="update" style="background: #556B2F;" >update</button> &nbsp;
<a href="index.php" class="btn" style="background: #556B2F;">Back</a>
</body>
