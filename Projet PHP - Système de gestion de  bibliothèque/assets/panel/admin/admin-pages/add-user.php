<?php
include '../../../webpages/db-connect.php';
error_reporting(0);
session_start();
if (!isset($_SESSION['lib-name'])) {
  header("location: ../../../webpages/librarian-login.php");
}

if (isset($_POST['add-user'])) {
  $name = mysqli_real_escape_string($con, $_POST['name']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $course = mysqli_real_escape_string($con, $_POST['course']);
  $year = mysqli_real_escape_string($con, $_POST['year']);
  $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
  $address = mysqli_real_escape_string($con, $_POST['address']);
  $city = mysqli_real_escape_string($con, $_POST['city']);
  $state = mysqli_real_escape_string($con, $_POST['state']);
  $zipcode = mysqli_real_escape_string($con, $_POST['zipcode']);
  $role = mysqli_real_escape_string($con, $_POST['role']);
  $idcard = mysqli_real_escape_string($con, $_POST['idcard']);
  $dob = mysqli_real_escape_string($con, $_POST['dob']);
  $imgname = $_FILES["profileimg"]["name"];
  $tempname = $_FILES["profileimg"]["tmp_name"];
  $folder = "../../img-store/student-profile-images/" . $imgname;
  move_uploaded_file($tempname, $folder);
  $code = rand(999999, 111111);

  $checkquery = "SELECT * FROM student WHERE email='$email' AND name='$name'";
  $query = mysqli_query($con, $checkquery);
  $emailcount = mysqli_num_rows($query);
  if ($emailcount > 0) {
    $error['std-msg'] = 'Student already exist';
?>
    <script>
      setTimeout(() => {
        document.querySelector(".error").style.display = "none"
      }, 2000);
    </script>
    <?php
  } else {
    if ($name == "") {
      $error['name'] = "Le nom ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z\s]*$/", $name)) {
      $error['name'] = "Seules les lettres alphabétiques sont autorisées";
    }
    if ($email == "") {
      $error['email'] = "L'e-mail ne doit pas être vide";
    } else if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)) {
      $error['email'] = "Veuillez entrer une adresse e-mail valide";
    }
    if ($mobile == "") {
      $error['mobile'] = "Le champ du numéro de téléphone ne doit pas être vide";
    } else if (!preg_match("/^[0-9]{10}+$/", $mobile)) {
      $error['mobile'] = "Veuillez entrer un numéro de téléphone valide";
    }
    if ($address == "") {
      $error['address'] = "L'adresse ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z0-9.,\s]*$/", $address)) {
      $error['address'] = "Seules les lettres et les chiffres sont autorisés";
    }
    if ($city == "") {
      $error['city'] = "La ville ne doit pas être vide";
    } else if (!preg_match("/^[a-zA-Z\s]*$/", $city)) {
      $error['city'] = "Seules les lettres alphabétiques sont autorisées";
    }
    
    if ($zipcode == "") {
      $error['zipcode'] = "Veuillez entrer un code postal";
    } else if(!preg_match("/^[0-9]{6}+$/", $zipcode)) {
      $error['zipcode'] = "Veuillez entrer un code postal valide";
    }else {
      if (!isset($error)) {
        $insertquery = "INSERT INTO student(name, email, course, year, mobile, address, city, state, zipcode, role, std_img, code,admission_date,id_card,dob) VALUES ('$name','$email','$course','$year','$mobile','$address','$city','$state','$zipcode','$role','$imgname','$code',curdate(),'$idcard','$dob')";
        $query = mysqli_query($con, $insertquery);
        if ($query) {
          $reciever_email = $email;
          $subject = "Code pour la connexion des étudiants";
          $message = 'Vous avez été ajouté avec succès par le bibliothécaire aux archives de la bibliothèque. Vous pouvez maintenant emprunter des livres à la bibliothèque ou simplement faire une demande de livre via le site web de la bibliothèque en ligne.
Vous pouvez utiliser vos identifiants de connexion, qui sont votre e-mail enregistré et un mot de passe à 6 chiffres.
Voici votre mot de passe de connexion :'.$code;
          $sender = "De: codewithpawanofficial@gmail.com";
          if (mail($reciever_email, $subject, $message, $sender)) {
            $error['std-msg'] = "Étudiant ajouté avec succès, nous avons envoyé les identifiants de connexion à l'adresse e-mail de l'étudiant. - $reciever_email";
    ?>
            <script>
              setTimeout(() => {
                location.replace("add-user.php");
              }, 2000);
            </script>
          <?php
          } else {
            $error['std-msg'] = "Échec lors de l'ajout de l'étudiant et de l'envoi des identifiants de connexion !";
          ?>
            <script>
              setTimeout(() => {
                location.replace("add-user.php");
              }, 2000);
            </script>
          <?php
          }
          $error['std-msg'] = 'Student Details have been added successfully';
          ?>
          <script>
            setTimeout(() => {
              document.querySelector(".error").style.display = "none"
            }, 2000);
          </script>
<?php
        } else {
          $error['std-msg'] = 'Error Occured while adding student details' . mysqli_error($con);
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
  <title>Système de gestion de bibliothèque|| Ajouter un étudiant</title>
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
    if (isset($error['std-msg'])) {
    ?>
      <p class="error">
        <?php echo $error['std-msg']; ?>
      </p>
    <?php
    }
    ?>
    <div class="control-panel">
      <h4>Add Student</h4>
      <div class="container" style="margin-top: 1rem;">
        <div class="book-cover-img">
          <img src="https://wordpress.library-management.com/wp-content/themes/library/img/259x340.png" alt="Student Profile Image" id="img-preview" />
        </div>
        <div class="add-student-form data-form">
          <h4>Détails de l'étudiants</h4>
          <form class="input-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
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
            <div class="input-1">
              <label for="email">Email *</label>
              <input type="text" name="email" id="email" placeholder="Enter E-mail Address" />
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
            <div class="input-field input-group upload-file">
              <div class="input-1">
                <label for="stdImg">Image de l'étudiant *</label>
                <input type="file" id="stdimg" name="profileimg" accept=".jpg,.png" required>
              </div>
              <div class="input-2 Course-option">
                <label for="course">le nom de course *</label>
                <select id="course" name="course">
                  <option value="Bsc">BSc</option>
                  <option value="B-Com">B-Com</option>
                  <option value="B-Tech">B-Tech</option>
                  <option value="MCA">MCA</option>
                  <option value="BA ">BA</option>
                </select>

              </div>
            </div>

            <div class="input-field input-group">
              <div class="input-1 year-option">
                <label for="year">Année *</label>
                <select id="year" name="year">
                  <option value="1st Year">1ere Année</option>
                  <option value="2nd Year">2eme Année</option>
                  <option value="3rd Year">3eme Année</option>
                  <option value="4th Year">4eme Année</option>
                  <option value="5th Year">5eme Année</option>
                </select>
                
              </div>
              <div class="input-2">
                <label for="mobile">Numéro de téléphone *</label>
                <input type="text" name="mobile" id="mobile" placeholder="Enter mobile no." />
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
            </div>
            <div class="book-desc">
              <label for="desc">Adresse *</label>
              <textarea rows="5" placeholder="Enter Student Address" id="desc" name="address"></textarea>
              <?php
              if (isset($error['address'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['address']; ?>
                </p>
              <?php
              }
              ?>
            </div>

            <div class="input-field upload-file">
              <div class="input-1">
                <label for="city">Ville *</label>
                <input type="text" name="city" id="city" placeholder="Enter City" />
                <?php
                if (isset($error['city'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['city']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
              <div class="input-2 state-option">
                <label for="state">Région*</label>
                <select id="state" name="state">
                  <option value="Paris 11">Paris 11</option>
                  <option value="Courbevoie">Courbevoie</option>
                  <option value="Paris 9">Paris 9</option>
                  <option value="Bourg la Reine">Bourg la Reine</option>
                  <option value="Paris 6">Paris 6</option>
                  <option value="Paris 4">Paris 4</option>
                  <option value="Bondy">Bondy</option>
                </select>
                
              </div>
            </div>
            <div class="input-field input-group">
              <div class="input-1">
                <label for="zipcode">Code postal *</label>
                <input type="text" maxlength="6" name="zipcode" id="zipcode" placeholder="Zip Code" />
                <?php
                if (isset($error['zipcode'])) {
                ?>
                  <p class="error-msg">
                    <?php echo $error['zipcode']; ?>
                  </p>
                <?php
                }
                ?>
              </div>
              <div class="input-2">
                <label for="role">Rôle *</label>
                <input type="text" name="role" id="role" value="Student" />
              </div>
            </div>
            <div class="input-field input-group">
              <div class="input-2">
                <label for="idcard">Carte d'identité *</label>
                <select id="idcard" name="idcard">
                  <option value="notassign">Ne pas attribuer</option>
                  <option value="assign">Attribuer</option>
                </select>
                <?php
              if (isset($error['idcard'])) {
              ?>
                <p class="error-msg">
                  <?php echo $error['idcard']; ?>
                </p>
              <?php
              }
              ?>
              </div>
              <div class="input-1">
                <label for="dob">Date de naissance *</label>
                <input type="date" name="dob" id="dob" required/>
              </div>
            </div>
            <input type="submit" value="Add User" name="add-user">
          </form>
        </div>
      </div>
    </div>
  </section>
  <script>
    let imgpreview = document.querySelector("#img-preview");
    let fileinput = document.getElementById("stdimg");

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