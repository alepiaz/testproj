create table Companies(
name varchar(255) NOT null,
email varchar(255),
website varchar(255),
primary key(name));


create table Employees(
first_name varchar(255) NOT null,
last_name varchar(255) NOT null,
company varchar(255),
email varchar(255),
phone varchar(255),
CONSTRAINT pk_employees PRIMARY KEY (first_name,last_name),
FOREIGN KEY(company) REFERENCES Companies(name)
);

