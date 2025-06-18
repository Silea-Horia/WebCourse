drop table Category;
drop table Product;

create table Category (
	Id int primary key identity(1,1),
	Name varchar(50) not null default('')
	);


create table Product (
	Id int primary key identity(1,1),
	Name varchar(50) not null default(''),
	Price float not null default(0),
	CategoryId int references Category(Id)
	);

insert into Category values 
	('Home'),
	('Tech'),
	('Food'),
	('Kitchen');

select * from Category;

insert into Product values
	('Sofa', 1250, 1),
	('Bed', 1900, 1),
	('Smartphone', 5000, 2),
	('Headphones', 500, 2),
	('USB Stick', 78, 2),
	('Croissant', 1.5, 3),
	('Bread', 11.99, 3),
	('Fork', 4, 4);

select * from Product;