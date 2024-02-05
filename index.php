<?php require_once 'html/header.php'; ?>

<body>

  <div class="button-container">
    <?php echo $web->getIngredientsButtons();?>
  </div>

  <div class="rowFrontPage__text-header">
    <h2>Doporučené recepty:</h2>
  </div>

  <div class="flex-container">
    
    <?php
    // zíkání dat ze souboru .json
      $json = file_get_contents('HTMLRecepty.json');
      $data = json_decode($json, true);
      $web->insertDataToDatabase($data);
      // vyčištění souboru .json
      file_put_contents('HTMLRecepty.json', '');
      $sql = $web->getBaseSql();
      $web->displayDataFromDatabase($sql, array(), 12); 
    ?>

  </div>

  <div class="flex-container">
  
  <div class="column">
    <h3 class="rowFrontPage__text-header">Nejlépe hodnocené:</h3>
    <?php
    //POZOR na aktualizaci SQL!!
      $sql = "SELECT r.id, r.nazev, r.obrazek, r.cas, AVG(h.hodnoceni) as average_rating 
      FROM hodnoceni h
      JOIN recepty r ON h.recept = r.id
      GROUP BY r.id, r.nazev, r.obrazek
      ORDER BY average_rating DESC";
      $web->displayDataFromDatabase($sql, array(), 8) 
    ?>
  </div>

  <div class="column">
<h3 class="rowFrontPage__text-header">Nově přidané:</h3>
    <?php 
      $baseSql = $web->getBaseSql();
      $sql = "$baseSql ORDER BY id DESC";
      $web->displayDataFromDatabase($sql, array(), 8)
    ?>
  </div>
</div>

</body>

<?php require_once 'html\footer.php' ?>