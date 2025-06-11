<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "admin"; // Benutzername, wie in deiner SQL-Anweisung
$password = "Passwort123!"; // Dein Passwort
$dbname = "ticketsystem"; // Der Name der Datenbank

// Erstelle Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Prüfe die Verbindung
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ticket-ID aus der URL holen
$ticket_id = $_GET['id'];

// Ticketdaten aus der Datenbank holen
$sql = "SELECT * FROM tickets WHERE Id = $ticket_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ticket gefunden, Daten in Variablen speichern
    $ticket = $result->fetch_assoc();
} else {
    echo "Ticket nicht gefunden!";
    exit();
}

// Formular-Verarbeitung: Ticket bearbeiten
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_name = $_POST['name'];
    $ticket_prioritaet = $_POST['prioritaet'];
    $ticket_status = $_POST['status'];
    $ticket_beschreibung = $_POST['beschreibung'];

    // Update SQL
    $update_sql = "UPDATE tickets SET Name='$ticket_name', Prioritaet='$ticket_prioritaet', Status='$ticket_status', Beschreibung='$ticket_beschreibung' WHERE Id=$ticket_id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: ticketsystem.php"); // Weiterleitung nach dem Bearbeiten
        exit();
    } else {
        echo "Fehler beim Bearbeiten des Tickets: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket bearbeiten</title>
    <link rel="stylesheet" href="styles.css"> <!-- Verlinke die gleiche CSS-Datei -->
</head>
<body>

    <h1>Ticket bearbeiten</h1>

    <!-- Ticket-Bearbeitungsformular -->
    <form method="POST">
        <div class="input-group">
            <label for="name">Name des Tickets:</label><br>
            <input type="text" id="name" name="name" value="<?php echo $ticket['Name']; ?>" required><br><br>
        </div>

        <div class="input-group">
            <label for="prioritaet">Priorität:</label><br>
            <input type="text" id="prioritaet" name="prioritaet" value="<?php echo $ticket['Prioritaet']; ?>" required><br><br>
        </div>

        <div class="input-group">
            <label for="status">Status:</label><br>
            <input type="text" id="status" name="status" value="<?php echo $ticket['Status']; ?>" required><br><br>
        </div>

        <div class="input-group">
            <label for="beschreibung">Beschreibung:</label><br>
            <textarea id="beschreibung" name="beschreibung" required><?php echo $ticket['Beschreibung']; ?></textarea><br><br>
        </div>

        <input type="submit" value="Ticket bearbeiten" class="btn2">
    </form>

    <br><br>
    <!-- Schaltflächen -->
    <div class="button-container">
        <a href="ticketsystem.php" class="btn2">Zurück zum Ticket-System</a>
        <a href="logout.php" class="btn2">Abmelden</a>
    </div>

</body>
</html>

<?php
$conn->close();
?>
