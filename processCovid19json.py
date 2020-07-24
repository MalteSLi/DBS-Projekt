import psycopg2
import json

create_table_country = '''CREATE TABLE IF NOT EXISTS Country (
    c_name varchar,
    c_country_code varchar,
    c_geoID varchar,
    c_continent varchar,
    c_population_density varchar,
    c_hospital_beds_pt integer,
    primary key (c_geoID));'''

create_table_daydata = '''CREATE TABLE IF NOT EXISTS DayData (
    d_dayID integer PRIMARY KEY,
    d_population integer,
    d_cases integer,
    d_deaths integer,
    d_test integer,
    d_sringency_index decimal);'''

create_table_has = '''CREATE TABLE IF NOT EXISTS Has (
    h_ID serial PRIMARY KEY,
    h_geoID varchar,
    h_dayID integer,
    h_day integer,
    h_month integer,
    h_year integer);'''

##### Tabellen in DB erstellen, wenn noch nicht vorhanden #####
try:
    conn = None    
    conn = psycopg2.connect(host="localhost", database="dbs_project_covid19", user="postgres", password="20postgres20")
    cur = conn.cursor()
    print ( conn.get_dsn_parameters(),"\n")
    cur.execute(create_table_country)
    conn.commit()
    cur.execute(create_table_daydata)
    conn.commit()
    cur.execute(create_table_has)
    conn.commit()
except (Exception, psycopg2.DatabaseError) as error:
    print(error)

##### Covid19.json öffnen #####
with open('covid19.json') as json_file:
    data = json.load(json_file)
    dayID = 0
    geoid = "NULL"
    for rec in data['records']:
        ### fetch data and correct if neccessary ###
        if "countriesAndTerritories" in rec:
            name = rec['countriesAndTerritories']
        else:
            name = "NULL"
        if "countryterritoryCode" in rec:
            code = rec['countryterritoryCode']
        else:
            code = "NULL"
        geoID_alt = geoid
        if "geoId" in rec:
            geoid = rec['geoId']
        else:
            geoid = "NULL"
        if "continentExp" in rec:
            continent = rec['continentExp']
        else:
            continent = "NULL"
        if "day" in rec:
            day = rec['day']
        else:
            day = "NULL"
        if "month" in rec:
            month = rec['month']
        else:
            month = "NULL"
        if "year" in rec:
            year = rec['year']
        else:
            year = "NULL"
        if "cases" in rec:
            cases = rec['cases']
        else:
            cases = "NULL"
        if "deaths" in rec:
            deaths = rec['deaths']
        else:
            deaths = "NULL"
        if "popData2018" in rec:
            pop = rec['popData2018']
        else:
            pop = "NULL"
        
        ### SQL Queries ###
        if geoID_alt != geoid:
            insert_country = "INSERT INTO Country (c_name,c_country_code,c_geoID,c_continent) VALUES ("+"'"+name+"','"+code+"','"+geoid+"','"+continent+"');"
        insert_daydata = "INSERT INTO DayData (d_dayID,d_population,d_cases,d_deaths) VALUES ("+str(dayID)+","+pop+","+cases+","+deaths+");"
        insert_has = "INSERT INTO Has (h_geoID,h_dayID,h_day,h_month,h_year) VALUES ('"+geoid+"',"+str(dayID)+","+day+","+month+","+year+");"
        
        ### commit sql queries
        if geoID_alt != geoid:
            cur.execute(insert_country)
            conn.commit()
        cur.execute(insert_daydata)
        conn.commit()
        cur.execute(insert_has)
        conn.commit()
        dayID += 1
    print(dayID)

#closing database connection
if(conn):
    cur.close()
    conn.close()
    print("PostgreSQL connection is closed")