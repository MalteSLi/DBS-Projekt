

create table Country (
c_name varchar,
c_country_code varchar,
"c_geoID" varchar,
c_continent varchar,
c_population_density varchar,
c_hospital_beds_pt integer,
primary key ("c_geoID")
);


create table DayData (
d_population integer,
d_cases integer,
d_deaths integer,
d_test integer,
d_sringency_index decimal,
d_dayID date,
constraint DayData primary key (dayID)
);