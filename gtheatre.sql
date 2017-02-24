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

