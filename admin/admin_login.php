<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../components/connect.php';

session_start();

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_DEFAULT);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_DEFAULT);

   // Display values for debugging
   echo "Submitted Name: $name <br>";
   echo "Submitted Password: $pass <br>";

   $passHash = sha1($pass);

   // Display hashed password for debugging
   echo "Hashed Password: $passHash <br>";

   try {
      // Echo the SQL query for debugging
      echo "SQL Query: SELECT * FROM `admins` WHERE name = '$name' AND password = '$passHash'<br>";

      $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = :name AND password = :pass");
      $select_admin->bindParam(':name', $name);
      $select_admin->bindParam(':pass', $passHash);
      $select_admin->execute();

      if($select_admin->rowCount() > 0){
          $row = $select_admin->fetch(PDO::FETCH_ASSOC);
          $_SESSION['admin_id'] = $row['id'];
          $message[] = 'Login sucessfully!';
          header('location:dashboard.php');
          exit();
      }else{
          $message[] = 'incorrect username or password!';
      }
   } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>login now</h3>
      <p>default username = <span>admin</span> & password = <span>111</span></p>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>