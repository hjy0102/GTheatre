--  Drop any existing tables. Any errors are ignored.
--
DROP TABLE IF EXISTS TheatreHalls;
DROP TABLE IF EXISTS Movies;
DROP TABLE IF EXISTS Tickets;
DROP TABLE IF EXISTS Plays;
DROP TABLE IF EXISTS Customers;
DROP TABLE IF EXISTS Employees;
DROP TABLE IF EXISTS Foods;
DROP TABLE IF EXISTS Buys;
-- may be commented out later after testing

--
-- Add each table 
-- 

CREATE TABLE TheatreHalls
  ( 
     HNumber  int(4), 
     Capacity int(4) NOT NULL, 
     PRIMARY KEY (HNumber) 
  ); 

CREATE TABLE Movies
  ( 
     Title     char(20) NOT NULL, 
     RYear     int(4) NOT NULL, 
     MRating   char(5), 
     Length    int(4), 
     TPrice    DEC(4,2) DEFAULT $9.00, 
     SoldCount int(4), 
     Qty       int(4), 
     PRIMARY KEY (Title, RYear) 
  ); 

CREATE TABLE Plays
  (
     STime      times NOT NULL,
     ETime      time NOT NULL,
     HallNumber int,
     Title      char(20),
     RYear      int,
     PRIMARY KEY (STime, ETime),
     FOREIGN KEY (HNumber) REFERENCES TheatreHalls (HNumber),
     FOREIGN KEY (Title) REFERENCES Movies (Title),
     FOREIGN KEY (RYear) REFERENCES Movies (RYear)
  );

CREATE TABLE Customers
  (
     CreditCard char(16) NOT NULL,
     Login      char(20) NOT NULL,
     Password   char(20) NOT NULL,
     FirstName  char(20),
     PRIMARY KEY (CreditCard)
  );

CREATE TABLE Employees
  (
     SIN       int NOT NULL,
     Login     char(20) NOT NULL,
     Password  char(20) NOT NULL,
     FirstName char(20),
     PRIMARY KEY (SIN)
  );

CREATE TABLE Foods
  (
     FType  int,
     FPrice int NOT NULL,
     PRIMARY KEY (FType)
  );

CREATE TABLE Buys
  (
     Title char(20) NOT NULL,
     RYear int NOT NULL,
     Qty   int NOT NULL,
     PRIMARY KEY (Title, RYear),
     FOREIGN KEY (Title) REFERENCES Movie(Title),
     FOREIGN KEY (RYear) REFERENCES Movie(RYear)
  );

--
-- Adding in data
--

-- Insert TheatreHall data

INSERT INTO TheatreHalls values(1, 140);
INSERT INTO TheatreHalls values(2, 150);
INSERT INTO TheatreHalls values(3, 200);
INSERT INTO TheatreHalls values(4, 220);
INSERT INTO TheatreHalls values(5, 200);

-- INSERT Customer data
INSERT INTO Customers values(1672789028338884, 'seanlennaerts', 'password123', 'Sean');
INSERT INTO Customers values(1234809109292392, 'ginnieisawesome304', '123456789', 'Ginnie');
INSERT INTO Customers values(8948392921823922, 'jwpark', '304bestcourseever', 'David');
INSERT INTO Customers values(8934712734823923, 'mikeeyoon', 'makecpscgreatagain', 'Mike');
INSERT INTO Customers values(9000340918239329, 'prettyprincess', 'MakeMikeGreatAgain2', 'Mike');

-- INSERT Employee data
INSERT INTO Employees values(871623499, 'TABen', 'password', 'Ben');
INSERT INTO Employees values(923184183, 'unicorn', 'areReal', NULL);
INSERT INTO Employees values(384928328, 'employeeOTMonth', 'all12months', 'Ginnie');
INSERT INTO Employees values(512834832, 'daduck', 'password', 'Donald');
INSERT INTO Employees values(876499102, 'TomHanks', 'isdabest', 'Tim');

-- INSERT Food data
INSERT INTO Foods values('Popcorn', 15);
INSERT INTO Foods values('Pretzels', 14);
INSERT INTO Foods values('Corndogs', 1);
INSERT INTO Foods values('Poutine', 15);
INSERT INTO Foods values('Nachos', 5);

-- INSERT Movie data
INSERT INTO Movies values('Star Wars: The Force Awakens', 2015, 'PG-13', 136,135, 2);
INSERT INTO Movies values('Cinderella', 2015, 'G', 106,80, 3);
INSERT INTO Movies values('Frozen', 2013, 'G', 102,121, 3);
INSERT INTO Movies values('Zootopia', 2016, 'G', 108,60, 2);
INSERT INTO Movies values('LaLaLand', 2016, 'PG', 128,99, 99);
