<?php
include '../../../webpages/db-connect.php';

$id = $_GET['id'];
$deletequery = "DELETE FROM student WHERE id = '$id'"; 
  $query = mysqli_query($con,$deletequery);
  if($query){
    $error['std-msg'] = "Les données de l'étudiant ont été supprimées avec succès";
    ?>
      <script>
        setTimeout(() => {
          location.replace("view-user.php");
        }, 2000);
      </script>
<?php
  }else{
    $error['std-msg'] = "Une erreur est survenue lors de la suppression des détails de l'étudiant";
    ?>
      <script>
        setTimeout(() => {
          location.replace("view-user.php");
        }, 2000);
      </script>
<?php
  }


?>