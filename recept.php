<?php 
require_once 'html/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT recepty.*, kategorie.nazev AS kategorie_nazev FROM recepty 
        JOIN kategorie ON recepty.kategorie = kategorie.id 
        WHERE recepty.id = :id";
$recept = $web->executeQuery($sql, ['id' => $id]);

$rating_avg = $web->executeQuery("SELECT AVG(hodnoceni) as average_rating FROM hodnoceni WHERE recept = :id", ['id' => $id]);
$rating_sum = $web->executeQuery("SELECT COUNT(*) as total FROM hodnoceni WHERE recept = :id", ['id' => $id]);

// Přidání hodnocení do databáze, pokud bylo odesláno
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hodnoceni"])) {
    $hodnoceni = intval($_POST["hodnoceni"]);
    $web->addRating($id, $hodnoceni);
    // Aktualizace hodnocení po přidání nového hodnocení
    $rating_avg = $web->executeQuery("SELECT AVG(hodnoceni) as average_rating FROM hodnoceni WHERE recept = :id", ['id' => $id]);
    $rating_sum = $web->executeQuery("SELECT COUNT(*) as total FROM hodnoceni WHERE recept = :id", ['id' => $id]);
}
?>


<?php if ($recept): ?>
    <div class="recept-container">
        <div class="recept-header">
            <img class="recept-obrazek" src= "<?= $recept["obrazek"] ?>" alt= "Obrázek k receptu">
            <div>
                <h1 class="recept-nazev"><?= $recept["nazev"] ?></h1>
                <div style="display: inline-block;">

                <!-- tlačítka na hodnocení -->
                <?php foreach(range(1,5) as $rating): ?>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="hodnoceni" value="<?php echo $rating; ?>"><?php echo $rating; ?></button>
                    </form>
                <?php endforeach ?>

            </div>
                <div class="recept-info">
                    <p class="recept-hodnoceni"><?= round($rating_avg['average_rating'] ?? 0, 2) . '/5 (' . $rating_sum['total'] . ' hodnocení)' ?></p>
                    <p class="recept-kategorie"><?= $recept["kategorie_nazev"] ?></p>
                    <p class="recept-cas"><?= $recept["cas"] ?></p>
                </div>
            </div>
        </div>
        <div class="recept-body">
            <div class="recept-ingredience">
                <p><?= $recept["ingredience"] ?></p>
            </div>
            <div class="recept-postup">
                <p><?= $recept["postup"] ?></p>
            </div>
        </div>
    </div>
<?php else: ?>
    Recept nebyl nalezen.
<?php endif; ?>

<?php
$conn = null;
require_once 'html/footer.php';
?>

<?php
$conn = null;
include 'html/footer.php';
?>
