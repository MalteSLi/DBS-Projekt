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

            </section>

            <aside>
                <form name="my-form" action="#">
                    <p class="form-box">
                        <label for "country" title="Country">Country</label>
                        <select id="region" name="region">
                            <optgroup label="Countries">
                                <!--PHP script um die Länder für das Drop-Down Menü zu holen-->
                                <?php
                                    $host        = "host = localhost";
                                    $port        = "port = 5432";
                                    $dbname      = "dbname = dbs_project_covid19";
                                    $credentials = "user = postgres password=20postgres20";

                                    $db = pg_connect( "$host $port $dbname $credentials"  );
                                    if(!$db) {
                                        echo "<p>Error : Unable to open database</p>";
                                    }
                                    $query = 'SELECT c_name FROM country ORDER BY c_name ASC';
                                    $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());

                                    // Länder im Array speichern
                                    $countrys = [];
                                    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                                        foreach ($line as $col_value) {
                                            //array_push($countrys, $col_value);
                                            echo "<option value='$col_value'>$col_value</option>";
                                        }
                                    }
                                ?>
                            </optgroup>
                        </select>
                    </p>
        
                    <p class="form-box">
                        <label for "property" title="Property">Property</label>
          
                        <select id="property" name="property">
                            <optgroup label="Properties">
                                <option value="d_cases">Cases</option>
                                <option value="d_deaths">Deaths</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Sringency Index</option>
                                <option value="population">Population</option>
                            </optgroup>
                        </select>
                    </p>
              
                </form>
            </aside>
               
        </div>
        
    </body>
</html>
