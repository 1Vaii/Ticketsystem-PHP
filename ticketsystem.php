<?php
session_start();

// Prüft ob der Benutzer Angemdeldet ist
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "admin";
$password = "Passwort123!";
$dbname = "ticketsystem";

// Connection String
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_name = $_POST['name'];
    $ticket_prioritaet = $_POST['prioritaet'];
    $ticket_status = $_POST['status'];
    $ticket_beschreibung = $_POST['beschreibung'];

    $sql = "INSERT INTO tickets (Name, Prioritaet, Status, Beschreibung) VALUES ('$ticket_name', '$ticket_prioritaet', '$ticket_status', '$ticket_beschreibung')";

    if ($conn->query($sql) === TRUE) {
        echo "Neues Ticket erfolgreich erstellt";
    } else {
        echo "Fehler: " . $sql . "<br>" . $conn->error;
    }
}

// Tickets Anzeigen
$sql = "SELECT Id, Name, Prioritaet, Status, Beschreibung FROM tickets";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticketsystem</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="ticket-container">
        <h2>Ticketsystem</h2>

        <div class="form-container">
            <h3>Neues Ticket einreichen</h3>
            <form method="POST">
                <div class="input-group">
                    <label for="name">Name des Tickets:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="input-group">
                    <label for="prioritaet">Priorität:</label>
                    <input type="text" id="prioritaet" name="prioritaet" required>
                </div>

                <div class="input-group">
                    <label for="status">Status:</label>
                    <input type="text" id="status" name="status" required>
                </div>

                <div class="input-group">
                    <label for="beschreibung">Beschreibung:</label>
                    <textarea id="beschreibung" name="beschreibung" required></textarea>
                </div>

                <button type="submit" class="btn">Ticket erstellen</button>
            </form>
        </div>

        <div class="tickets-list">
            <h3>Vorhandene Tickets</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Priorität</th>
                        <th>Status</th>
                        <th>Beschreibung</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row["Id"]."</td>
                                    <td>".$row["Name"]."</td>
                                    <td>".$row["Prioritaet"]."</td>
                                    <td>".$row["Status"]."</td>
                                    <td>".$row["Beschreibung"]."</td>
                                    <td><a href='edit_ticket.php?id=".$row["Id"]."' class='btn2'>Bearbeiten</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Keine Tickets gefunden</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="button-container">
            <a href="index.php" class="btn2">Home</a>
	    <a href="http://192.168.0.3:3000/d/cegzttr48epdse/dashboard01?orgId=1&from=2025-03-26T12:05:56.219Z&to=2025-03-26T12:16:28.174Z&timezone=browser&viewPanel=panel-1" gap: 20px; class="btn2">Health-Monitor</a>
            <a href="logout.php" class="btn2">Abmelden</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
