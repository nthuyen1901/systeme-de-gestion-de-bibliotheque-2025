<?php
include '../../../webpages/db-connect.php';

$id = $_GET['id'];
$deletequery = "DELETE FROM book WHERE id = '$id'"; 
  $query = mysqli_query($con,$deletequery);
  if($query){
    $error['book-msg'] = "Les données du livre ont été supprimées avec succès";
    ?>
      <script>
        setTimeout(() => {
          location.replace("view-book.php");
        }, 2000);
      </script>
<?php
  }else{
    $error['book-msg'] = "Une erreur est survenue lors de la suppression des détails du livre";
  }


?>