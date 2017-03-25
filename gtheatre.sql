use heroku_d0dc4a6713d6673;

-- DROP TABLE IF EXISTS TheatreHalls;
-- DROP TABLE IF EXISTS Movies;
-- DROP TABLE IF EXISTS Tickets;
-- DROP TABLE IF EXISTS Plays;
-- DROP TABLE IF EXISTS Customers;
-- DROP TABLE IF EXISTS Employees;
-- DROP TABLE IF EXISTS Foods;
-- DROP TABLE IF EXISTS Bundle;
-- DROP TABLE IF EXISTS Associated_Tickets
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
     TPrice    DEC(4,2) DEFAULT 9.00,
    --  SoldCount int(4),
    --  Qty       int(4),
     PRIMARY KEY (Title, RYear)
  );

CREATE TABLE Associated_Tickets
  (
    Title       char(20) NOT NULL,
    RYear       int(4) NOT NULL,
    TicketNo    int NOT NULL UNIQUE AUTO_INCREMENT,
    Qty         int NOT NULL,
    CreditCard  char(16) NOT NULL,
    Login       char(20) NOT NULL,
    TPrice      DEC(4,1) DEFAULT 9.00,
    PRIMARY KEY (Title, RYear, TicketNo)
  );

CREATE TABLE Plays
  (
     STime      time NOT NULL,
     ETime      time NOT NULL,
     HNumber    int(4),
     Title      char(20) NOT NULL,
     RYear      int NOT NULL,
    --  PRIMARY KEY (STime, ETime),
     PRIMARY KEY (HNumber, Title, RYear),
     FOREIGN KEY (HNumber) REFERENCES TheatreHalls (HNumber) ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (Title, RYear) REFERENCES Movies (Title, RYear) ON DELETE CASCADE ON UPDATE CASCADE
  );

CREATE TABLE Customers
  (
     CreditCard char(16) NOT NULL,
     Customer_Login      char(20) NOT NULL,
     Customer_Password   char(20) NOT NULL,
     FirstName  char(20),
     PRIMARY KEY (Customer_Login)
  );

CREATE TABLE Employees
  (
     SSN       int NOT NULL,
     Employee_Login     char(20) NOT NULL,
     Employee_Password  char(20) NOT NULL,
     FirstName char(20),
     UNIQUE (SSN),
     PRIMARY KEY (Employee_Login)
  );

CREATE TABLE Foods
  (
     FType  char(16) NOT NULL,
     FPrice int NOT NULL,
     PRIMARY KEY (FType)
  );

CREATE TABLE Bundle
  (
	 FType char(16) NOT NULL,
	 Title char(20) NOT NULL,
	 RYear int(4) NOT NULL,
	 TicketNo int NOT NULL UNIQUE AUTO_INCREMENT,
	 PRIMARY KEY (FType, Title, RYear, TicketNo),
	 FOREIGN KEY (FType) REFERENCES Foods (FType),
     FOREIGN KEY (Title, RYear) REFERENCES Movies (Title, RYear) ON DELETE CASCADE ON UPDATE CASCADE,
	 FOREIGN KEY (TicketNo) REFERENCES Associated_Tickets (TicketNo) ON DELETE CASCADE ON UPDATE CASCADE
  );

--
-- Adding in data
--

-- INSERT TheatreHall data
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
INSERT INTO Employees values(923184183, 'unicorns', 'areReal', 'unicorn');
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
INSERT INTO Movies values('Star Wars', 2015, 'PG-13', 136, 9);
INSERT INTO Movies values('Cinderella', 2015, 'G', 106, 9);
INSERT INTO Movies values('Frozen', 2013, 'G', 102, 9);
INSERT INTO Movies values('Zootopia', 2016, 'G', 108, 9);
INSERT INTO Movies values('LaLaLand', 2016, 'PG', 128, 9);

-- INSERT Bundle data
INSERT INTO Bundle values('Popcorn', 'Star Wars', 2015, 1);
INSERT INTO Bundle values('Pretzels', 'Cinderella', 2015, 2);
INSERT INTO Bundle values('Corndogs', 'Frozen', 2013, 3);
INSERT INTO Bundle values('Poutine', 'Zootopia', 2016, 4);
INSERT INTO Bundle values('Nachos', 'LaLaLand', 2016, 5);

-- INSERT Plays data
INSERT INTO Plays values("17:00:00", "19:00:00", 1, "Star Wars", 2015);
INSERT INTO Plays values("11:00:00", "13:00:00", 2, "Star Wars", 2015);
INSERT INTO Plays values("9:00:00", "10:30:00", 3, "Cinderella", 2015);
INSERT INTO Plays values("15:00:00", "16:30:00", 4, "Frozen", 2013);
INSERT INTO Plays values("15:30:00", "17:00:00", 5, "Zootopia", 2016);

-- INSERT Tickets data
INSERT INTO Associated_Tickets values('Zootopia', 2016, 1, 1, 1672789028338884, 'seanlennaerts', 9);
INSERT INTO Associated_Tickets values('Zootopia', 2016, 2, 1, 1234809109292392, 'ginnieisawesome304', 9);
INSERT INTO Associated_Tickets values('LaLaLand', 2016, 3, 1, 1672789028338884, 'seanlennaerts', 9);
INSERT INTO Associated_Tickets values('LaLaLand', 2016, 4, 1, 8948392921823922, 'jwpark', 9);
INSERT INTO Associated_Tickets values('LaLaLand', 2016, 5, 1, 9000340918239329, 'prettyprincess', 9);
