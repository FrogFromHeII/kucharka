<?php
require_once 'html/header.php';

// Získání ID kategorie z URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Získání živého vyhledávání
$q = isset($_GET['q']) ? $_GET['q'] : '';

// Display search results if search query is provided
if (!empty($q)) {
    // Zobrazení dat podle živého vyhledávání
    $sql = "SELECT id, nazev, obrazek FROM recepty WHERE LOWER(nazev) LIKE :searchTerm";
    $params = array('searchTerm' => '%' . strtolower($q) . '%');
    $web->displayDataFromDatabase($sql, $params);
} elseif ($id !== null) {
    // Získání receptů pro danou kategorii
    $columns = array("id", "nazev", "obrazek");
    $table = "recepty WHERE kategorie = " . $id;
    $result = $web->getDataFromDatabase($columns, $table);

    // Zobrazení dat podle id kategorie
    $sql = "SELECT id, nazev, obrazek FROM recepty WHERE kategorie = :categoryId";
    $params = array('categoryId' => $id);
    $web->displayDataFromDatabase($sql, $params);
} else {
    echo "Invalid category ID.";
}

require_once 'html/footer.php';
?>

