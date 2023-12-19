<?php 
include 'html/header.php';
include 'database.php';

$db = new Database();
$conn = $db->dbConnection();

$id = $_GET['id'];

$sql = "SELECT * FROM recepty WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$recept = $stmt->fetch(PDO::FETCH_ASSOC);

if ($recept) {
    echo "<h1>" . $recept["nazev"] . "</h1>";
    echo "<p>" . $recept["ingredience"] . "</p>";
    echo "<p>". $recept["postup"] . "</p>";
    echo '<img src= "'. $recept["obrazek"] .'"alt= "Obrázek k receptu"'. ">";
} else {
    echo "Recept nebyl nalezen.";
}

$conn = null;

include 'html/footer.php';
?>
