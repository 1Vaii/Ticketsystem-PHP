<?php
session_start();

// AD-Verbindungsdaten
$ldap_server = "ldap://v-dc01-tw.ticket.sys";
$ldap_dn = "CN=V-DC01-TW,OU=Domain Controllers,DC=ticket,DC=sys";
$ldap_domain = "ticket.sys";

// Fehlernachricht
$error_message = "";

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verbindungsaufbau zum LDAP-Server
    $ldap_conn = ldap_connect($ldap_server);
    
    if (!$ldap_conn) {
        $error_message = "Fehler beim Verbinden mit dem LDAP-Server!";
    } else {
        // Optional: Setze Zeitüberschreitungen und andere Optionen
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        
        // LDAP-Bind (Authentifizierung) durchführen
        $ldap_dn_user = "$username@$ldap_domain"; // Der vollständige Benutzername (z.B. "username@domain.local")
        
        // Versuch, den Benutzer im AD zu authentifizieren
        $bind = @ldap_bind($ldap_conn, $ldap_dn_user, $password);

        if ($bind) {
            // Erfolgreiche Anmeldung
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username; // Benutzername in der Session speichern
            header("Location: ticketsystem.php"); // Weiterleitung zum Ticketsystem
            exit();
        } else {
            $error_message = "Benutzername oder Passwort sind falsch!";
        }

        // Verbindung schließen
        ldap_unbind($ldap_conn);
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ticketsystem</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Ticket.sys </br>Login</h2>
        
        <?php if ($error_message) { echo "<p style='color:red;'>$error_message</p>"; } ?>
        
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Benutzername:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Anmelden</button>
        </form>
    </div>
</body>
</html>

