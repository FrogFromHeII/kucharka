

<?php require_once 'html/header.php'; ?>

<body>
  <div class="flex-container">  

  <?php

  $data = $web->loadDataFromJson();
  $web->insertDataToDatabase($data);
  $web->clearJsonFile();
  $sql = "SELECT id, nazev, obrazek FROM recepty";
  $web->displayDataFromDatabase($sql);
  ?>

  </div>
</body>

<?php require_once 'html\footer.php' ?>

</html>