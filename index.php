<?php
session_start();

// CPrüft ob der Benutzer Angemeldet ist
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ticketsystem.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
