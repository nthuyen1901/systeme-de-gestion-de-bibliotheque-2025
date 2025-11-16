<?php
session_start();
error_reporting(0);

include 'db-connect.php';
if (isset($_POST['register'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
  $mobile = mysqli_real_escape_string($con, $_POST['mobile']);


  $emailquery = "SELECT * FROM librarian WHERE email='$email'";
  $query = mysqli_query($con, $emailquery);
  $emailcount = mysqli_num_rows($query);
  if ($emailcount > 0) {
    $error['lib-msg'] = 'email already exist';
?>
    <script>
      setTimeout(() => {
        location.replace("librarian-login.php");
      }, 2000)
    </script>
    <?php
  } else {
    if ($name == "") {
      $error['name'] = "Le nom ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $error['name'] = "Seules les lettres alphabétiques sont autorisées";
    }
    if ($password == "") {
      $error['pass'] = "Le mot de passe ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z0-9].{8,}/", $password)) {
      $error['pass'] = "Le mot de passe doit contenir au minimum 8 caractères et inclure uniquement des lettres et des chiffres.";
    }
    if($cpassword == ""){
      $error['cpass'] = "La confirmation du mot de passe ne doit pas être vide.";
    }else if ($password !== $cpassword) {
      $error['cpass'] = "La confirmation du mot de passe ne correspond pas.";
    }
    if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
      $error['email'] = "Veuillez entrer une adresse e-mail valide.";
    }else if($email == ""){
      $error['email'] = "L'e-mail ne doit pas être vide.";
    }
    if (!preg_match("/^[0-9]{10}+$/", $mobile)) {
      $error['mobile'] = "Veuillez entrer un numéro de téléphone valide.";
    } else if($mobile == ""){
      $error['mobile'] = "Le champ du numéro de téléphone ne doit pas être vide.";
    }else {
      if (!isset($error)) {
        $insertquery = "INSERT INTO librarian (name,email,password,cpassword,mobile) VALUES ('$name','$email','$password','$cpassword','$mobile')";
        $query = mysqli_query($con, $insertquery);
        if ($query) {
          $error['lib-msg'] = "Vous vous êtes inscrit avec succès. Connectez-vous maintenant avec votre compte.";
        }
    ?>
        <script>
          setTimeout(() => {
            location.replace("librarian-login.php");
          }, 2000)
        </script>
<?php
      }
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
  <title>Système de gestion de bibliothèque || Formulaire d'inscription pour le bibliothécaire</title>
  <link rel="stylesheet" href="../css/index.css">
 
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  
</head>

<body onload="preloader()">
  <style>
    .input-field .error {
      color: #FF3333;
      font-size: 14px;
    }
  </style>
  <?php include '../loader/loader.php' ?>

  <section class="registration">
    <div class="registration-form">
      <h4>S'inscrire</h4>
      <p>Veuillez vous inscrire en tant que bibliothécaire pour l'émission de livres aux étudiants.</p>
      <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <?php
        if (isset($error['lib-msg'])) {
        ?>
          <p>
            <?php echo $error['lib-msg']; ?>
          </p>
        <?php
        }
        ?>
        <div class="input-field">
          <label for="name">Nom complet *</label>
          <input type="text" name="name" id="name" placeholder="Your Name">
          <?php
          if (isset($error['name'])) {
          ?>
            <p class="error">
              <?php echo $error['name']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <div class="input-field">
          <label for="email">Email *</label>
          <input type="text" name="email" id="email" placeholder="Your Email">
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
        <div class="input-field">
          <label for="password">mot de passe *</label>
          <input type="password" name="password" id="password" placeholder="Password">
          <?php
          if (isset($error['pass'])) {
          ?>
            <p class="error">
              <?php echo $error['pass']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <div class="input-field">
          <label for="cpassword">Confirmer le mot de passe *</label>
          <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">
          <?php
          if (isset($error['cpass'])) {
          ?>
            <p class="error">
              <?php echo $error['cpass']; ?>
            </p>
          <?php
          }
          ?>
          
        </div>
        <div class="input-field">
          <label for="mobileno">Numéro de téléphone. *</label>
          <input type="text" maxlength="10" name="mobile" id="mobileno" placeholder="Mobile No.">
          <?php
          if (isset($error['mobile'])) {
          ?>
            <p class="error">
              <?php echo $error['mobile']; ?>
            </p>
          <?php
          }
          ?>
        </div>
        <input type="submit" name="register" id="signup" value="Register">
        <p>Vous avez déjà un compte ? <a href="librarian-login.php">Connectez-vous maintenant</a></p>
      </form>
    </div>
  </section>


</body>

</html>