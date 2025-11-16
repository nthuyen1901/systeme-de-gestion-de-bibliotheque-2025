<?php
session_start();
error_reporting(0);

include 'db-connect.php';
$id = $_GET['id'];
$fetchbook = "SELECT * FROM book WHERE id='$id'";
$result = mysqli_query($con, $fetchbook);
$bookrow = mysqli_num_rows($result);
$bookdata = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de gestion de bibliothèque|| Détails du livre</title>
    <link rel="stylesheet" href="../css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />

</head>

<body onload="preloader()">
    <?php include '../loader/loader.php' ?>
    <header>
        <nav class="navbar">
            <div class="logo">
                <div class="icon">
                    <img src="../images/lib.png" alt="Management System Logo">
                </div>
                <div class="logo-details">
                    <h5>L.M.S</h5>
                </div>
            </div>
            <ul class="nav-list">
                <div class="logo">
                    <div class="title">
                        <div class="icon">
                            <img src="../images/lib.png" alt="Management System Logo">
                        </div>
                        <div class="logo-header">
                            <h4>L.M.S</h4>
                            <small>Système de bibliothèque</small>
                        </div>
                    </div>
                    <button class="close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <li><a href="">Accueil</a></li>
                <li><a href="#book">Livres</a></li>
                <li><a href="#about">À propos</a></li>
                <li><a href="#contact">contact</a></li>
                <div class="login">
                    <?php
                    if (isset($_SESSION['loggedin'])) {
                    ?>
                        <a href="../panel/admin/dashboard.php" type="button" class="loginbtn">Tableau de bord</a>
                    <?php
                    } else if (isset($_SESSION['stdloggedin'])) {
                    ?>
                        <a href="../panel/student/std-dashboard.php">Tableau de bord</a>

                    <?php
                    } else {
                    ?>
                        <a href="login-type.php" type="button" class="loginbtn">Connexion</a>
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


    <section class="book-overview">
        <div class="img">
            <img src="../panel/img-store/book-images/<?php echo $bookdata['cover'] ?>" alt="" />
        </div>
        <div class="book-content">
            <h4><?php echo $bookdata['title'] ?></h4>

            <p>
                <?php echo $bookdata['description'] ?>
            </p>


            <div class="footer">
                <div class="author-detail">
                    <div class="author">
                        <small>Auteur</small>
                        <strong><?php echo $bookdata['author'] ?></strong>
                    </div>
                    <div class="publisher">
                        <small>Éditeur</small>
                        <strong><?php echo $bookdata['publisher'] ?></strong>
                    </div>
                </div>
                <div class="badge">
                    <?php
                    if ($bookdata['quantity'] > 0) {
                        echo '<span style="background-color: #dbf5e5;
                        color: #56c156;">Available</span>';
                    } else {
                        echo '<span style="background-color: #FF8989;
                        color: #D71313;">Not Available</span>';
                    }
                    ?>
                </div>
            </div>
            
                <div class="input-group">

                    <button class="cartbtn"><a href="login-type.php" style="text-decoration: none;color:#fff;">Obtenir le livre</a></button>

                </div>
            </div>
        </div>
    </section>
    <section class="bookdata-recentbook">
        <div class="main">
            <table>
                <tr>
                    <th>Titre</th>
                    <td><?php echo $bookdata['title'] ?></td>
                </tr>
                <tr>
                    <th>Auteur

                    </th>
                    <td><?php echo $bookdata['author'] ?></td>
                </tr>
                <tr>
                    <th>Éditeur</th>
                    <td><?php echo $bookdata['publisher'] ?></td>
                </tr>
                <tr>
                    <th>Langue</th>
                    <td>Anglais</td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td><?php echo $bookdata['isbn'] ?></td>
                </tr>
                <tr>
                    <th>Catégorie</th>
                    <td><?php echo $bookdata['category'] ?></td>
                </tr>
            </table>
            <div class="recent-book">
                <h4>Livre récent</h4>
                <div class="book-container">
                    <?php
                    $book = "SELECT * FROM book WHERE NOT id='$id' ORDER BY id DESC LIMIT 4";
                    $bookresult = mysqli_query($con, $book);
                    if (mysqli_num_rows($bookresult) > 0) {
                        while ($row = mysqli_fetch_assoc($bookresult)) {
                    ?>
                            <div class="book">
                                <div class="img">
                                    <img src="../panel/img-store/book-images/<?php echo $row['cover'] ?>" alt="Book Cover Image">
                                </div>
                                <div class="content">
                                    <h5><?php echo $row['title'] ?></h5>
                                    <div class="badge">
                                        <span><?php echo mb_strimwidth($row['author'], 0, 30, '...'); ?></span>
                                    </div>

                                    
                                    <div class="btn">
                                        <button><a href="book-details.php?id=<?php echo $row['id'] ?>" style="text-decoration: none;color:#fff;">Obtenir le livre</a></button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
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
                    Le système de gestion de bibliothèque est soigneusement développé pour faciliter la gestion de tout type de bibliothèque. Il s'agit en fait d'une version virtuelle d'une bibliothèque réelle.
                    </p>
                </div>
                <div class="social-links">
                    <h4>Suivez-nous</h4>
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
                <h4>Catégories de livres</h4>
                <ul>
                    <li><a href="#">Informatique</a></li>
                    <li><a href="#">Programmation</a></li>
                    <li><a href="#">Philosophie</a></li>
                    <li><a href="#">Sciences sociales</a></li>
                    <li><a href="#">Fiction</a></li>
                    <li><a href="#">Fantasy</a></li>
                </ul>
            </div>
            <div class="quick-links list">
                <h4>Liens rapides</h4>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="#contact">Nous contacter</a></li>
                    <li><a href="#about">À propos de nous</a></li>
                    <li><a href="assets/webpages/login-type.php">Connexion</a></li>
                    <li><a href="#book">Rechercher des livres</a></li>
                </ul>
            </div>
            <div class="our-store list">
                <h4>Notre bibliothèque</h4>
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