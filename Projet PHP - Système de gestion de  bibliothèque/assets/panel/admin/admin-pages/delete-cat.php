<?php
include '../../../webpages/db-connect.php';

$id = $_GET['id'];
$deletequery = "DELETE FROM category WHERE catid = '$id'"; 
  $query = mysqli_query($con,$deletequery);
  if($query){
      $error['book-msg'] = "La catégorie a été supprimée avec succès";
      $altersql = "ALTER TABLE category AUTO_INCREMENT=1";
                                      $aquery = mysqli_query($con,$altersql);
                                      ?>
                                      <script>
                                        setTimeout(() => {
                                          location.replace("category.php");
                                        }, 2000);
                                      </script>
                                <?php


  }else{
    $error['book-msg'] = "Une erreur est survenue lors de la suppression de la catégorie";
  }

?>