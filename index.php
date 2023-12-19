<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>Moje stránka s recepty</title>
    <link rel="stylesheet" href="style.css">

<!-- Funkce pro systém vyhledávání -->
<script>
function showResult(str) {
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","liveSearch.php?q="+str,true);
  xmlhttp.send();
}
</script>

</head>

<?php require_once 'html/header.php'; ?>

<body>
<!-- Vyhledávací pole -->
  <form action="">
    <label for="nz">Hledat:</label>
    <input type="text" size="30" onkeyup="showResult(this.value)">
    <div id="livesearch"></div>
  </form>
  
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

<?php require_once 'html\footer.php' ?>

</html>
