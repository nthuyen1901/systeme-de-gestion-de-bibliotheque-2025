<?php
session_start();
session_unset();  // Effacer toutes les variables de session.
session_destroy(); // Détruire la session.

// Chemin de redirection correct.
header("Location: /library-management-system/index.php");
exit;
?>
