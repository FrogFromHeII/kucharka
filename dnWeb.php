<?php 

class dnWeb {
    private $conn;

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

                $sql = "INSERT INTO recepty (nazev, nazev_html, ingredience, postup, obrazek) VALUES (?, ?, ?, ?, ?)";
                $stmt= $this->conn->prepare($sql);
                $stmt->execute([$nazev, $nazev_html, $ingredience, $postup, $obrazek]);
            }
        }
    }

// vyčištění souboru .json
    function clearJsonFile() {
        file_put_contents('HTMLRecepty.json', '');
    }

// načtení dat a vytvoření dynamických webů
    function displayDataFromDatabase() {
        $sql = "SELECT id, nazev, obrazek FROM recepty";
        $result = $this->conn->query($sql);

        if ($result->rowCount() > 0) {
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<a class = 'rowFrontPage' href='recept.php?id=" . $row["id"] ."-nz=". $row["nazev"] . "'>";
                echo "<img src ='" . $row['obrazek'] . "'class = 'frontPageItemPicture' alt = 'Obrázek k receptu'>"
                . "<div class = frontPageText>" . $row["nazev"] . "</div>";
                echo "</a>";
            }
        } else {
            echo "0 výsledků";
        }
    }

// odpojení databáze
    function closeConnection() {
        $this->conn = null;
    }
}

?>
