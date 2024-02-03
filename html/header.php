<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title>Moje stránka s recepty</title>
    <link rel="stylesheet" href="style.css">
    <script src="cookie.js"></script>
</head>

<?php
include 'dnWeb.php';
$web = new dnWeb();
?>

<header class="header">
    <div class="header__inner">

        <!-- Obrázek s názvem jako pokliknutelný odkaz -->
        <a class="logo" href="index.php">
            <img src="html/pasta-svgrepo-com.svg" alt="Obrázek špaget" width="32" height="32">
        </a>
        <h1><a class="header__link" href="index.php">Ondrova Kuchařka</a></h1>
        <!-- Funkce pro systém vyhledávání -->
        <script>
            function showResult(str) {
                if (str.length == 0) {
                    document.getElementById("livesearch").innerHTML = "";
                    document.getElementById("livesearch").style.border = "0px";
                    return;
                }
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("livesearch").innerHTML = this.responseText;
                        document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
                    }
                };
                xmlhttp.open("GET", "liveSearch.php?q=" + str, true);
                xmlhttp.send();
            }

            // Redirect to search_results.php when the form is submitted
            function redirectToResults() {
                var searchValue = document.getElementById("searchInput").value;
                window.location.href = "searchResult.php?q=" + searchValue;
            }
        </script>
        
        <!-- Load icon library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Vyhledávací pole -->
        <form class="search-form" onsubmit="redirectToResults(); return false;">
            <input id="searchInput" class="search-form__input" type="text" placeholder="Vyhledávání" size="30" onkeyup="showResult(this.value)" onclick="showResult(this.value)" name="search">
            <button class="search-form__button" type="submit"><i class="fa fa-search"></i></button>
            <div id="livesearch"></div>
        </form>

        <!-- Rozbalovací seznam kategorií -->
        <div class = 'header__drop-down'> <?php echo $web->getCategoriesDropdown(); ?> </div>

</header>