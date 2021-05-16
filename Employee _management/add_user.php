<?php error_reporting(0);
 include('connection.php');
session_start();
$_SESSION['message']="";
$ppic = "";
$name = "";
$address = "";
$email = "";
$phone = "";
$update = false;
$uploadOk = 1;
$err_msg=1;

if (isset($_POST['submit'])) {
$max_id = mysqli_query($conn, "SELECT MAX(id) as max_id FROM users");
$row = mysqli_fetch_row($max_id);
$highest_id = $row[0];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
	$_SESSION['message'] =$_SESSION['message']. "<br> Please Enter valid name";
	$err_msg=0;
?><script> document.empForm.name.focus();</script> <?php
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$_SESSION['message'] =$_SESSION['message']. "<br> Invalid email format";
	$err_msg=0;
}
if (!preg_match('#[0-9]{10}#', $phone))
	{
		$_SESSION['message'] =$_SESSION['message']." <br>Enter 10 digit Phone number";
    $err_msg=0;

	}

  $query = mysqli_query($conn, "SELECT * FROM users where email='$email'");
  $emailC = mysqli_fetch_row($query);
  if($emailC[0]!=null)
  {
  $_SESSION['message']=$_SESSION['message']."<br>Email id Already Exists";
  	$err_msg=0;
  }

  $query = mysqli_query($conn, "SELECT * FROM users where phone='$phone'");
  $phoneC = mysqli_fetch_row($query);
  if($phoneC[0]!=null)
  {
  $_SESSION['message']=$_SESSION['message']."<br>Phone number Already Exists";
  	$err_msg=0;
  }

$id1=$highest_id+1;
$code='DCKAP'.$id1;
$fileName = basename($_FILES["ppic"]["name"]);

$targetFilePath = 'images/' . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$var1 = rand(1111,9999);
$pic = $_FILES["ppic"]["name"];
$dst = "./images/".$var1.$pic;
$dst_db = "images/".$var1.$pic;

// Check file size
if ($_FILES["ppic"]["size"] > 5000000) {
	$_SESSION['message']=$_SESSION['message']. "<br>File size is too large.";
	$uploadOk = 0;
  	$err_msg=0;
}
if($fileType == "jpg" || $fileType == "png" || $fileType == "jpeg"
|| $fileType == "gif" || $fileType == "JPG" ) {
  move_uploaded_file($_FILES["ppic"]["tmp_name"],$dst);
}
else {

$_SESSION['message']=$_SESSION['message']. "<br>Please select only image file.";
	$uploadOk = 0;
  	$err_msg=0;
}


$dobm = $_POST['dobMonth'];
$dobd = $_POST['dobDay'];
$doby = $_POST['dobYear'];
$dob = $dobd.'/'.$dobm.'/'.$doby;
$address = $_POST['address'];
if (checkdate($dobm,$dobd,$doby)!='true')
{
$_SESSION['message'] = $_SESSION['message']. "<br>Invalid Date";
$err_msg=0;
}


if($err_msg==1)
{

mysqli_query($conn, "INSERT INTO users (name, email,phone,code,pic,dob,address,status,del_status) VALUES ('$name','$email','$phone','$code','$dst_db','$dob', '$address','1','0')");
$_SESSION['message'] = "Employee created successfully";
header('location: index.php');
}
else
{
	//$_SESSION['message']=$_SESSION['message']. "<br> Problem not Inserted in the Database";
//header('location: add_user.php');
}
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Empoyee Details</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php if (isset($_SESSION['message']) && $_SESSION['message']!="") {?>
		<div class="msg">
			<?php
				echo $_SESSION['message'];
				unset($_SESSION['message']);
		?>
		</div>
	<?php } ?>
	<form method="post" action="add_user.php" name="empForm" enctype="multipart/form-data">
    <h1 style="color:blue;">Add Employee</h1>

    <div class="input-group">
			<label>Name *</label>
			<input type="text" name="name" value="<?php echo $name; ?>" required>
		</div>

		<div class="input-group">
			<label>Email *</label>
			<input type="text" name="email" value="<?php echo $email; ?>" required>
		</div>
		<div class="input-group">
			<label>Phone No *</label>
			<input type="text" name="phone" value="<?php echo $phone; ?>" maxlength="10" required">
		</div>
		<div class="input-group">
			<label>Address *</label>
			<input type="text" name="address" value="<?php echo $address; ?>" required">
		</div>
		<div class="input-group">
			<label>Profile pic *</label>


      <input type="file" name="ppic" value="<?php echo $pic; ?>" Required>

			<!-- <input type="text" name="ppic" value=""> -->
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

  <?php } ?>

  //echo selected="selected";
        </select>

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


		<div class="input-group">
			<button class="btn" type="submit" name="submit">Save</button> &nbsp; <a href="index.php" class="btn">Back</a>
		</div>
	</form>
</body>
<script type="text/javascript">


</script>
</html>
