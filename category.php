<?php

require_once 'html/header.php';

// Získání ID kategorie z URL
$id = $_GET['id'];

// Získání receptů pro danou kategorii
$columns = array("id", "nazev", "obrazek");
$table = "recepty WHERE kategorie = " . $id;
$result = $web->getDataFromDatabase($columns, $table);

// Zobrazení dat podle id kategorie
$sql = "SELECT id, nazev, obrazek FROM recepty WHERE kategorie = :categoryId";
$params = array('categoryId' => $id);
$web->displayDataFromDatabase($sql, $params);

require_once 'html/footer.php';

?>