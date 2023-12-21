<?php 
include 'html/header.html';
include 'dnWeb.php';

$web = new dnWeb();
$web->connectToDatabase();
$id = $_GET['id'];
$recept = $web->getReceptById($id);
$rating_avg = $web->getAverageRating($id);
$rating_sum = $web->getTotalRatings($id);

// Přidání hodnocení do databáze, pokud bylo odesláno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hodnoceni = $_POST["hodnoceni"];
    $web->addRating($id, $hodnoceni);
    // Aktualizace hodnocení po přidání nového hodnocení
    $rating = $web->getAverageRating($id);
    $total_ratings = $web->getTotalRatings($id);
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
                    <p class="recept-hodnoceni"><?= round($rating_avg['average_rating'] ?? 0, 2) . '/5 (' . $rating_sum . ' hodnocení)' ?></p>
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
include 'html/footer.html';
?>
