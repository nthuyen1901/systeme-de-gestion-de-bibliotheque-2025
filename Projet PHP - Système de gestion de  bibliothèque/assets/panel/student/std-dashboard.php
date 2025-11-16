<?php
session_start();
include_once 'C:/xampp1/htdocs/library-management-system/assets/webpages/db-connect.php';

// S'assurer que l'utilisateur est connecté.
if (!isset($_SESSION['stdloggedin']) || $_SESSION['stdloggedin'] !== true) {
    header('Location: ../../webpages/student-login.php');
    exit;
}

// S'assurer que l'e-mail est stocké dans la session.
if (!isset($_SESSION['std-email'])) {
    echo "Erreur : L'e-mail de l'étudiant n'est pas défini dans la session.";
    exit;
}

$student_email = $_SESSION['std-email'];

// Récupérer les détails de l'étudiant.
$student_query = "SELECT * FROM student WHERE email = '$student_email'";
$student_result = mysqli_query($con, $student_query);
$student_data = mysqli_fetch_assoc($student_result);
$student_id = $student_data['id'] ?? null;

if (!$student_id) {
    die("Erreur : Identifiant étudiant non trouvé.");
}

// Retourner la date d'échéance pour le prochain livre.
$due_date_query = "SELECT duedate FROM issuebook WHERE userid = '$student_id' AND status = 'issued' ORDER BY duedate ASC LIMIT 1";
$due_date_result = mysqli_query($con, $due_date_query);
$due_date_data = mysqli_fetch_assoc($due_date_result);
$return_due_date = $due_date_data['duedate'] ?? "Aucun livre en retard";

// Nouvelles arrivées (Les 3 derniers livres).
$new_arrivals_query = "SELECT title FROM book ORDER BY id DESC LIMIT 3";
$new_arrivals_result = mysqli_query($con, $new_arrivals_query);
$new_arrivals = [];
while ($row = mysqli_fetch_assoc($new_arrivals_result)) {
    $new_arrivals[] = $row['title'];
}

// Notifications (Livres dus, Approbations).
$notifications = [];
if ($return_due_date !== "Aucun livre en retard") {
    $notifications[] = "Votre livre est dû le $return_due_date!";
}

// Demandes de livres en attente.
$pending_requests_query = "SELECT COUNT(*) AS total FROM book_request WHERE std_id = '$student_id' AND status = 'pending'";
$pending_requests_result = mysqli_query($con, $pending_requests_query);
$pending_requests = mysqli_fetch_assoc($pending_requests_result)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord étudiant</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e9edf2;
            margin: 0;
            padding: 0;
        }
        .dashboard {
            display: flex;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background: #2c2f33;
            padding: 15px;
            color: white;
        }
        .sidebar a {
            text-decoration: none;
            display: block;
            color: white;
            padding: 12px;
            margin: 5px 0;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #7289da;
            border-radius: 5px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            color: white;
            font-weight: bold;
        }
        .card:nth-child(1) { background: #6a5acd; }
        .card:nth-child(2) { background: #3498db; }
        .card:nth-child(3) { background: #f39c12; }
        .card:nth-child(4) { background: #e74c3c; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>Bibliothèque</h2>
            <a href="books.php">📚 Livres</a>
            <a href="profile.php">👤 Profil</a>
            <a href="logout.php">🚪 Déconnexion</a>
        </div>

        <div class="content">
            <div class="top-bar">
                <h2>📊 Tableau de bord étudiant</h2>
                <button class="logout-btn" onclick="window.location.href='logout.php'">Log Out</button>
            </div>

            <div class="dashboard-cards">
                <div class="card">
                    <h2><?php echo $return_due_date; ?></h2>
                    <p>Prochaine date d'échéance de retour</p>
                </div>
                <div class="card">
                    <h2><?php echo implode(", ", $new_arrivals); ?></h2>
                    <p>Nouveautés</p>
                </div>
                <div class="card">
                    <h2><?php echo implode("<br>", $notifications); ?></h2>
                    <p>Notifications</p>
                </div>
                <div class="card">
                    <h2><?php echo $pending_requests; ?></h2>
                    <p>Demandes en attente</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
