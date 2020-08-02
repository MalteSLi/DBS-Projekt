<!DOCTYPE html>
<html>
    <head>

        <link rel="stylesheet" href="style1.css" type="text/css">

        <!--required meta tags-->
        <meta charset = "utf-8">
        <title>Covid19DataStats</title>
        <!--Chart.js einbinden-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    
    </head>
    <body>
        
        <header><h1>Weltweite Covid-19 Statistiken</h1></header>
        
        <div id="wrapper1" class="wrapper">
            <h2>Direkter Ländervergleich</h2>
            <h2 id="chart1Header"></h2>
            <section>
                <h2 id="chart1Header"></h2>
                <canvas id="myChart" width="500" height="200"></canvas>
            </section>

            <p id="testAusgabe"></p>
            <section>
                <form name="menu1" action="#">
                    <p class="form-box">
                        <label for="country" title="Country">Land 1</label>
                        <select id="region1" name="region1">
                            <optgroup label="Länder">
                                <option value="null">null</option>
                                <!----- PHP script um die Länder1 für das Drop-Down Menü zu holen ----->
                                <?php
                                    $host        = "host = localhost";
                                    $port        = "port = 5432";
                                    $dbname      = "dbname = dbs_project_covid19";
                                    $credentials = "user = postgres password=Claudius1";

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
                    <p id="regionInfo1"></p>
                    <p>
                        <label for="country" title="Country">Land 2</label>
                        <select id="region2" name="region2">
                            <optgroup label="Länder">
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
                    <p id="regionInfo2"></p>
                    <p>
                        <label for="country" title="Country">Land 3</label>
                        <select id="region3" name="region3">
                            <optgroup label="Länder">
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
                    <p id="regionInfo3"></p>
        
                    <p class="form-box">
                        <label for="property" title="Property">Parameter</label>
          
                        <select id="property" name="property">
                            <optgroup label="Parameter">
                                <option value="null">null</option>
                                <option value="d_cases">Fälle</option>
                                <option value="d_deaths">Tote</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Strenge-Index</option>
                            </optgroup>
                        </select>
                    </p>
                    
                    <p class="form-box">
                        <label for="date" title="date">Zeitraum</label>
          
                        <input id="fromDate" name="fromDate" type="date" value="2019-12-31" min="2019-12-31" max="2020-06-14"> - <input id="toDate" name="toDate" type="date" min="2019-12-31" max="2020-06-14" value="2020-06-14">
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
                        if(isset($_GET['fromDate'])) {
                            $fromDate = $_GET['fromDate'];
                        } else {
                            $fromDate = "2019-12-31";
                        }
                        if(isset($_GET['toDate'])) {
                            $toDate = $_GET['toDate'];
                        } else {
                            $toDate = "2020-06-14";
                        }

                        /*********** Daten für Property von Country 1 holen ************/
                        if(isset($_GET['region1'])) {
                            $region1 = $_GET['region1'];
                        } else {
                            $region1 = "null";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region1'
                            AND ((h_year = ".substr($fromDate,0,4)." AND h_month = ".substr($fromDate,5,2)." AND h_day >= ".substr($fromDate,8,2).") OR (h_year >= ".substr($fromDate,0,4)." AND h_month > ".substr($fromDate,5,2).") OR (h_year > ".substr($fromDate,0,4).")) 
                            AND ((h_year = ".substr($toDate,0,4)." AND h_month = ".substr($toDate,5,2)." AND h_day <= ".substr($toDate,8,2).") OR (h_year <= ".substr($toDate,0,4)." AND h_month < ".substr($toDate,5,2).") OR (h_year < ".substr($toDate,0,4)."))
                            ORDER BY h_year, h_month, h_day";
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
                        $query = "SELECT c_name, c_population, c_population_density, c_hospital_beds_pt FROM country WHERE c_geoid = '$region1'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region1 = $row;
                        }
                        pg_free_result($result);


                        /************* Daten für Property von Country 2 holen ************/
                        if(isset($_GET['region2'])) {
                            $region2 = $_GET['region2'];
                        } else {
                            $region2 = "null";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region2' 
                            AND ((h_year = ".substr($fromDate,0,4)." AND h_month = ".substr($fromDate,5,2)." AND h_day >= ".substr($fromDate,8,2).") OR (h_year >= ".substr($fromDate,0,4)." AND h_month > ".substr($fromDate,5,2).") OR (h_year > ".substr($fromDate,0,4).")) 
                            AND ((h_year = ".substr($toDate,0,4)." AND h_month = ".substr($toDate,5,2)." AND h_day <= ".substr($toDate,8,2).") OR (h_year <= ".substr($toDate,0,4)." AND h_month < ".substr($toDate,5,2).") OR (h_year < ".substr($toDate,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dataForCountry2 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dataForCountry2, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name, c_population, c_population_density, c_hospital_beds_pt FROM country WHERE c_geoid = '$region2'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region2 = $row;
                        }
                        pg_free_result($result);


                        /************* Daten für Property von Country 3 holen ************/
                        if(isset($_GET['region3'])) {
                            $region3 = $_GET['region3'];
                        } else {
                            $region3 = "null";
                        }
                        $query = "SELECT h_day, h_month, h_year, $property FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region3'
                            AND ((h_year = ".substr($fromDate,0,4)." AND h_month = ".substr($fromDate,5,2)." AND h_day >= ".substr($fromDate,8,2).") OR (h_year >= ".substr($fromDate,0,4)." AND h_month > ".substr($fromDate,5,2).") OR (h_year > ".substr($fromDate,0,4).")) 
                            AND ((h_year = ".substr($toDate,0,4)." AND h_month = ".substr($toDate,5,2)." AND h_day <= ".substr($toDate,8,2).") OR (h_year <= ".substr($toDate,0,4)." AND h_month < ".substr($toDate,5,2).") OR (h_year < ".substr($toDate,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dataForCountry3 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dataForCountry3, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name, c_population, c_population_density, c_hospital_beds_pt FROM country WHERE c_geoid = '$region3'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region3 = $row;
                        }
                        pg_free_result($result);
                    ?>
                    <!---------------------  --------------------->
                    <script type="text/javascript">
                        var property = <?php echo json_encode($property); ?>;
                        document.getElementById('property').value = property;
                        var xLabels = <?php echo json_encode($dates); ?>;

                        /*** Region 1 bearbeiten ***/
                        var data1 = <?php echo json_encode($dataForCountry1); ?>;
                        var region1 = <?php echo json_encode($region1); ?>;
                        if(region1 != "null")
                            document.getElementById('regionInfo1').innerHTML = "Aktiv: "+region1[0]+"<br>Bevölkerung: "+region1[1]+" Ew.<br>Bevölkerungsdichte: "+region1[2]+" Ew./km^2<br>Krankenhausbetten/1000Ew.: "+region1[3]+"";

                        /*** Region 2 bearbeiten ***/
                        var data2 = <?php echo json_encode($dataForCountry2); ?>;
                        var region2 = <?php echo json_encode($region2); ?>;
                        if(region2 != "null")
                            document.getElementById('regionInfo2').innerHTML = "Aktiv: "+region2[0]+"<br>Bevölkerung: "+region2[1]+" Ew.<br>Bevölkerungsdichte: "+region2[2]+" Ew./km^2<br>Krankenhausbetten/1000Ew.: "+region2[3]+"";
                        
                        /*** Region 3 bearbeiten ***/
                        var data3 = <?php echo json_encode($dataForCountry3); ?>;
                        var region3 = <?php echo json_encode($region3); ?>;
                        if(region3 != "null")
                            document.getElementById('regionInfo3').innerHTML = "Aktiv: "+region3[0]+"<br>Bevölkerung: "+region3[1]+" Ew.<br>Bevölkerungsdichte: "+region3[2]+" Ew./km^2<br>Krankenhausbetten/1000Ew.: "+region3[3]+"";

                        document.getElementById("chart1Header").innerHTML = "Vergleichs-Parameter: "+property.substring(2);
                        var myChartObject = document.getElementById('myChart');
                        new Chart(myChartObject, {
                            type: 'line',
                            data: {
                                labels: xLabels,
                                datasets: [{
                                    label: region1[0],
                                    fill: false,
                                    backgroundColor: '#B3D6C6',
                                    borderColor: '#B3D6C6',
                                    data: data1,
                                }, {
                                    label: region2[0],
                                    fill: false,
                                    backgroundColor: 'rgba(255,100,0,1)',
                                    borderColor: 'rgba(255,100,0,1)',
                                    data: data2,
                                }, {
                                    label: region3[0],
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
            </section>

        </div>
        
        <div id="wrapper2" class="wrapper">
            <h2>Statistiken einer Region</h2>
            <h2 id="chart2Header"></h2>
            <section>
                <canvas id="myChart2" width="500" height="200"></canvas>
            </section>

            <p id="testAusgabe"></p>
            <section>
                <form name="menu1" action="#">
                    <p>
                        <label for="country" title="Country">Land</label>
                        <select id="region_single" name="region_single">
                            <optgroup label="Länder">
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
                    <p id="region_singleInfo"></p>
        
                    <p class="form-box">
                        <label for="property" title="Property">Parameter 1</label>
          
                        <select id="property_single1" name="property_single1">
                            <optgroup label="Parameter">
                                <option value="null">null</option>
                                <option value="d_cases">Fälle</option>
                                <option value="d_deaths">Tote</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Strenge-Index</option>
                                <option value="cases_pht">Fälle pro 100k Einwohner</option>
                                <option value="deaths_pht">Tote pro 100k Einwohner</option>
                            </optgroup>
                        </select>
                    </p>
                    <p class="form-box">
                        <label for="property" title="Property">Parameter 2</label>
          
                        <select id="property_single2" name="property_single2">
                            <optgroup label="Parameter">
                                <option value="null">null</option>
                                <option value="d_cases">Fälle</option>
                                <option value="d_deaths">Tote</option>
                                <option value="d_test">Tests</option>
                                <option value="d_sringency_index">Strenge-Index</option>
                                <option value="cases_pht">Fälle pro 100k Einwohner</option>
                                <option value="deaths_pht">Tote pro 100k Einwohner</option>
                            </optgroup>
                        </select>
                    </p>
                    <p class="form-box">
                        <label for="date" title="date">Zeitraum</label>
          
                        <input id="fromDate_single" name="fromDate_single" type="date" value="2019-12-31" min="2019-12-31" max="2020-06-14"> - <input id="toDate_single" name="toDate_single" type="date" min="2019-12-31" max="2020-06-14" value="2020-06-14">
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
                        if(isset($_GET['region_single'])) {
                            $region_single = $_GET['region_single'];
                        } else {
                            $region_single = "null";
                        }
                        if(isset($_GET['fromDate_single'])) {
                            $fromDate_single = $_GET['fromDate_single'];
                        } else {
                            $fromDate_single = "2019-12-31";
                        }
                        if(isset($_GET['toDate_single'])) {
                            $toDate_single = $_GET['toDate_single'];
                        } else {
                            $toDate_single = "2020-06-14";
                        }

                        /*********** Daten für Property1 und 2 von region_single holen ************/
                        
                        if($property_single1 == "cases_pht") {
                            $query = "SELECT h_day, h_month, h_year, ((d_cases/c_population)*100000) FROM has, daydata, country WHERE h_dayid = d_dayid AND c_geoid = h_geoid AND h_geoid = '$region_single'
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }elseif($property_single1 == "deaths_pht") {
                            $query = "SELECT h_day, h_month, h_year, ((d_deaths/c_population)*100000) FROM has, daydata, country WHERE h_dayid = d_dayid AND c_geoid = h_geoid AND h_geoid = '$region_single' 
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }else {
                            $query = "SELECT h_day, h_month, h_year, $property_single1 FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region_single' 
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dates = [];
                        $dataForProperty1 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dates, $row[2]."-".$row[1]."-".$row[0]);
                            array_push($dataForProperty1, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        if($property_single2 == "cases_pht") {
                            $query = "SELECT h_day, h_month, h_year, ((d_cases/c_population)*100000) FROM has, daydata, country WHERE h_dayid = d_dayid AND c_geoid = h_geoid AND h_geoid = '$region_single'
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }elseif($property_single2 == "deaths_pht") {
                            $query = "SELECT h_day, h_month, h_year, ((d_deaths/c_population)*100000) FROM has, daydata, country WHERE h_dayid = d_dayid AND c_geoid = h_geoid AND h_geoid = '$region_single' 
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }else {
                            $query = "SELECT h_day, h_month, h_year, $property_single2 FROM has, daydata WHERE h_dayid = d_dayid AND h_geoid = '$region_single' 
                            AND ((h_year = ".substr($fromDate_single,0,4)." AND h_month = ".substr($fromDate_single,5,2)." AND h_day >= ".substr($fromDate_single,8,2).") OR (h_year >= ".substr($fromDate_single,0,4)." AND h_month > ".substr($fromDate_single,5,2).") OR (h_year > ".substr($fromDate_single,0,4).")) 
                            AND ((h_year = ".substr($toDate_single,0,4)." AND h_month = ".substr($toDate_single,5,2)." AND h_day <= ".substr($toDate_single,8,2).") OR (h_year <= ".substr($toDate_single,0,4)." AND h_month < ".substr($toDate_single,5,2).") OR (h_year < ".substr($toDate_single,0,4)."))
                            ORDER BY h_year, h_month, h_day";
                        }
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());     
                        $dates = [];
                        $dataForProperty2 = [];
                        while ($row = pg_fetch_array($result)) {
                            array_push($dates, $row[2]."-".$row[1]."-".$row[0]);
                            array_push($dataForProperty2, ['x' => $row[2]."-".$row[1]."-".$row[0], 'y' => $row[3]]);
                            //echo $row[0]."/".$row[1]."/".$row[2]." ".$property.":".$row[3]."<br>";  // Zum Überprüfen/Ausgaben der Daten
                        }
                        pg_free_result($result);
                        $query = "SELECT c_name, c_population, c_population_density, c_hospital_beds_pt FROM country WHERE c_geoid = '$region_single'";
                        $result = pg_query($query) or die('Abfrage fehlgeschlagen: ' . pg_last_error());
                        while ($row = pg_fetch_array($result)) {
                            $region_single = $row;
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
                        if(region_single != "null")
                            document.getElementById('region_singleInfo').innerHTML = "Aktiv: "+region_single[0]+"<br>Bevölkerung: "+region_single[1]+" Ew.<br>Bevölkerungsdichte: "+region_single[2]+" Ew./km^2<br>Krankenhausbetten/1000Ew.: "+region_single[3]+"";

                        document.getElementById("chart2Header").innerHTML = "Region: "+region_single[0];
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
            </section>

        </div>
    </body>
</html>
