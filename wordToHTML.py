import os
import mammoth
import json
import shutil
import docx2txt

# Cesta k složce s dokumenty Word
folder_path = "C:\\xampp\\htdocs\\kucharka\\Recepty"
new_folder_path = "C:\\xampp\\htdocs\\kucharka\\ReceptyVDatabazi"
image_folder_path = "C:\\xampp\\htdocs\\kucharka\\ReceptyVDatabazi\\Obrázky_k_receptům"  # Složka pro uložení obrázků

# Seznam pro ukládání receptů
recepty = []

# Procházíme všechny soubory ve složce
for filename in os.listdir(folder_path):
    # Kontrolujeme, zda je soubor dokument Word (.docx)
    if filename.endswith(".docx"):
        # Vytváříme úplnou cestu k souboru
        file_path = os.path.join(folder_path, filename)

        with open(file_path, "rb") as docx_file:
            # Převedení Word dokumentu na text
            text = docx2txt.process(docx_file)

            # Rozdělení textu na název, ingredience a postup
            nazev, kategorie, suroviny, cas, ingredience, postup, picture_path = text.split(";", 6)

            # Převedení textu na HTML
            with open(file_path, "rb") as docx_file:
                        result = mammoth.convert_to_html(docx_file)
                        html = result.value # obsahuje převedený HTML
                        messages = result.messages # obsahuje jakékoliv zprávy, například o nepodporovaných funkcích Wordu

            nazev_html, kategire_html, suroviny_html, cas_html, ingredience_html, postup_html, picture_path_html = html.split(";", 6)

            # Uložení do asociativního seznamu
            recept = {
                "nazev": nazev,
                "nazev_html": nazev_html,
                "ingredience": ingredience_html,
                "postup": postup_html,
                "obrazek": picture_path,
                "cas": cas,
                "kategorie": kategorie,
                "suroviny": suroviny
            }

            # Přidání receptu do seznamu receptů
            recepty.append(recept)

        # Přesun souboru do nové složky
        shutil.move(file_path, os.path.join(new_folder_path, filename))

# Uložení seznamu receptů do JSON souboru
with open('HTMLRecepty.json', 'w') as json_file:
    json.dump(recepty, json_file)