<?php
include 'db-connect.php';
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['student-login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

   
    $error = [];

   
    if (empty($email)) {
        $error['email'] = "L'e-mail ne doit pas être vide";
    }
    if (empty($password)) {
        $error['pass'] = "Le mot de passe ne doit pas être vide";
    }

    if (empty($error)) {
        $emailquery = "SELECT * FROM student WHERE email = '$email'";
        $check_email = mysqli_query($con, $emailquery);
        $emailcount = mysqli_num_rows($check_email);

        if ($emailcount > 0) {
            $fetch = mysqli_fetch_assoc($check_email);
            $fetch_code = $fetch['code']; 

            
            if ($password === $fetch_code) {
                $_SESSION['std-name'] = $fetch['name'];
                $_SESSION['std-email'] = $fetch['email']; 
                $_SESSION['stdloggedin'] = true;

                
                header('Location: ../panel/student/std-dashboard.php');
                exit; 
            } else {
                $error['pass'] = 'Incorrect password. Please enter the correct code.';
                $_SESSION['stdloggedin'] = false;
            }
        } else {
            $error['email'] = 'Email not found. Please enter a registered email.';
            $_SESSION['stdloggedin'] = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Système de gestion de bibliothèque || Connexion Étudiant</title>
    <link rel="stylesheet" href="../css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <style>
        .input-field .error {
            color: #FF3333;
            font-size: 14px;
        }
    </style>

    <section class="login">
        <form class="login-form" action="" method="POST">
            <h4>Connexion Étudiant</h4>

            <div class="input-form">
                <div class="input-field">
                    <label for="email">Email *</label>
                    <input type="email" name="email" id="email" placeholder="Your Email" value="<?php echo isset($email) ? htmlentities($email) : ''; ?>">
                    <?php if (isset($error['email'])) { echo "<p class='error'>{$error['email']}</p>"; } ?>
                </div>

                <div class="input-field">
                    <label for="password">mot de passe *</label>
                    <input type="password" maxlength="6" name="password" id="password" placeholder="Enter Code">
                    <?php if (isset($error['pass'])) { echo "<p class='error'>{$error['pass']}</p>"; } ?>
                </div>

                <p>oublié mot de passe? <a href="forgot-password.php">Cliquez ici</a></p>
                <input type="submit" name="student-login" value="Login">
            </div>
        </form>
    </section>
</body>
</html>
