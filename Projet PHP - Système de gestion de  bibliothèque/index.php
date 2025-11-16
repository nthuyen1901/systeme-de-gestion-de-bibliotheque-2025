<?php
session_start();
error_reporting(0);
include 'assets/webpages/db-connect.php';

$bookdata = "SELECT * FROM book ORDER BY id DESC LIMIT 8";
$result = mysqli_query($con, $bookdata);

?>
<?php

if (isset($_POST['contact'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
  $message = mysqli_real_escape_string($con, $_POST['message']);

  if ($name == "") {
    $error['name'] = "Le nom ne doit pas être vide";
  } else if (!preg_match("/^[a-zA-Z\s]*$/", $name)) {
    $error['name'] = "Seules les lettres alphabétiques sont autorisées";
  }
  if ($email == "") {
    $error['email'] = "Veuillez entrer une adresse e-mail";
  } else if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
    $error['email'] = "Veuillez entrer une adresse e-mail valide";
  }
  if ($mobile == "") {
    $error['mobile'] = "Veuillez entrer un numéro de téléphone";
  } else if (!preg_match("/^[0-9]{10}+$/", $mobile)) {
    $error['mobile'] = "Veuillez entrer un numéro de téléphone valide";
  }
  if ($message == "") {
    $error['message'] = "Le message ne doit pas être vide";
  } else if (!preg_match("/^[a-zA-Z0-9.,\s]*$/", $message)) {
    $error['message'] = "Seules les lettres alphabétiques sont autorisées";
  } else {
    if (!isset($error)) {
      $insertdata = "INSERT INTO contacttable(name,email,mobile,message) VALUES('$name','$email','$mobile','$message')";
      $runquery = mysqli_query($con, $insertdata);
      if ($runquery) {
        $reciever_email = $email;
        $subject = "Merci de nous avoir contactés | Système de Gestion de Bibliothèque";
        $body = "Bonjour " . $name . ",

Merci de nous avoir contactés. Nous avons bien reçu votre demande concernant la bibliothèque.
Nous allons traiter votre demande et nous reviendrons vers vous bientôt.
        
Vous pouvez également nous contacter directement ou partager vos demandes avec l'administrateur du site à l'adresse suivante : librarymanagementwebsiteparis@gmail.com.
        
Découvrez nos livres sur notre site web : Système de Gestion de Bibliothèque
        
Cordialement,
Administrateur du Système de Gestion de Bibliothèque";
        $sender = "From: librarymanagementwebsite@gmail.com";
        if (mail($reciever_email, $subject, $body, $sender)) {
          echo '<div class="modal" id="popup">
          <div class="modal-main">
            <div class="modal-content">
              <div class="modal-header">
                <span><i class="bx bx-x" id="close-btn"></i></span>
              </div>
              <div class="modal-body">
                <figure>
                  <img src="https://www.skoolbeep.com/blog/wp-content/uploads/2020/12/WHAT-IS-THE-PURPOSE-OF-A-LIBRARY-MANAGEMENT-SYSTEM-min.png" alt="Illustration du Système de Gestion de Bibliothèque">
                </figure>
                <h5>Formulaire soumis avec succès</h5>
                <p>Merci de nous avoir contactés. Nous vous contacterons bientôt.</p>
              </div>
            </div>
          </div>
        </div>';
          ?>
          <script>
            document.getElementById("popup").style.display = "flex";
            let btn = document.getElementById("close-btn");

            btn.addEventListener("click", () => {
              document.getElementById("popup").style.display = "none";
            })
          </script>
          <?php
        } else {
          echo "Erreur lors de l'envoi du message";
        }
      } else {
        echo "Erreur lors de l'exécution de la requête";
      }
    }
  }
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Le système de gestion de bibliothèque (L.M.S) est un système simple utilisé par les bibliothécaires pour gérer les enregistrements des livres et effectuer certaines opérations dessus.">
  <meta name="keywords" content="LMS,lms,système de gestion de bibliothèque,logiciel de bibliothèque,gestion de bibliothèque" />
  <title>Système de Gestion de Bibliothèque || Facilite la gestion des enregistrements de livres</title>
  <link rel="stylesheet" href="assets/css/index.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <!--- Lien Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <!-- Lien FontAwesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
</head>

<body onload="preloader()">
  <?php include 'assets/loader/loader.php' ?>
  <header>
    <nav class="navbar">
      <div class="logo">
        <div class="icon">
          <img src="assets/images/lib.png" alt="Logo du Système de Gestion de Bibliothèque">
        </div>
        <div class="logo-details">
          <h5>L.M.S</h5>
        </div>
      </div>
      <ul class="nav-list">
        <div class="logo">
          <div class="title">
            <div class="icon">
              <img src="assets/images/lib.png" alt="Logo du Système de Gestion de Bibliothèque">
            </div>
            <div class="logo-header">
              <h4>L.M.S</h4>
              <small>Système de Bibliothèque</small>
            </div>
          </div>
          <button class="close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <li><a href="">Accueil</a></li>
        <li><a href="#book">Livres</a></li>
        <li><a href="#about">À Propos</a></li>
        <li><a href="#contact">Contact</a></li>
        <div class="login">
          <?php
          if (isset($_SESSION['loggedin'])) {
          ?>
            <a href="assets/panel/admin/dashboard.php" type="button" class="loginbtn">Tableau de Bord</a>
          <?php
          } else if (isset($_SESSION['stdloggedin'])) {
          ?>
            <a href="assets/panel/student/std-dashboard.php">Tableau de Bord</a>
          <?php
          } else {
          ?>
            <a href="assets/webpages/login-type.php" type="button" class="loginbtn">Connexion</a>
          <?php
          }
          ?>
        </div>
      </ul>
      <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
      </div>
    </nav>
  </header>

  <section class="home">
    <div class="title">
      <h2>Bienvenue dans le <span>Système de Gestion de Bibliothèque en Ligne</span></h2>
      <p>Explorez et Empruntez des Livres en Ligne</p>
      <div class="btns">
        <?php
        if (isset($_SESSION['loggedin'])) {
        ?>
          <button><a href="assets/panel/admin/dashboard.php">Tableau de Bord</a></button>
        <?php
        } else if (isset($_SESSION['stdloggedin'])) {
        ?>
          <button><a href="assets/panel/student/std-dashboard.php">Tableau de Bord</a></button>
        <?php
        } else {
        ?>
          <button><a href="assets/webpages/login-type.php">Connexion</a></button>
        <?php
        }
        ?>
        <button><a href="#book">Parcourir les Livres</a></button>
      </div>
    </div>
  </section>

  <section class="books-showcase" id="book">
    <div class="title">
      <h4>Nos Livres</h4>
    </div>
    <div class="books-container">
      <?php
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
      ?>
          <div class="book">
            <div class="img">
              <img src="assets/panel/img-store/book-images/<?php echo $row['cover'] ?>" alt="Couverture du Livre">
            </div>
            <div class="book-detail">
              <h5><?php echo $row['title'] ?></h5>
              <small><?php echo $row['author'] ?></small>
              <div class="footer-btn">
                <button><a href="assets/webpages/book-details.php?id=<?php echo $row['id'] ?>">Obtenir ce Livre</a></button>
              </div>
            </div>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </section>


  <section class="about-us" id="about">
    <div class="main">
      <div class="img">
        <img src="https://i.pinimg.com/originals/a7/4e/56/a74e56ce6107f0367195ea16e60bdd78.png" alt="À Propos de Nous">
      </div>
      <div class="about-content">
        <h4>À Propos de Nous</h4>
        <p>Le système de gestion de bibliothèque est soigneusement développé pour faciliter la gestion de tout type de bibliothèque. Il s'agit en fait d'une version virtuelle d'une bibliothèque réelle. C'est un système basé sur le web où vous pouvez gérer les livres de différentes catégories, gérer les utilisateurs et gérer l'émission/le retour des livres facilement. L'émission d'un livre à un membre ne prend qu'un simple clic. Le LMS sera un compagnon efficace et intelligent pour gérer votre bibliothèque.</p>
      </div>
    </div>
  </section>

  <section class="contact" id="contact">
    <h3>Nous Contacter</h3>
    <div class="main">
      <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10497.524174558434!2d2.3522219!3d48.856614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671d8775f3a07%3A0x5291c939dd3f1f9b!2sParis%2C%20France!5e0!3m2!1sen!2sfr!4v1700000000000" height="70" style="width: 100%; border: none; border-radius: 5px" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="contact-form">
        <h4>Nous Contacter</h4>
        <p>Contactez-nous</p>
        <form class="input-form" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
          <div class="input-field">
            <label for="name">Nom Complet *</label>
            <input type="text" name="name" id="name" placeholder="Nom Complet" />
            <?php
            if (isset($error['name'])) {
            ?>
              <p class="error-msg">
                <?php echo $error['name']; ?>
              </p>
            <?php
            }
            ?>
          </div>
          <div class="input-field">
            <label for="email">E-mail *</label>
            <input type="email" name="email" id="email" placeholder="Adresse E-mail" />
            <?php
            if (isset($error['email'])) {
            ?>
              <p class="error-msg">
                <?php echo $error['email']; ?>
              </p>
            <?php
            }
            ?>
          </div>
          <div class="input-field">
            <label for="phone">Numéro de Téléphone *</label>
            <input type="text" name="mobile" id="phone" placeholder="Numéro de Téléphone" />
            <?php
            if (isset($error['mobile'])) {
            ?>
              <p class="error-msg">
                <?php echo $error['mobile']; ?>
              </p>
            <?php
            }
            ?>
          </div>
          <div class="message">
            <label for="message">Message *</label>
            <textarea placeholder="Message" name="message" id="message"></textarea>
            <?php
            if (isset($error['message'])) {
            ?>
              <p class="error-msg">
                <?php echo $error['message']; ?>
              </p>
            <?php
            }
            ?>
          </div>
          <input type="submit" name="contact" value="ENVOYER">
          <!-- <button name="contact">ENVOYER</button> -->
        </form>
      </div>
    </div>
  </section>
  <footer>
    <div class="container">
      <div class="logo-description">
        <div class="logo">
          <div class="img">
            <i class='bx bx-book-reader'></i>
          </div>
          <div class="title">
            <h4>L.M.S</h4>
          </div>
        </div>
        <div class="logo-body">
          <p>
          Ce système de gestion de bibliothèque est soigneusement développé pour faciliter la gestion de tout type de bibliothèque. Il s'agit en fait d'une version virtuelle d'une bibliothèque réelle.
          </p>
        </div>
        <div class="social-links">
          <h4>Follow Us</h4>
          <ul class="links">
            <li>
              <a href=""><i class="fa-brands fa-facebook-f"></i></a>
            </li>
            <li>
              <a href=""><i class="fa-brands fa-youtube"></i></a>
            </li>
            <li>
              <a href=""><i class="fa-brands fa-twitter"></i></a>
            </li>
            <li>
              <a href=""><i class="fa-brands fa-linkedin"></i></a>
            </li>
            <li>
              <a href=""><i class="fa-brands fa-instagram"></i></a>
            </li>
          </ul>
        </div>
      </div>
      <div class="categories list">
        <h4>Catégories de Livres</h4>
        <ul>
          <li><a href="#">Informatique</a></li>
          <li><a href="#">Programmation</a></li>
          <li><a href="#">Philosophie</a></li>
          <li><a href="#">Sciences Sociales</a></li>
          <li><a href="#">Fiction</a></li>
          <li><a href="#">Fantasy</a></li>
        </ul>
      </div>
      <div class="quick-links list">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="#contact">Nous Contacter</a></li>
          <li><a href="#about">À Propos</a></li>
          <li><a href="assets/webpages/login-type.php">Connexion</a></li>
          <li><a href="#book">Rechercher des Livres</a></li>
        </ul>
      </div>
      <div class="our-store list">
        <h4>Notre Bibliothèque</h4>
        <div class="map" style="margin-top: 1rem">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10497.524174558434!2d2.3522219!3d48.856614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671d8775f3a07%3A0x5291c939dd3f1f9b!2sParis%2C%20France!5e0!3m2!1sen!2sfr!4v1700000000000" height="70" style="width: 100%; border: none; border-radius: 5px" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
            <ul>
      <li>
        <a href=""><i class="fa-solid fa-location-dot"></i>10 Rue de Rivoli, 75004 Paris, France</a>
      </li>
      <li>
        <a href=""><i class="fa-solid fa-phone"></i>+33 1 23 45 67 89</a>
      </li>
      <li>
        <a href=""><i class="fa-solid fa-envelope"></i>contact@parislibrary.fr</a>
      </li>
    </ul>

      </div>
    </div>
  </footer>
  <script>
    let hamburgerbtn = document.querySelector(".hamburger");
    let nav_list = document.querySelector(".nav-list");
    let closebtn = document.querySelector(".close");
    hamburgerbtn.addEventListener("click", () => {
      nav_list.classList.add("active");
    });
    closebtn.addEventListener("click", () => {
      nav_list.classList.remove("active");
    });
  </script>
</body>

</html>