<?php

include 'koneksi.php';
session_start();
$userid = $_SESSION['userid'];

if(isset($_POST['update_profile'])){

   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

   mysqli_query($conn, "UPDATE `user` SET name = '$update_name', email = '$update_email' WHERE id = '$userid'") or die('query failed');

   $old_pass = $_POST['old_pass'];
   $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
   $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
   $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

   if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
      if($update_pass != $old_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         mysqli_query($conn, "UPDATE `user` SET password = '$confirm_pass' WHERE id = '$userid'") or die('query failed');
         $message[] = 'password updated successfully!';
      }
   }

   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'uploaded_img/'.$update_image;

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image is too large';
      }else{
         $image_update_query = mysqli_query($conn, "UPDATE `user` SET image = '$update_image' WHERE id = '$userid'") or die('query failed');
         if($image_update_query){
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'image updated succssfully!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">


</head>
<style>
.update-profile {
    min-height: 100vh;
    background-color: var(--light-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.update-profile form {
    padding: 20px;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    text-align: center;
    width: 700px;
    text-align: center;
    border-radius: 5px;
}

.update-profile form img {
    height: 200px;
    width: 200p;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 5px;
}

.update-profile form .flex {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 15px;
}

.update-profile form .flex .inputBox {
    width: 49%;
}

.update-profile form .flex .inputBox span {
    text-align: left;
    display: block;
    margin-top: 15px;
    font-size: 17px;
    color: var(--black);
}

.update-profile form .flex .inputBox .box {
    width: 100%;
    border-radius: 5px;
    background-color: var(--light-bg);
    padding: 12px 14px;
    font-size: 17px;
    color: var(--black);
    margin-top: 10px;
}

@media (max-width:650px) {
    .update-profile form .flex {
        flex-wrap: wrap;
        gap: 0;
    }

    .update-profile form .flex .inputBox {
        width: 100%;
    }
}
</style>

<body>

    <div class="update-profile">

        <?php
      $select = mysqli_query($conn, "SELECT * FROM `user` WHERE id = '$userid'") or die('query failed');
      if(mysqli_num_rows($select) > 0){
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

        <form action="../register.php" method="post" enctype="multipart/form-data">
            <?php
         if($fetch['image'] == ''){
            echo '<img src="images/default-avatar.png">';
         }else{
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }
         if(isset($message)){
            foreach($message as $message){
               echo '<div class="message">'.$message.'</div>';
            }
         }
      ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Username :</span>
                    <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
                    <span>your email :</span>
                    <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
                    <span>update your pic :</span>
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
                </div>
                <div class="inputBox">
                    <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                    <span>old password :</span>
                    <input type="password" name="update_pass" placeholder="enter previous password" class="box">
                    <span>new password :</span>
                    <input type="password" name="new_pass" placeholder="enter new password" class="box">
                    <span>confirm password :</span>
                    <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
                </div>
            </div>
            <input type="submit" value="update profile" name="update_profile" class="btn">
            <a href="home.php" class="delete-btn">go back</a>
        </form>

    </div>

</body>

</html>