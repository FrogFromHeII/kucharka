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

    function getBaseSql() {
        return "SELECT id, nazev, obrazek, cas FROM recepty";
    }

// vložení dat ze souboru .json do databáze
    function insertDataToDatabase($data) {
        if ($data == true) {
            foreach ($data as $recept) {
                $sql = 
                "INSERT INTO recepty (nazev, nazev_html, ingredience, postup, obrazek, cas, kategorie, suroviny) 
                VALUES (:nazev, :nazev_html, :ingredience, :postup, :obrazek, :cas, :kategorie, :suroviny)";
                $params = [
                    'nazev' => $recept['nazev'], 
                    'nazev_html' => $recept['nazev_html'], 
                    'ingredience' => $recept['ingredience'], 
                    'postup' => $recept['postup'], 
                    'obrazek' => $recept['obrazek'], 
                    'cas' => $recept['cas'], 
                    'kategorie' => $recept['kategorie'],
                    'suroviny' => $recept['suroviny']
                ];
                $this->executeQuery($sql, $params);
            }
        }
    }

    function displayDataFromDatabase($sql, $params = array(), $limit = null) {
        $result = $this->executeQuery($sql, $params, true);
    
        if ($limit !== null) {
            $result = array_slice($result, 0, $limit);
        }
    
        if (count($result) > 0) {
            foreach ($result as $row) {
                echo "<a class = 'rowFrontPage' href='recept.php?id=" . $row["id"] ."-nz=". $row["nazev"] . "'>";
                echo "<img src ='" . $row['obrazek'] . "'class = 'frontPageItemPicture' alt = 'Obrázek k receptu'>"
                . "<div class = frontPageText>" . $row["nazev"] . "</div>"
                . "<div class = 'frontPageTime'>" . $row["cas"] . "</div>";
                echo "</a>";
            }
        }
    }

// obecné načtení dat z volitelné databáze
    function getDataFromDatabase($columns, $table) {
        $sql = "SELECT " . implode(", ", $columns) . " FROM " . $table;
        return $this->executeQuery($sql, [], true);
    }

// obecná funkce s povinými argumenty pro SQL příkaz a pro parametry k němu
function executeQuery($sql, $params = array(), $fetchAll = false) {
    try {
        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }

        if ($fetchAll) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("Error executing query: " . $e->getMessage());
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
        $dropdown = "<div class='dropdown'>\n";
        $dropdown .= "<span>Vyberte kategorii</span>\n";
        $dropdown .= "<div class='dropdown-content'>\n";
        
        foreach($result as $row) {
            $dropdown .= "<p><a href='searchResult.php?id=" . $row["id"] . "'>" . $row["nazev"] . "</a></p>\n";
        }
        
        $dropdown .= "</div>\n";
        $dropdown .= "</div>\n";
        
        return $dropdown;
    }

    function getIngredientsButtons() {
        // Získání surovin z databáze
        $columns = array("id", "nazev");
        $result = $this->getDataFromDatabase($columns, "suroviny");
        // Vytvoření seznamu tlačítek
        $buttons = "";
        foreach($result as $row) {
            $buttons .= "<button class='button' onclick='window.location.href=\"searchResult.php?id=" . $row["id"] . "\"'>" . $row["nazev"] . "</button>\n";
        }
        return $buttons;
    }    
  
// odpojení databáze
    function closeConnection() {
        $this->conn = null;
    }
}

?>