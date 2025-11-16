<?php
include '../../../webpages/db-connect.php';
session_start();
error_reporting(0);
if (!isset($_SESSION['lib-name'])) {
  header("location: ../../../webpages/librarian-login.php");
}

if (isset($_POST['update-pass'])) {
  $name = $_SESSION['lib-name'];
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
  if ($password == "") {
    $error['pass'] = "Veuillez entrer le mot de passe";
  } else if (!preg_match("/.{8,}/", $password)) {
    $error['pass'] = "Le nouveau mot de passe doit contenir au minimum 8 caractères";
  }
  if ($password !== $cpassword) {
    $error['cpass'] = "La confirmation du mot de passe ne correspond pas";
  } else if ($cpassword == "") {
    $error['cpass'] = "Veuillez entrer la confirmation du mot de passe";
  } else if (!preg_match("/.{8,}/", $password)) {
    $error['cpass'] = "Le nouveau mot de passe doit contenir au minimum 8 caractères";
  } else {
    $pass = password_hash($password, PASSWORD_BCRYPT);
    $cpass = password_hash($cpassword, PASSWORD_BCRYPT);
    $updatepass = "UPDATE librarian SET password='$pass',cpassword='$cpass' WHERE name='$name'";
    $query = mysqli_query($con, $updatepass);
    if ($query) {
      $error['pass-msg'] = "Votre mot de passe a été mis à jour avec succès";
?>
      <script>
        setTimeout(() => {
          document.querySelector(".error").style.display = "none";
        }, 2000);
      </script>
    <?php
    } else {
      $error['pass-msg'] = "Votre mot de passe n'a pas été mis à jour. Veuillez réessayer";
    ?>
      <script>
        setTimeout(() => {
          document.querySelector(".error").style.display = "none";
        }, 2000);
      </script>
<?php
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Système de gestion de bibliothèque || Mettre à jour le mot de passe</title>
  <link rel="stylesheet" href="../../css/main.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />

  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
</head>

<body onload="preloader()">
  <?php include 'sidebar.php'; ?>
  <section class="home-section">
    <div class="home-content">
      <i class="fa-solid fa-bars"></i>
      <div class="logout">
        <button><a href="logout.php">Se déconnecter</a></button>
      </div>
    </div>
    <?php
    if (isset($error['pass-msg'])) {
    ?>
      <p class="error">
        <?php echo $error['pass-msg']; ?>
      </p>
    <?php
    }
    ?>
    <div class="control-panel">
      <h4>Mettre à jour le mot de passe</h4>
      <div class="container">
        <div class="update-pass-form data-form">
          <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <div class="input-field">
              <label for="password">Nouveau mot de passe *</label>
              <input type="password" id="password" name="password" placeholder="Enter New Password" />
              <?php
              if (isset($error['pass'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['pass']; ?>
                </p>
              <?php
              }
              ?>
            </div>
            <div class="input-field">
              <label for="cpassword">Confirmer le nouveau mot de passe*</label>
              <input type="password" id="cpassword" name="cpassword" placeholder="Enter New Password Again" />
              <?php
              if (isset($error['cpass'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['cpass']; ?>
                </p>
              <?php
              }
              ?>
            </div>
            <input type="submit" value="Update Password" name="update-pass">
          </form>
        </div>
      </div>
    </div>
  </section>


  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

  <script src="../../js/main.js"></script>
</body>

</html>