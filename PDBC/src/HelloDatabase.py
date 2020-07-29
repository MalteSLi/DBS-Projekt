import psycopg2

def go():
    conn = None    
    conn = psycopg2.connect(host="localhost", database="dbs_project_covid19", user="postgres", password="20postgres20")
    cur = conn.cursor()
    cur.execute('SELECT version();')
    for record in cur:
        print("Record: ",record)
    cur.close()            
    if conn is not None:
        conn.close()
try:
    go()
except (Exception, psycopg2.DatabaseError) as error:
    print(error)