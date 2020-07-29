<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" href="style.css" type="text/css">

        <!--required meta tags-->
        <meta charset = "utf-8">
        <title>Covid19Data</title>
    
    </head>
    <body>
        
        <!--Dropdownmenu für Länder und Parameter erstellen-->
        <div id="wrapper">
            <header><h1>Liniendiegramm-Editor</h1></header>
            <!--Liniendiagramm einfügen-->
            <section>
                <canvas id="myChart" width="400" height="200"></canvas>
            <!--Chart.js einbinden-->
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                <script src="Liniendiagramm.js"></script>
                <script src="index.js"></script>

                <?php
                    //echo php_ini_loaded_file();
                    //echo phpinfo();
                    $host        = "host = localhost";
                    $port        = "port = 5432";
                    $dbname      = "dbname = dbs_project_covid19";
                    $credentials = "user = postgres password=20postgres20";

                    $db = pg_connect( "$host $port $dbname $credentials"  );
                    if(!$db) {
                        echo "<p>Error : Unable to open database</p>";
                    } else {
                        echo "<p>Opened database successfully</p>";
                    }
                    $query = 'SELECT * FROM country';
                    $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());

                    // Ergebnisse in HTML ausgeben
                    echo "<p><b>Alle Einträge aus Tabelle 'Country'</b></p>";
                    echo "<table>\n";
                    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                        echo "\t<tr>\n";
                        foreach ($line as $col_value) {
                            echo "\t\t<td>$col_value</td>\n";
                        }
                        echo "\t</tr>\n";
                    }
                    echo "</table>\n";
                ?>
            </section>

            <aside>
                <form name="my-form" action="#">
                    <p class="form-box">
                      <label for "country" title="Country">Country</label>
        
                       <select id="region" name="region">
                           <optgroup label="Countries">
                               <option value="Antigua_and_Barbuda">Antigua and Barbuda</option>
                               <option value="Argentinia">Argentinia</option>
                               <option value="Armenia">Armenia</option>
                               <option value="Aruba">Aruba</option>
                               <option value="Australia">Australia</option>
                               <option value="Azerbaijan">Azerbaijan</option>
                           </optgroup>
                       </select>
                    </p>
        
                    <p class="form-box">
                        <label for "property" title="Property">Property</label>
          
                         <select id="property" name="property">
                             <optgroup label="Properties">
                                 <option value="cases">Cases</option>
                                 <option value="deaths">Deaths</option>
                                 <option value="tests">Tests</option>
                                 <option value="stringency_index">Sringency Index</option>
                                 <option value="population">Population</option>
                             </optgroup>
                         </select>
                      </p>
        
                  
                </form>
            </aside>
            
           
        </div>
        

    </body>
</html>
