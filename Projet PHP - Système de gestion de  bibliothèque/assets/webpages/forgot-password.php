<?php
include 'db-connect.php';
session_start();
error_reporting(0);

if (isset($_POST['send-code'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $emailquery = "SELECT * FROM student WHERE email = '$email'";
  $check_email = mysqli_query($con, $emailquery);
  $emailcount = mysqli_num_rows($check_email);
  if ($emailcount > 0) {
    $ucode = rand(999999, 111111);
    $updatecode = "UPDATE student SET code='$ucode' WHERE email='$email'";
    $runquery = mysqli_query($con, $updatecode);
    if ($runquery) {
      $reciever_email = $email;
      $subject = "Code pour la connexion des étudiants";
      $message = "Votre code a été modifié et le nouveau code est $ucode";
      $sender = "De: codewithpawanofficial@gmail.com";
      if (mail($reciever_email, $subject, $message, $sender)) {
        $_SESSION['code'] = "Nous avons envoyé un code mis à jour à votre adresse e-mail. - $reciever_email";
        header('location: student-login.php');
      } else {
        $errors['otp-error'] = "Échec de l'envoi du code.!";
      }
    } else {
      $errors['otp-error'] = "Une erreur s'est produite lors de l'exécution de la requête.";
    }
  } else {
    $error['email'] = 'Enter Registered Email';
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Système de gestion de bibliothèque || Mot de passe oublié ?</title>
  <link rel="stylesheet" href="../css/index.css" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  
</head>

<body onload="preloader()">
  <?php include '../loader/loader.php' ?>

  <section class="forgot-pass">
    <div class="forgot-form">
      <h4>Mot de passe oublié </h4>
      <p>Entrez l'e-mail enregistré avec votre compte.</p>
      <?php
      if (isset($errors['otp-error'])) {
      ?>
        <p class="error">
          <?php echo $errors['otp-error']; ?>
        </p>
      <?php
      }
      ?>
      <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="input-field">
          <label for="email">E-mail *</label>
          <input type="email" name="email" id="email" placeholder="Email Address" />
          <?php
          if (isset($error['email'])) {
          ?>
            <p class="error">
              <?php echo $error['email']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <input type="submit" name="send-code" value="Submit">
        <p>Déjà inscrit ? <a href="student-login.php">Connexion</a></p>
      </form>
    </div>
  </section>
</body>

</html>