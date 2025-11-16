<?php
session_start();
include 'C:/xampp1/htdocs/library-management-system/assets/webpages/db-connect.php';

// S'assurer que l'étudiant est connecté.
if (!isset($_SESSION['std-email'])) {
    echo "Erreur : L'e-mail de l'étudiant n'est pas défini dans la session.";
    exit;
}

$student_email = $_SESSION['std-email'];
$student_query = "SELECT name, email, course, year FROM student WHERE email = '$student_email'";
$student_result = mysqli_query($con, $student_query);
$student_data = mysqli_fetch_assoc($student_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'étudiant</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .profile-info {
            text-align: left;
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }
        .profile-info strong {
            color: #000;
        }
        .back-btn {
            display: inline-block;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 15px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Profil de l'étudiant</h2>
        <p class="profile-info"><strong>Nom:</strong> <?php echo $student_data['name']; ?></p>
        <p class="profile-info"><strong>Email:</strong> <?php echo $student_data['email']; ?></p>
        <p class="profile-info"><strong>Cours:</strong> <?php echo $student_data['course']; ?></p>
        <p class="profile-info"><strong>Année:</strong> <?php echo $student_data['year']; ?></p>
        <a href="std-dashboard.php" class="back-btn">Retour au tableau de bord</a>
    </div>
</body>
</html>
