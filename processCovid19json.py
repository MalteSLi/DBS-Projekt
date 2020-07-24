import psycopg2

create_table_country = """CREATE TABLE IF NOT EXISTS Country (
    c_name varchar,
    c_country_code varchar,
    "c_geoID" varchar,
    c_continent varchar,
    c_population_density varchar,
    c_hospital_beds_pt integer,
    primary key ("c_geoID"));"""

create_table_daydata = """CREATE TABLE IF NOT EXISTS DayData (
    d_dayID serial PRIMARY KEY,
    d_population integer,
    d_cases integer,
    d_deaths integer,
    d_test integer,
    d_sringency_index decimal);"""

create_table_has = """CREATE TABLE IF NOT EXISTS Has (
    h_ID serial PRIMARY KEY,
    h_geoID integer FOREIGN KEY REFERENCES Country,
    h_dayID integer FOREIGN KEY REFERENCES DayData,
    h_day integer,
    h_month integer,
    h_year integer);"""

try:
    conn = None    
    conn = psycopg2.connect(host="localhost", database="dbs_project_covid19", user="postgres", password="20postgres20")
    cur = conn.cursor()
    cur.execute(create_table_country)
    cur.execute(create_table_daydata)
    cur.execute(create_table_has)
except (Exception, psycopg2.DatabaseError) as error:
    print(error)
finally:
    #closing database connection.
    if(conn):
        cur.close()
        conn.close()
        print("PostgreSQL connection is closed")
