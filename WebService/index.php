<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" href="style.css" type="text/css">

        <!--required meta tags-->
        <meta charset = "utf-8">
        <title>Covid19Data</title>
        <!--Chart.js einbinden-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    
    </head>
    <body>
        
        <!--Dropdownmenu für Länder und Parameter erstellen-->
        <div id="wrapper">
            <header><h1>Liniendiegramm-Editor</h1></header>
            <!--Liniendiagramm einfügen-->
            <section>
                <canvas id="myChart" width="500" height="300"></canvas>
            </section>

            <p id="testAusgabe"></p>
            <aside>
                <form name="menu1" action="#">
                    <p class="form-box">
                        <label for="country" title="Country">Country 1</label>
                        <select id="region1" name="region1">
                            <optgroup label="Countries">
                                <!----- PHP script um die Länder1 für das Drop-Down Menü zu holen ----->
                                <?php
                                    $host        = "host = localhost";
                                    $port        = "port = 5432";
                                    $dbname      = "dbname = dbs_project_covid19";
                                    $credentials = "user = postgres password=20postgres20";

                                    $db = pg_connect( "$host $port $dbname $credentials"  );
                                    if(!$db) {
                                        echo "<p>Error : Unable to open database</p>";
                                    }
                                    $query = 'SELECT c_name, c_geoid FROM country ORDER BY c_name ASC';
                                    $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                                    
                                    // Länder in einem Array speichern
                                    $countries = [];
                                    while ($row = pg_fetch_array($result)) {
                                        array_push($countries, $row);
                                    }
                                    pg_free_result($result);
                                    // Länder im Drop Down Menü speichern
                                    foreach($countries as $c) {
                                        echo "<option value='".$c[1]."'>".$c[0]."</option>";
                                    }
                                ?>
                                <!---------------------  --------------------->
                            </optgroup>
                        </select>
                    </p>
                    <p>
                        <label for="country" title="Country">Country 2</label>
                        <select id="region2" name="region2">
                            <optgroup label="Countries">
                                <!----- PHP script um die Länder2 im Drop-Down Menü anzuzeigen ----->
                                <?php
                                    // Länder im Drop Down Menü speichern
                                    foreach($countries as $c) {
                                        echo "<option value='".$c[1]."'>".$c[0]."</option>";
                                    }
                                ?>
                                <!---------------------  --------------------->
                            </optgroup>
                        </select>
                    </p>
                    <p>
                        <label for="country" title="Country">Country 3</label>
                        <select id="region3" name="region3">
                            <optgroup label="Countries">
                                <!----- PHP script um die Länder2 im Drop-Down Menü anzuzeigen ----->
                                <?php
                                    // Länder im Drop Down Menü speichern
                                    foreach($countries as $c) {
                                        echo "<option value='".$c[1]."'>".$c[0]."</option>";
                                    }
                                ?>
                                <!---------------------  --------------------->
                            </optgroup>
                        </select>
                    </p>
        
                    <p class="form-box">
                        <label for="property" title="Property">Property</label>
          
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

                    <button type="submit" id="refresh" onclick="">Aktualisieren</button>
                    <!----- Den Inhalt der ersten Längerauswahl auslesen und die Daten aus der Datenbank holen ----->
                    <?php                   
                        // Property-Daten für alle Tage der Länder abfragen und in einem Array speichern
                        /*********** Property holen ************/
                        if(!($property = $_GET['property'])){
                            $property = "d_cases";
                        }


                        /*********** Daten für Property von Country 1 holen ************/
                        if(!($region1 = $_GET['region1'])){
                            $region1 = "DE";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region1' ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dates = []; 
                        $dataForCountry1 = [];
                        while ($row = pg_fetch_array($result)) {
                            //array_push($dates, $row[0]."/".$row[1]."/".$row[2]);
                            array_push($dates, $row[2]."-".$row[1]."-".$row[0]);
                            array_push($dataForCountry1, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name FROM country WHERE c_geoid = '$region1'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region1 = $row[0];
                        }
                        pg_free_result($result);


                        /************* Daten für Property von Country 2 holen ************/
                        if(!($region2 = $_GET['region2'])){
                            $region2 = "BE";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region2' ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dataForCountry2 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dataForCountry2, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name FROM country WHERE c_geoid = '$region2'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region2 = $row[0];
                        }
                        pg_free_result($result);


                        /************* Daten für Property von Country 3 holen ************/
                        if(!($region3 = $_GET['region3'])){
                            $region3 = "AU";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region3' ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dataForCountry3 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dataForCountry3, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name FROM country WHERE c_geoid = '$region3'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region3 = $row[0];
                        }
                        pg_free_result($result);
                    ?>
                    <!---------------------  --------------------->
                    <script type="text/javascript">
                        var xLabels = <?php echo json_encode($dates); ?>;
                        var data1 = <?php echo json_encode($dataForCountry1); ?>;
                        var region1 = <?php echo json_encode($region1); ?>;
                        var data2 = <?php echo json_encode($dataForCountry2); ?>;
                        var region2 = <?php echo json_encode($region2); ?>;
                        var data3 = <?php echo json_encode($dataForCountry3); ?>;
                        var region3 = <?php echo json_encode($region3); ?>;

                        var myChartObject = document.getElementById('myChart');
                        new Chart(myChartObject, {
                            type: 'line',
                            data: {
                                labels: xLabels,
                                datasets: [{
                                    label: region1,
                                    fill: false,
                                    backgroundColor: '#B3D6C6',
                                    borderColor: '#B3D6C6',
                                    data: data1,
                                }, {
                                    label: region2,
                                    fill: false,
                                    backgroundColor: '#B3D6C6',
                                    borderColor: 'rgba(255,0,0,1)',
                                    data: data2,
                                }, {
                                    label: region3,
                                    fill: false,
                                    backgroundColor: '#B3D6C6',
                                    borderColor: 'rgba(0    ,255,0,1)',
                                    data: data3,
                                }]
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </form>
            </aside>

        </div>
    </body>
</html>
