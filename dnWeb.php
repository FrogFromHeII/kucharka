<?php 

class dnWeb {
    private $conn;

    function __construct() {
        $this->connectToDatabase();
    }
// připojení k databázi
    private function connectToDatabase() {
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
                $sql = 
                "INSERT INTO recepty (nazev, nazev_html, ingredience, postup, obrazek, cas, kategorie) 
                VALUES (:nazev, :nazev_html, :ingredience, :postup, :obrazek, :cas, :kategorie)";
                $params = [
                    'nazev' => $recept['nazev'], 
                    'nazev_html' => $recept['nazev_html'], 
                    'ingredience' => $recept['ingredience'], 
                    'postup' => $recept['postup'], 
                    'obrazek' => $recept['obrazek'], 
                    'cas' => $recept['cas'], 
                    'kategorie' => $recept['kategorie']
                ];
                $this->executeQuery($sql, $params);
            }
        }
    }

// vyčištění souboru .json
    function clearJsonFile() {
        file_put_contents('HTMLRecepty.json', '');
    }

// načtení a zobrazení dat z databáze, podle volitelného SQL a parametrů
    function displayDataFromDatabase($sql, $params = array()) {
        $result = $this->executeQuery($sql, $params, true);

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

// obecné načtení dat z volitelné databáze
    function getDataFromDatabase($columns, $table) {
        $sql = "SELECT " . implode(", ", $columns) . " FROM " . $table;
        return $this->executeQuery($sql, [], true);
    }

// obecná funkce s povinými argumenty pro SQL příkaz a pro parametry k němu
    function executeQuery($sql, $params, $fetchAll = false) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        if ($fetchAll) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
    
// přidání hodnocení receptu
    function addRating($recept, $hodnoceni) {
        $sql = "INSERT INTO hodnoceni (recept, hodnoceni) VALUES (:recept, :hodnoceni)";
        $this->executeQuery($sql, ['recept' => $recept, 'hodnoceni' => $hodnoceni]);
    }

// Rozbalovací okno se všemi kategoriemi z databáze
    function getCategoriesDropdown() {
        // Získání kategorií z databáze
        $columns = array("id", "nazev");
        $result = $this->getDataFromDatabase($columns, "kategorie");
        // Vytvoření rozbalovacího seznamu
        $dropdown = "<select onchange='window.location.href=this.value;'>\n";
        $dropdown .= "<option value=''>Vyberte kategorii</option>\n";
        foreach($result as $row) {
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
