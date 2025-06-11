<?php
session_start();
session_unset(); // Alle Session-Daten löschen
session_destroy(); // Die Session zerstören
header("Location: login.php"); // Weiterleitung zur Login-Seite
exit();
?>
