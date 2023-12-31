<head>
    <meta charset="utf-8">
    <title>Moje stránka s recepty</title>
    <link rel="stylesheet" href="style.css">
    <img src="html/pasta-svgrepo-com.svg" alt="Obrázek špaget" width="32" height="32">
</head>

<?php
include 'dnWeb.php';
$web = new dnWeb();
?>

<header>
    <div class = 'header'>
        <h1><a class = "header-link" href="index.php">Moje stránka s recepty</a></h1>

            <!-- Rozbalovací seznam kategorií -->
            <?php echo $web->getCategoriesDropdown(); ?>

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
        <!-- Vyhledávací pole -->
        <form action="">
                <label for="nz">Hledat:</label>
                <input type="text" size="30" onkeyup="showResult(this.value)" onclick="showResult(this.value)">
                <div id="livesearch"></div>
            
        </form>
    </div>
    </header>
