const {Client} = require('pg')
const client = new Client({
	user:"postgres", 
	password:"...",
	host: "localhost",
	port: 5432,
	database: "dbs_project_covid19"
})

client.connect()
.then(()=> console.log("Connected successeefuly"))
.then(()=> client.query("select c_name from country"))
.then(results => console.table(results.rows))
.catch(e => console.log(e))
.finally(() => client.end())
