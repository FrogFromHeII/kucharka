<?php
// Připojení k databázi
include 'dnWeb.php';
$web = new dnWEb();

// Získání názvů z databáze
$sql = "SELECT id, nazev FROM recepty";
$columns = array("id", "nazev");
$result = $web->getDataFromDatabase($columns, "recepty");
$web->closeConnection();

// vytvoření array names
$names = array();
if (count($result) > 0) {
    foreach ($result as $row) {
        array_push($names, $row);
    }
}

$q=$_GET["q"];
$hint = "";

// kód vytváří seznam návrhů na základě vstupu uživatele a vrací tento seznam jako řetězec HTML odkazů na stránky receptů
// každý návrh je na novém řádku

// kontrola, zda proměná q obsahuje znaky
if ($q !== "") {
    // převedení vstupu na malá písmena
    $q = mb_strtolower($q, 'UTF-8');
    // cyklus procházející položky v names
    foreach($names as $name) {
        // kontrola zda, vstup uživatele odpovídá nějáké položce ze seznamu names
        if (mb_stristr(mb_strtolower($name["nazev"], 'UTF-8'), $q, false, 'UTF-8')) {
            // přidání odkazu na recept do výsledků
            $hint .= "<a href='recept.php?id=" . $name["id"] . "-nz=" . $name["nazev"] . "'>" . $name["nazev"] . "</a></br>";
        }
    }
}

// tisk nalezených receptů, popřípadě tisk chybové hlášky
echo $hint === "" ? "takový recept zatím nemáme" : $hint;
?>