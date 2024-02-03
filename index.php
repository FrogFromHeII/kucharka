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
    $data = $web->loadDataFromJson();
    $web->insertDataToDatabase($data);
    $web->clearJsonFile();
    $sql = "SELECT id, nazev, obrazek FROM recepty";
    $web->displayDataFromDatabase($sql, array(), 12); 
    ?>

  </div>

  <div class="flex-container">
  
  <div class="column">
    <h3 class="rowFrontPage__text-header">Nejlépe hodnocené:</h3>
    <?php $web->displayDataFromDatabase($sql, array(), 8) ?>
  </div>
      
  <div class="column">
<h3 class="rowFrontPage__text-header">Nově přidané:</h3>
    <?php $web->displayDataFromDatabase($sql, array(), 8) ?>
  </div>
</div>

</body>

<?php require_once 'html\footer.php' ?>