<?php

// Připojení k databázi
include 'database.php';
$db = new Database();
$conn = $db->dbConnection();

// Získání názvů z databáze
$sql = "SELECT id, nazev FROM recepty";
$result = $conn->query($sql);

// vytvoření array names
$names = array();
if ($result->rowCount() > 0) {
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
  // získání délky vstupu
  $len=mb_strlen($q, 'UTF-8');
  // cyklus procházející položky v names
  foreach($names as $name) {
    // kontrola zda, vstup uživatele odpovídá nějáké položce ze seznamu names
    if (mb_stristr($q, mb_substr($name["nazev"], 0, $len, 'UTF-8'), false, 'UTF-8')) {
      // kontrola zda je proměná hint prázdná, pokud ano položka je přidána na začátek, pokud ne, přidává se na další řádek
      if ($hint === "") {
        $hint = "<a href='recept.php?id=" . $name["id"] . "-nz=" . $name["nazev"] . "'>" . $name["nazev"] . "</a>";
      } else {
        $hint .= "</br> <a href='recept.php?id=" . $name["id"] . "-nz=" . $name["nazev"] . "'>" . $name["nazev"] . "</a>";
      }
    }
  }
}

// tisk nalezených receptů, popřípadě tisk chybové hlášky
echo $hint === "" ? "takový recept zatím nemáme" : $hint;

?>


