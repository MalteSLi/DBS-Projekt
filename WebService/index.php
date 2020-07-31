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
        
        <header><h1>Liniendiegramm-Editor</h1></header>
        
        <div id="wrapper1" class="wrapper">
            <h2>Direkter Ländervergleich</h2>
            <h2 id="chart1Header"></h2>
            <section>
                <h2 id="chart1Header"></h2>
                <canvas id="myChart" width="500" height="300"></canvas>
            </section>

            <p id="testAusgabe"></p>
            <aside>
                <form name="menu1" action="#">
                    <p class="form-box">
                        <label for="country" title="Country">Land 1</label>
                        <select id="region1" name="region1">
                            <optgroup label="Countries">
                                <option value="null">null</option>
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
                        <label for="country" title="Country">Land 2</label>
                        <select id="region2" name="region2">
                            <optgroup label="Countries">
                                <option value="null">null</option>
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
                        <label for="country" title="Country">Land 3</label>
                        <select id="region3" name="region3">
                            <optgroup label="Countries">
                                <option value="null">null</option>
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
                        <label for="property" title="Property">Parameter</label>
          
                        <select id="property" name="property">
                            <optgroup label="Properties">
                                <option value="null">null</option>
                                <option value="d_cases">Cases</option>
                                <option value="d_deaths">Deaths</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Sringency Index</option>
                            </optgroup>
                        </select>
                    </p>

                    <button type="submit" id="refresh" onclick="">Grafik Aktualisieren</button>
                    <!----- Den Inhalt der ersten Längerauswahl auslesen und die Daten aus der Datenbank holen ----->
                    <?php                   
                        // Property-Daten für alle Tage der Länder abfragen und in einem Array speichern
                        /*********** Property holen ************/
                        if(isset($_GET['property'])) {
                            $property = $_GET['property'];
                        } else {
                            $property = "null";
                        }


                        /*********** Daten für Property von Country 1 holen ************/
                        if(isset($_GET['region1'])) {
                            $region1 = $_GET['region1'];
                        } else {
                            $region1 = "null";
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
                        if(isset($_GET['region2'])) {
                            $region2 = $_GET['region2'];
                        } else {
                            $region2 = "null";
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
                        if(isset($_GET['region3'])) {
                            $region3 = $_GET['region3'];
                        } else {
                            $region3 = "null";
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
                        var property = <?php echo json_encode($property); ?>;
                        document.getElementById('property').value = property;
                        var xLabels = <?php echo json_encode($dates); ?>;
                        var data1 = <?php echo json_encode($dataForCountry1); ?>;
                        var region1 = <?php echo json_encode($region1); ?>;
                        var data2 = <?php echo json_encode($dataForCountry2); ?>;
                        var region2 = <?php echo json_encode($region2); ?>;
                        var data3 = <?php echo json_encode($dataForCountry3); ?>;
                        var region3 = <?php echo json_encode($region3); ?>;

                        document.getElementById("chart1Header").innerHTML = "Vergleichs-Parameter: "+property.substring(2);
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
                                    backgroundColor: 'rgba(255,100,0,1)',
                                    borderColor: 'rgba(255,100,0,1)',
                                    data: data2,
                                }, {
                                    label: region3,
                                    fill: false,
                                    backgroundColor: 'rgba(50,200,200,1)',
                                    borderColor: 'rgba(50,200,200,1)',
                                    data: data3,
                                }]
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        ticks: {
                                            beginAtZero: false
                                        }
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: false
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </form>
            </aside>

        </div>
        
        <div id="wrapper2" class="wrapper">
            <h2>Land-Statistiken</h2>
            <h2 id="chart2Header"></h2>
            <section>
                <canvas id="myChart2" width="500" height="300"></canvas>
            </section>

            <p id="testAusgabe"></p>
            <aside>
                <form name="menu1" action="#">
                    <p>
                        <label for="country" title="Country">Land</label>
                        <select id="region_single" name="region_single">
                            <optgroup label="Countries">
                                <option value="null">null</option>
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
                        <label for="property" title="Property">Parameter 1</label>
          
                        <select id="property_single1" name="property_single1">
                            <optgroup label="Properties">
                                <option value="null">null</option>
                                <option value="d_cases">Cases</option>
                                <option value="d_deaths">Deaths</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Sringency Index</option>
                            </optgroup>
                        </select>
                    </p>
                    <p class="form-box">
                        <label for="property" title="Property">Parameter 2</label>
          
                        <select id="property_single2" name="property_single2">
                            <optgroup label="Properties">
                                <option value="null">null</option>
                                <option value="d_cases">Cases</option>
                                <option value="d_deaths">Deaths</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Sringency Index</option>
                            </optgroup>
                        </select>
                    </p>

                    <button type="submit" id="refresh" onclick="">Grafik Aktualisieren</button>
                    <!----- Den Inhalt der ersten Längerauswahl auslesen und die Daten aus der Datenbank holen ----->
                    <?php                   
                        // Property-Daten für alle Tage des Landes abfragen und in einem Array speichern
                        /*********** Property auslesen ************/
                        if(isset($_GET['property_single1'])) {
                            $property_single1 = $_GET['property_single1'];
                        } else {
                            $property_single1 = "null";
                        }
                        if(isset($_GET['property_single2'])) {
                            $property_single2 = $_GET['property_single2'];
                        } else {
                            $property_single2 = "null";
                        }


                        /*********** Daten für Property1,2 und 3 von Land holen ************/
                        if(isset($_GET['region_single'])) {
                            $region_single = $_GET['region_single'];
                        } else {
                            $region_single = "null";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property_single1 FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region_single' ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dates = [];
                        $dataForProperty1 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dates, $row[2]."-".$row[1]."-".$row[0]);
                            array_push($dataForProperty1, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT h_day, h_month, h_year, $property_single2 FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region_single' ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dates = [];
                        $dataForProperty2 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dates, $row[2]."-".$row[1]."-".$row[0]);
                            array_push($dataForProperty2, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                    ?>
                    <!-------------------------------------------->
                    <script type="text/javascript">
                        var property_single1 = <?php echo json_encode($property_single1); ?>;
                        document.getElementById('property_single1').value = property_single1;
                        var property_single2 = <?php echo json_encode($property_single2); ?>;
                        document.getElementById('property_single2').value = property_single2;
                        var xLabels = <?php echo json_encode($dates); ?>;
                        var data1 = <?php echo json_encode($dataForProperty1); ?>;
                        var data2 = <?php echo json_encode($dataForProperty2); ?>;
                        var region_single = <?php echo json_encode($region_single); ?>;

                        document.getElementById("chart2Header").innerHTML = "Vergleichs-Parameter: "+region_single;
                        var myChartObject = document.getElementById('myChart2');
                        new Chart(myChartObject, {
                            type: 'line',
                            data: {
                                labels: xLabels,
                                datasets: [{
                                    label: property_single1,
                                    fill: false,
                                    backgroundColor: '#B3D6C6',
                                    borderColor: '#B3D6C6',
                                    yAxisID: 'A',
                                    data: data1,
                                }, {
                                    label: property_single2,
                                    fill: false,
                                    backgroundColor: 'rgba(255,100,0,1)',
                                    borderColor: 'rgba(255,100,0,1)',
                                    yAxisID: 'B',
                                    data: data2,
                                }]
                            },
                            options: {
                                scales: {
                                    xAxes: [{
                                        ticks: {
                                            beginAtZero: false
                                        }
                                    }],
                                    yAxes: [{
                                            id: 'A',
                                            type: 'linear',
                                            position: 'left',
                                        },{
                                            id: 'B',
                                            type: 'linear',
                                            position: 'right',
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }
                                    ]
                                }
                            }
                        });
                    </script>
                </form>
            </aside>

        </div>
    </body>
</html>
