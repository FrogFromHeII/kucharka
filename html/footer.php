
<style>
  /* Styl pro vyskakovací okno */
  .cookie-popup {
    display: none;
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f9ed69;
    color: #222;
    text-align: center;
    padding: 20px;
    }
</style>

</div>
  <!-- Vyskakovací okno pro cookies -->
  <div id="cookiePopup" class="cookie-popup">
  Tato webová stránka používá cookies k vylepšení uživatelského zážitku.
  <button onclick="closePopup()">Rozumím</button>
  </div>

  <script>
    let myCookie = new Cookie('myCookie', 'myValue', 7);

    // Zkontroluje, zda cookie existuje
    if (!myCookie.checkCookie()) {
        // Vytvoří cookie
        myCookie.createCookie();
        // Zobrazí vyskakovací okno
        document.getElementById('cookiePopup').style.display = 'block';
    }

    // Funkce pro zavření vyskakovacího okna
    function closePopup() {
        document.getElementById('cookiePopup').style.display = 'none';
    }
</script>


<footer>
    <div class = 'footer'>
        <p>&copy; 2023 Moje stránka s recepty</p>
    </div>
</footer>
<?php 
$web->closeConnection();
?>
</html>