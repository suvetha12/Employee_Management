<?php error_reporting(0);
include('connection.php');
session_start();

$_SESSION['message']="";
$id=$_REQUEST['id'];
$uploadOk = 1;
$err_msg=1;
if (isset($_POST['submit'])) {
  $fileName = basename($_FILES["ppic"]["name"]);

  $targetFilePath = 'images/' . $fileName;
  $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
  $var1 = rand(1111,9999);
  $pic = $_FILES["ppic"]["name"];
  $dst = "./images/".$var1.$pic;
  $dst_db = "images/".$var1.$pic;

  if ($_FILES["ppic"]["size"] > 500000) {
  	$_SESSION['message']=$_SESSION['message']. "<br>File size is too large.";
  	$uploadOk = 0;
  }
  if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
  && $fileType != "gif" && $fileType != "JPG" ) {
  $_SESSION['message']=$_SESSION['message']. "<br>Please select only image file.";
  	$uploadOk = 0;
    $err_msg=0;
  }


if($err_msg==1)
{
  move_uploaded_file($_FILES["ppic"]["tmp_name"],$dst);
  		mysqli_query($conn, "UPDATE users SET pic='$dst_db' WHERE id=$id");
  		$_SESSION['message'] = "Picture updated!";
  		header('location: change.php?id='.$id);
}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Profile picture</title>
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
	<?php }

  $record = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
    $rec = mysqli_fetch_row($record);
  ?>
	<form method="post" action="change.php" name="empForm" enctype="multipart/form-data">
    <h1 style="color:blue;">Change Profile Picture</h1>

<div class="input-group">
  <label>Profile pic *</label>

  <?php

  if ($rec[5]!="") { ?><img src="<?php echo $rec[5]; ?>" height="500" width="500">
  <input type="hidden" name="ppicS" value="<?php echo $rec[5]; ?>">
  <?php

   }
$id=$rec[0];
?>
<input type="hidden" name="id" value="<?php echo $rec[0];?>">

 <input type="file" name="ppic" value="<?php echo $ppic; ?>" required>


  <button class="btn" type="submit" name="submit" style="background: #556B2F;" >update</button> &nbsp;
  <a href="index.php" class="btn" style="background: #556B2F;">Back</a>

  <!-- <input type="text" name="ppic" value="</html>"> -->
</div>
</form>
</body>
</html>
