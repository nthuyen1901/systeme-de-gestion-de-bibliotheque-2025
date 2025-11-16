<?php
include '../../../webpages/db-connect.php';
session_start();
$id = $_GET['id'];
$title = $_GET['title'];
$bookrequest = "SELECT * FROM book_request WHERE std_id='$id'";
$deletequery = "UPDATE book_request SET status='Disapproved' WHERE std_id = '$id' and title='$title'"; 
  $query = mysqli_query($con,$deletequery);
  if($query){
    $error['book-msg'] = "La demande de livre de ".$id." a été désapprouvée";
    ?>
      <script>
        setTimeout(() => {
          location.replace("view-book-request.php");
        }, 2000);
      </script>
<?php
  }else{
    $error['book-msg'] = "Une erreur est survenue lors de la suppression ou de la désapprobation de la demande de livret";
  }


?>