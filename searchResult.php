<?php require_once 'html/header.php'; ?>
<h2>Nalezené recepty:</h2>
<div class="flex-container">
    <?php // Získání ID kategorie z URL
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    // Získání živého vyhledávání
    $q = isset($_GET['q']) ? $_GET['q'] : '';

    // Display search results if search query is provided
    if (!empty($q)) {
        // Zobrazení dat podle živého vyhledávání
        $baseSql = $web->getBaseSql();
        $sql = "$baseSql WHERE LOWER(nazev) LIKE :searchTerm";
        $params = array('searchTerm' => '%' . strtolower($q) . '%');
        $web->displayDataFromDatabase($sql, $params);
    } elseif ($id !== null) {
        // Získání receptů pro danou kategorii
        $columns = array("id", "nazev", "obrazek");
        $table = "recepty WHERE kategorie = " . $id;
        $result = $web->getDataFromDatabase($columns, $table);

        // Zobrazení dat podle id kategorie
        $baseSql = $web->getBaseSql();
        $sql = "$baseSql WHERE kategorie = :categoryId";
        $params = array('categoryId' => $id);
        $web->displayDataFromDatabase($sql, $params);
    }

    require_once 'html/footer.php';
    ?>
</div>