<?php
include '../../../webpages/db-connect.php';
error_reporting(0);
session_start();
if (!isset($_SESSION['lib-name'])) {
  header("location: ../../../webpages/librarian-login.php");
}


if (isset($_POST['add-book'])) {
  $isbn = mysqli_real_escape_string($con, $_POST['isbn']);
  $author = mysqli_real_escape_string($con, $_POST['Auteur']);
  $title = mysqli_real_escape_string($con, $_POST['title']);
  $category = mysqli_real_escape_string($con, $_POST['category']);
  $publisher = mysqli_real_escape_string($con, $_POST['publisher']);
  // $bookimg = mysqli_real_escape_string($con,$_POST['book-img']);

  $imgname = $_FILES["bookimg"]["name"];
  $tempname = $_FILES["bookimg"]["tmp_name"];
  $folder = "../../img-store/book-images/" . $imgname;
  move_uploaded_file($tempname, $folder);


  // $bookpdf = mysqli_real_escape_string($con,$_POST['book-pdf']);



  $pdfname = $_FILES['book-pdf']['name'];
  $file_tmp = $_FILES['book-pdf']['tmp_name'];
  move_uploaded_file($file_tmp, "../../img-store/book-pdf/" . $pdfname);


  $price = mysqli_real_escape_string($con, $_POST['price']);
  $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
  $description = mysqli_real_escape_string($con, $_POST['description']);
  $bookquery = "SELECT * FROM book WHERE isbn='$isbn'";
  $query = mysqli_query($con, $bookquery);
  $bookcount = mysqli_num_rows($query);
  if ($bookcount > 0) {
    $error['book-msg'] = 'Book already exist';
?>
    <script>
      setTimeout(() => {
        document.querySelector(".error").style.display = "none"
      }, 2000);
    </script>
    <?php
  } else {
    if ($isbn == "") {
      $error['isbn'] = "Veuillez entrer le numéro ISBN.";
    } else if (!preg_match("/^(?:[0-9]*).{13}$/", $isbn) || !preg_match("/^(?:[0-9]*).{10}$/", $isbn)) {
      $error['isbn'] = "Veuillez entrer un numéro ISBN valide.";
    }
    if ($author == "") {
      $error['author'] = "L'auteur ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z,&\s]*$/", $author)) {
      $error['author'] = "Seules les lettres alphabétiques sont autorisées";
    }
    if ($title == "") {
      $error['title'] = "Le titre ne doit pas être vide";
    } else if (!preg_match("/^[A-Za-z0-9\s]*$/", $title)) {
      $error['title'] = "Seules les lettres et les chiffres sont autorisésd";
    }
    if ($category == "") {
      $error['category'] = "La catégorie ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z\s]*$/", $category)) {
      $error['category'] = "Seules les lettres alphabétiques sont autorisées";
    }
    if ($publisher == "") {
      $error['publisher'] = "L'éditeur ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z\s]*$/", $publisher)) {
      $error['publisher'] = "Seules les lettres alphabétiques sont autorisées";
    }
    if ($price == "") {
      $error['price'] = "Le prix ne doit pas être vide";
    } else if (!preg_match("/^[0-9]*$/", $price)) {
      $error['price'] = "Entrez le prix en chiffres";
    }
    if ($quantity == "") {
      $error['quantity'] = "La quantité ne doit pas être vide";
    } else if (!preg_match("/^[0-9]*$/", $quantity)) {
      $error['quantity'] = "Entrez la quantité en chiffres";
    }
    if ($description == "") {
      $error['description'] = "La description ne doit pas être vide";
    } else {
      if (!isset($error)) {
        $insertquery = "INSERT INTO book (isbn,author,title,category,publisher,price,quantity,description,cover,pdf) VALUES ('$isbn','$author','$title','$category','$publisher','$price','$quantity','$description','$imgname','$pdfname')";
        $query = mysqli_query($con, $insertquery);
        if ($query) {

          $error['book-msg'] = 'Your Book has been Inserted Successfully';
    ?>
          <script>
            setTimeout(() => {
              document.querySelector(".error").style.display = "none"
            }, 2000);
          </script>
        <?php
        } else {
          $error['book-msg'] = 'Book Not Inserted';

        ?>
          <script>
            setTimeout(() => {
              document.querySelector(".error").style.display = "none"
            }, 2000);
          </script>
<?php
        }
      }
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
  <title>Système de gestion de bibliothèque ||Ajouter un livre</title>
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
      <h4>Ajouter un livre</h4>
      <div class="container">
        <div class="book-cover-img">
          <img src="https://wordpress.library-management.com/wp-content/themes/library/img/259x340.png" alt="Book Cover Image" id="img-preview" />
        </div>
        <div class="add-book-form data-form">
          <h4>Détails du livre</h4>
          <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <div class="input-field input-group">
              <div class="input-1">
                <label for="isbn">ISBN *</label>
                <input type="text" name="isbn" id="isbn" maxlength="13" placeholder="Enter ISBN" />
                <?php
                if (isset($error['isbn'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['isbn']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
              <div class="input-2">
                <label for="author">Nom de l'auteur *</label>
                <input type="text" name="author" id="author" placeholder="Enter Author Name" />

                <?php
                if (isset($error['author'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['author']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="input-field">
              <label for="title">Titre du livre *</label>
              <input type="text" name="title" id="title" placeholder="Enter Book Title" />
              <?php
              if (isset($error['title'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['title']; ?>
                </p>
              <?php
              }
              ?>
            </div>
            <div class="input-field input-group">
            <div class="input-1 Course-option">
                <label for="category">Catégorie *</label>
                <select id="category" name="category">
                  <?php
                  $fetchcat = "SELECT * FROM category";
                  $fquery = mysqli_query($con,$fetchcat);
                  while ($row=mysqli_fetch_assoc($fquery))
                  {
                    ?>
                    <option value="<?php echo $row['category']; ?>"><?php echo $row['category']; ?></option>
                    <?php
                  }
                  ?>
                </select>

              </div>
              <div class="input-2">
                <label for="publisher">Éditeur *</label>
                <input type="text" name="publisher" id="publisher" placeholder="Enter Publisher Name" />
                <?php
                if (isset($error['publisher'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['publisher']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
            </div>

            <div class="input-field upload-file">
              <div class="input-1">
                <label for="img">Télécharger l'image du livre *</label>
                <input type="file" name="bookimg" id="img" accept=".jpg,.png" required />
              </div>
              <div class="input-2">
                <label for="pdf">Télécharger le PDF du livre *</label>
                <input type="file" name="book-pdf" id="pdf" accept=".pdf" required />
              </div>
            </div>
            <div class="input-field input-group">
              <div class="input-1">
                <label for="price">Prix *</label>
                <input type="text" name="price" id="price" placeholder="Enter Book Price" />
                <?php
                if (isset($error['price'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['price']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
              <div class="input-2">
                <label for="quantity">Quantité *</label>
                <input type="text" name="quantity" id="quantity" value="1" placeholder="Enter Book Quantity" />
                <?php
                if (isset($error['quantity'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['quantity']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
            </div>
            <div class="book-desc">
              <label for="desc">Description du livre *</label>
              <textarea rows="5" placeholder="Enter BOok Description" id="desc" name="description"></textarea>
              <?php
              if (isset($error['description'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['description']; ?>
                </p>
              <?php
              }
              ?>
            </div>
            <input type="submit" value="Add Book" name="add-book">
          </form>
        </div>
      </div>
    </div>
  </section>

  <script>
    let imgpreview = document.querySelector(".book-cover-img #img-preview");
    let fileinput = document.getElementById("img");

    fileinput.onchange = () => {
      let reader = new FileReader();
      reader.readAsDataURL(fileinput.files[0]);
      reader.onload = () => {
        let fileURL = reader.result;
        imgpreview.src = fileURL;
        
        
      }
    }
  </script>

  <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

  <script src="../../js/main.js"></script>
</body>

</html>