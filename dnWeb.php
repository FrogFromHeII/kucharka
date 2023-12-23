<?php 

class dnWeb {
    private $conn;

    function __construct() {
        $this->connectToDatabase();
    }
// připojení k databázi
    function connectToDatabase() {
        include 'database.php';
        $db = new Database();
        $this->conn = $db->dbConnection();
    }

// zíkání dat ze souboru .json
    function loadDataFromJson() {
        $json = file_get_contents('HTMLRecepty.json');
        return json_decode($json, true);
    }

// vložení dat ze souboru .json do databáze
    function insertDataToDatabase($data) {
        if ($data == true) {
            foreach ($data as $recept) {
                $nazev = $recept['nazev'];
                $nazev_html = $recept['nazev_html'];
                $ingredience = $recept['ingredience'];
                $postup = $recept['postup'];
                $obrazek = $recept['obrazek'];
                $cas = $recept['cas'];
                $kategorie = $recept['kategorie'];

                $sql = "INSERT INTO recepty (nazev, nazev_html, ingredience, postup, obrazek, cas, kategorie) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt= $this->conn->prepare($sql);
                $stmt->execute([$nazev, $nazev_html, $ingredience, $postup, $obrazek, $cas, $kategorie]);
            }
        }
    }

// vyčištění souboru .json
    function clearJsonFile() {
        file_put_contents('HTMLRecepty.json', '');
    }

// načtení a zobrazení dat z databáze, podle volitelného SQL a parametrů
function displayDataFromDatabase($sql, $params = array()) {
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        foreach ($result as $row) {
            echo "<a class = 'rowFrontPage' href='recept.php?id=" . $row["id"] ."-nz=". $row["nazev"] . "'>";
            echo "<img src ='" . $row['obrazek'] . "'class = 'frontPageItemPicture' alt = 'Obrázek k receptu'>"
            . "<div class = frontPageText>" . $row["nazev"] . "</div>";
            echo "</a>";
        }
    } else {
        echo "V této kategorii nejsou žádné recepty.";
    }
}

// volitelné načtení dat z volitelné databáze
    function getDataFromDatabase($columns, $table) {
        $sql = "SELECT " . implode(", ", $columns) . " FROM " . $table;
        $result = $this->conn->query($sql);
        return $result;
    }

// získání receptu podle ID
    function getReceptById($id) {
        $sql = "SELECT recepty.*, kategorie.nazev AS kategorie_nazev FROM recepty 
                JOIN kategorie ON recepty.kategorie = kategorie.id 
                WHERE recepty.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

// získání průměrného hodnocení receptu
    function getAverageRating($id) {
        $sql = "SELECT AVG(hodnoceni) as average_rating FROM hodnoceni WHERE recept = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

// získání celkového počtu hodnocení
    function getTotalRatings($id) {
        $sql = "SELECT COUNT(*) as total FROM hodnoceni WHERE recept = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

// přidání hodnocení receptu
    function addRating($recept, $hodnoceni) {
        $sql = "INSERT INTO hodnoceni (recept, hodnoceni) VALUES (:recept, :hodnoceni)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['recept' => $recept, 'hodnoceni' => $hodnoceni]);
    }

// Rozbalovací okno se všemi kategoriemi z databáze
    function getCategoriesDropdown() {
        // Získání kategorií z databáze
        $columns = array("id", "nazev");
        $result = $this->getDataFromDatabase($columns, "kategorie");

        // Vytvoření rozbalovacího seznamu
        $dropdown = "<select onchange='window.location.href=this.value;'>\n";
        $dropdown .= "<option value=''>Vyberte kategorii</option>\n";
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dropdown .= "<option value='category.php?id=" . $row["id"] . "'>" . $row["nazev"] . "</option>\n";
        }
        $dropdown .= "</select>\n";

        return $dropdown;
    }
    
// odpojení databáze
    function closeConnection() {
        $this->conn = null;
    }
}

?>
