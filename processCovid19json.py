import psycopg2
import json

drop_table = 'DROP TABLE IF EXISTS '

create_table_country = '''CREATE TABLE IF NOT EXISTS Country (
    c_name varchar,
    c_country_code varchar,
    c_geoID varchar,
    c_continent varchar,
    c_population_density double precision,
    c_hospital_beds_pt double precision,
    primary key (c_geoID));'''

create_table_daydata = '''CREATE TABLE IF NOT EXISTS DayData (
    d_dayID integer PRIMARY KEY,
    d_population integer,
    d_cases integer,
    d_deaths integer,
    d_test integer,
    d_sringency_index double precision);'''

create_table_has = '''CREATE TABLE IF NOT EXISTS Has (
    h_ID serial PRIMARY KEY,
    h_geoID varchar,
    h_dayID integer,
    h_day integer,
    h_month integer,
    h_year integer);'''

##### Alte Tabellen löschen und neu erstellen #####
try:
    conn = None    
    conn = psycopg2.connect(host="localhost", database="dbs_project_covid19", user="postgres", password="20postgres20")
    cur = conn.cursor()
    ### erst Tabellen löschen und dann neu erstellen. ###
    cur.execute(drop_table+'Country'+';')
    conn.commit()
    cur.execute(create_table_country)
    conn.commit()
    cur.execute(drop_table+'DayData'+';')
    conn.commit()
    cur.execute(create_table_daydata)
    conn.commit()
    cur.execute(drop_table+'Has'+';')
    conn.commit()
    cur.execute(create_table_has)
    conn.commit()
except (Exception, psycopg2.DatabaseError) as error:
    print(error)

##### Covid19.json öffnen #####
with open('covid19.json') as json_file:
    data = json.load(json_file)
    dayID = 0
    name = code = geoid = geoID_alt = continent = day = month = year = cases = deaths = pop = ""
    for rec in data['records']:
        ### Daten holen und bereinigen ###
        if "countriesAndTerritories" in rec:
            name = rec['countriesAndTerritories']
        if len(name) == 0:
            name = "NULL"
        if "countryterritoryCode" in rec:
            code = rec['countryterritoryCode']
        if len(code) == 0:
            code = "NULL"
        geoID_alt = geoid
        if "geoId" in rec:
            geoid = rec['geoId']
        if len(geoid) == 0:
            geoid = "NULL"
        if "continentExp" in rec:
            continent = rec['continentExp']
        if len(continent) == 0:
            continent = "NULL"
        if "day" in rec:
            day = rec['day']
        if len(day) == 0:
            day = "NULL"
        if "month" in rec:
            month = rec['month']
        if len(month) == 0:
            month = "NULL"
        if "year" in rec:
            year = rec['year']
        if len(year) == 0:
            year = "NULL"
        if "cases" in rec:
            cases = rec['cases']
        if len(cases) == 0:
            cases = "NULL"
        if "deaths" in rec:
            deaths = rec['deaths']
        if len(deaths) == 0:
            deaths = "NULL"
        if "popData2018" in rec:
            pop = rec['popData2018']
        if len(pop) == 0:
            pop = "NULL"
        
        ### SQL Queries ###
        if geoID_alt != geoid:
            insert_country = "INSERT INTO Country (c_name,c_country_code,c_geoID,c_continent) VALUES ("+"'"+name+"','"+code+"','"+geoid+"','"+continent+"');"
        insert_daydata = "INSERT INTO DayData (d_dayID,d_population,d_cases,d_deaths) VALUES ("+str(dayID)+","+pop+","+cases+","+deaths+");"
        insert_has = "INSERT INTO Has (h_geoID,h_dayID,h_day,h_month,h_year) VALUES ('"+geoid+"',"+str(dayID)+","+day+","+month+","+year+");"
        
        ### commit sql queries
        try:
            if geoID_alt != geoid:
                cur.execute(insert_country)
                conn.commit()
            cur.execute(insert_daydata)
            conn.commit()
            cur.execute(insert_has)
            conn.commit()
            dayID += 1
        except (Exception, psycopg2.DatabaseError) as error:
            print(error)
    print("Inserted: ",dayID,"records")

##### Verbindung zur Datenbank schließen #####
if(conn):
    cur.close()
    conn.close()
    print("PostgreSQL connection is closed")