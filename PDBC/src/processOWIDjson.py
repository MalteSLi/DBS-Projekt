import psycopg2
import json

##### Alte Tabellen löschen und neu erstellen #####
try:
    conn = None    
    conn = psycopg2.connect(host="localhost", database="dbs_project_covid19", user="postgres", password="20postgres20")
    cur = conn.cursor()
    cur.execute('SELECT * FROM country') # Testen ob Tabellen vorhanden
except (Exception, psycopg2.DatabaseError) as error:
    print(error)

##### Covid19.json öffnen #####
with open('../data/owid-covid-data.json') as json_file:
    data = json.load(json_file)
    schluessel = data.keys()
    for k in schluessel:
        pop_dens = hos_beds_pt = ""
        ### fetch country data and correct if neccessary ###
        if "population_density" in data[k]:
            pop_dens = str(data[k]['population_density'])
        if len(pop_dens) == 0:
            pop_dens = "NULL"
        if "hospital_beds_per_thousand" in data[k]:
            hos_beds_pt = str(data[k]['hospital_beds_per_thousand'])
        if len(hos_beds_pt) == 0:
            hos_beds_pt = "NULL"

        update_country_records = "UPDATE country SET c_population_density = "+pop_dens+", c_hospital_beds_pt = "+hos_beds_pt+" WHERE c_country_code = '"+k+"';"
        
        try:
            cur.execute(update_country_records)
            conn.commit()
        except (Exception, psycopg2.DatabaseError) as error:
            print(error)
        
        ### fetch country's day data and correct it if neccessary ###
        days = data[k]["data"]
        for day in days:
            y = m = d = tests = string_index = ""
            date = day["date"].split("-")
            if "total_tests" in day:
                tests = str(day['total_tests'])
            if len(tests) == 0:
                tests = "NULL"
            if "stringency_index" in day:
                string_index = str(day['stringency_index'])
            if len(string_index) == 0:
                string_index = "NULL"

            get_dayid = "SELECT h_dayID FROM has, country WHERE h_geoID = c_geoid AND c_country_code = '"+k+"' AND h_day = "+date[2]+" AND h_month = "+date[1]+" AND h_year = "+date[0]+";"

            try:
                cur.execute(get_dayid)
                res = cur.fetchall()
                if(len(res) > 1):
                    print("FEHLER: Mehrere Tage kommen in Frage!")
                    print("Stack:",res)
                    break
                elif (len(res) == 0):
                    id = "null"
                else:
                    id = str(res[0][0])
            except (Exception, psycopg2.DatabaseError) as error:
                print(error)

            update_daydata_records = "UPDATE daydata SET d_test = "+tests+", d_sringency_index = "+string_index+" WHERE d_dayid = "+id+";"
            
            try:
                cur.execute(update_daydata_records)
                conn.commit()
            except (Exception, psycopg2.DatabaseError) as error:
                print(error)
