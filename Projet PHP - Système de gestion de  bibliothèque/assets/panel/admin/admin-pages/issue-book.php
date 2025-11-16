<?php
include '../../../webpages/db-connect.php';
error_reporting(0);
session_start();
if (!isset($_SESSION['lib-name'])) {
  header("location: ../../../webpages/librarian-login.php");
}

$bookcount = 0;
$stdcount = 0;
if (isset($_POST['search'])) {
  $isbn = $_POST['isbn'];

  $checkquery = "SELECT * FROM book WHERE isbn='$isbn'";
  $query = mysqli_query($con, $checkquery);
  $result = mysqli_fetch_assoc($query);
  $bookcount = mysqli_num_rows($query);
}
if (isset($_POST['issue-book'])) {
  $isbn = $_POST['isbn'];
  $title = $_POST['title'];
  $id = $_POST['user-id'];
  $name = $_POST['name'];
  $issuedate = $_POST['issuedate'];
  $duedate = $_POST['duedate'];


  $checkquery = "SELECT * FROM student WHERE id='$id'";
  $query = mysqli_query($con, $checkquery);
  $result = mysqli_fetch_assoc($query);
  $stdcount = mysqli_num_rows($query);
  if ($name == "") {
    $error['name'] = "Veuillez entrer le nom de l'étudiant";
  } else if (!preg_match("/^[a-zA-Z,&\s]*$/", $name)) {
    $error['name'] = "Seules les lettres alphabétiques sont autorisées";
  }
  if ($id == "") {
    $error['id'] = "Veuillez entrer l'ID de l'étudiant";
  } else if (!preg_match("/^[0-9]*$/", $id)) {
    $error['id'] = "Entrez l'ID en chiffres uniquement";
  } else {


    if ($stdcount > 0) {
      $checkissue = "SELECT * FROM issuebook WHERE title='$title' AND userid='$id' AND status='Not Returned'";
      $iquery = mysqli_query($con, $checkissue);
      $issueresult = mysqli_fetch_assoc($iquery);
      $issuecount = mysqli_num_rows($iquery);

      if (!isset($error)) {
        if ($issuecount > 0) {
          $error['book-msg'] = "Le livre a déjà été emprunté par l'étudiant";
?>
          <script>
            setTimeout(() => {
              location.replace("issue-book.php");
            }, 2000);
          </script>
          <?php
        } else {
          $checkquery = "SELECT * FROM book WHERE isbn='$isbn'";
          $query = mysqli_query($con, $checkquery);
          $result = mysqli_fetch_assoc($query);
          $bookcount = $result['quantity'];
          if ($bookcount > 0) {


            $status = "Non retourné";
            $return_date = "0000-00-00";
            $insertquery = "INSERT INTO issuebook VALUES('$isbn','$title','$id','$name','$issuedate','$duedate','$status','$return_date')";
            $query = mysqli_query($con, $insertquery);
            if ($query) {
              $error['book-msg'] = "Livre " . $title . " a été emprunté par " . $name;
              $updatebookdata = "UPDATE book SET quantity= quantity-1 WHERE isbn='$isbn'";
              $query = mysqli_query($con, $updatebookdata);
          ?>
              <script>
                setTimeout(() => {
                  location.replace("issue-book.php");
                }, 2000);
              </script>
      <?php
            } else {
              $error['book-msg'] = "Une erreur est survenue lors de l'émission du livre à l'étudiant";
            }
          } else {
            $error['book-msg'] = "Le livre n'est pas disponible";
          }
        }
      }
    } else {
      $error['id'] = "Aucun étudiant n'a été trouvé avec cet ID";
      ?>
      <script>
        setTimeout(() => {
          location.replace("issue-book.php");
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
  <title>Système de gestion de bibliothèque||Émettre un livre</title>
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
    if (isset($error['book-msg'])) {
    ?>
      <p class="error">
        <?php echo $error['book-msg']; ?>
      </p>
    <?php
    }
    ?>
    <div class="control-panel">
      <h4>Émettre un livre</h4>
      <div class="container" style="margin-top: 1rem;">
        <div class="book-cover-img">
          <img src="<?php
                    if (isset($result['cover'])) {
                      echo "../../img-store/book-images/" . $result['cover'];
                    } else {
                      echo "https://wordpress.library-management.com/wp-content/themes/library/img/259x340.png";
                    }
                    ?>" alt="Book Cover Image" id="img-preview" />
        </div>
        <div class="issue-book-form data-form">
          <h4>Détails de l'émission du livre</h4>
          <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="search-fields">
              <div class="input-field">
                <label for="isbn">ISBN *</label>
                <?php
                if ($bookcount > 0) {
                  $bookisbn = $result['isbn'];
                } else {
                  if (!isset($error['isbn'])) {
                    $error['isbn'] = "No Book has been found";
                  }
                }
                ?>
                <?php
                if (isset($error['isbn'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['isbn']; ?>
                  </p>
                <?php
                }
                ?>

                <div class="search-btn">
                  <input type="text" name="isbn" value="<?php
                                                        if (isset($bookisbn)) {
                                                          echo $bookisbn;
                                                        }
                                                        ?>" id="isbn" placeholder="Enter Book ISBN" />
                  <input type="submit" name="search" value="Search" style="margin-top: 0.5rem;" />
                </div>

              </div>
            </div>
            <div class="input-field">
              <label for="title">Titre du livre *</label>
              <input type="text" name="title" id="title" readonly value="<?php
                                                                          if ($bookcount > 0) {
                                                                            echo $result['title'];
                                                                          }
                                                                          ?>" placeholder="Enter Book Title" />
            </div>

            <div class="input-field">
              <label for="id">ID utilisateur *</label>
              <input type="text" name="user-id" id="id" placeholder="Type User-ID. Eg:1001" />
              <?php
              if (isset($error['id'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['id']; ?>
                </p>
              <?php
              }
              ?>
            </div>
            <div class="input-field">
              <label for="name">Nom de l'étudiant *</label>
              <input type="text" name="name" id="name" placeholder="Enter Student Name" />
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

            <div class="input-field input-group">
              <div class="input-1">
                <label for="issuedate">Date d'émission *</label>
                <input type="date" name="issuedate" id="issuedate" />

              </div>
              <div class="input-2">
                <label for="returndate">Date limite de retour *</label>
                <input type="date" name="duedate" id="returndate" />

              </div>
            </div>
            <input type="submit" name="issue-book" value="Issue Book">
          </form>
        </div>
      </div>
    </div>
  </section>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

  <script src="../../js/main.js"></script>
</body>

</html>