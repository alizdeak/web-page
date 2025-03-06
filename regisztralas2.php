<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ellenőrizzük, hogy a megfelelő adatokat küldték-e
    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
        // Eltároljuk a felhasználó által megadott adatokat
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];

        // Előfordulhat, hogy itt egy adatbázis lekérdezés kerülne a felhasználónév és e-mail cím ellenőrzésére,
        // majd a felhasználó regisztrálása az adatbázisba

        // E-mail küldése az értesítésről
        $to = $email;
        $subject = "Sikeres regisztráció";
        $message = "Kedves $username! Köszönjük a regisztrációt.";

        // Fejlécek beállítása, hogy HTML formátumban küldjük az e-mailt
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // E-mail küldése
        mail($to, $subject, $message, $headers);

        // Átirányítás a sikeres regisztráció után
        header("Location: registration_success.html");
        exit();
    } else {
        // Ha hiányzó adatok vannak, hibaüzenetet jelenítünk meg
        echo "Hiányzó felhasználónév, jelszó vagy e-mail cím.";
    }
} else {
    // Ha nem POST kérés érkezett, akkor valószínűleg valami hiba történt
    echo "Hiba történt a kérés feldolgozása során.";
}
?>
