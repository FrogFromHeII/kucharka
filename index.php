<!DOCTYPE html>
<html lang="cs">

<?php require_once 'html/header.html'; ?>

<body>
  <div class="flex-container">  

  <?php
  include 'dnWeb.php';
  $web = new dnWeb();
  $web->connectToDatabase();
  $data = $web->loadDataFromJson();
  $web->insertDataToDatabase($data);
  $web->clearJsonFile();
  $web->displayDataFromDatabase();
  $web->closeConnection();
  ?>
  
  </div>
</body>

<?php require_once 'html\footer.html' ?>

</html>
