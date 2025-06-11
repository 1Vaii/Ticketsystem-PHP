<?php
session_start();

// Überprüfen, ob der Benutzer bereits eingeloggt ist
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Wenn der Benutzer eingeloggt ist, leiten wir ihn zum Ticketsystem weiter
    header("Location: ticketsystem.php");
    exit();
} else {
    // Wenn der Benutzer noch nicht eingeloggt ist, leiten wir ihn zur Login-Seite weiter
    header("Location: login.php");
    exit();
}
?>
