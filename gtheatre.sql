use heroku_d0dc4a6713d6673;

DROP TABLE IF EXISTS Tickets;
DROP TABLE IF EXISTS Customers;
DROP TABLE IF EXISTS Employees;
DROP TABLE IF EXISTS Bundle;
DROP TABLE IF EXISTS Foods;
DROP TABLE IF EXISTS Associated_Tickets;
DROP TABLE IF EXISTS Plays;
DROP TABLE IF EXISTS TheatreHalls;
DROP TABLE IF EXISTS Movies;

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
  
  CREATE TABLE Plays
  (
     STime      time NOT NULL,
     ETime      time NOT NULL,
     HNumber    int(4),
     Title      char(20) NOT NULL,
     RYear      int NOT NULL,
    --  PRIMARY KEY (STime, ETime),
     PRIMARY KEY (STime, HNumber, Title, RYear),
     FOREIGN KEY (HNumber) REFERENCES TheatreHalls (HNumber),
     FOREIGN KEY (Title, RYear) REFERENCES Movies (Title, RYear)
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
    STime       time NOT NULL,
    PRIMARY KEY (Title, RYear, TicketNo),
    FOREIGN KEY (Title, RYear) REFERENCES Movies (Title, RYear),
    FOREIGN KEY (STime) REFERENCES Plays(STime)
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
     SIN       int NOT NULL,
     Employee_Login     char(20) NOT NULL,
     Employee_Password  char(20) NOT NULL,
     FirstName char(20),
     UNIQUE (SIN),
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
	 STime time NOT NULL,
	 PRIMARY KEY (FType, Title, RYear, STime),
	 FOREIGN KEY (FType) REFERENCES Foods (FType),
     FOREIGN KEY (Title, RYear) REFERENCES Movies (Title, RYear),
	 FOREIGN KEY (STime) REFERENCES Plays (STime) ON DELETE CASCADE ON UPDATE CASCADE
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
INSERT INTO TheatreHalls values(6, 200);

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
INSERT INTO Movies values('Finding Nemo', 2003, 'G', 100, 9);
INSERT INTO Movies values('Ratatouille', 2007, 'G', 111, 9);
INSERT INTO Movies values('WALL-E', 2008, 'G', 98, 9);
INSERT INTO Movies values('Up', 2009, 'G', 120, 9);
INSERT INTO Movies values('Tangled', 2010, 'G', 100, 9);
INSERT INTO Movies values('Brave', 2012, 'G', 110, 9);
INSERT INTO Movies values('Wreck-It Ralph', 2012, 'G', 101, 9);
INSERT INTO Movies values('Monsters University', 2013, 'G', 104, 9);
INSERT INTO Movies values('Frozen', 2013, 'G', 102, 9);
INSERT INTO Movies values('Star Wars', 2016, '14A', 136, 9);
INSERT INTO Movies values('Beauty and the Beast', 2017, 'G', 129, 9);

-- INSERT Plays data
INSERT INTO Plays values('11:00:00', '12:55:00', 1, 'Finding Nemo', 2003);
INSERT INTO Plays values('13:00:00', '13:55:00', 1, 'Finding Nemo', 2003);
INSERT INTO Plays values('11:00:00', '13:06:00', 2, 'Ratatouille', 2007);
INSERT INTO Plays values('14:00:00', '16:06:00', 2, 'Ratatouille', 2007);
INSERT INTO Plays values('16:30:00', '18:23:00', 2, 'WALL-E', 2008);
INSERT INTO Plays values('11:50:00', '14:05:00', 2, 'Up', 2009);
INSERT INTO Plays values('12:15:00', '14:30:00', 3, 'Up', 2009);
INSERT INTO Plays values('14:30:00', '16:45:00', 2, 'Up', 2009);
INSERT INTO Plays values('14:00:00', '15:55:00', 1, 'Tangled', 2010);
INSERT INTO Plays values('16:00:00', '17:55:00', 1, 'Tangled', 2010);
INSERT INTO Plays values('18:15:00', '20:20:00', 1, 'Brave', 2012);
INSERT INTO Plays values('17:00:00', '19:05:00', 2, 'Brave', 2012);
INSERT INTO Plays values('14:50:00', '16:46:00', 3, 'Wreck-It Ralph', 2012);
INSERT INTO Plays values('17:10:00', '19:09:00', 3, 'Monsters University', 2013);
INSERT INTO Plays values('11:00:00', '12:57:00', 4, 'Frozen', 2013);
INSERT INTO Plays values('13:15:00', '15:12:00', 4, 'Frozen', 2013);
INSERT INTO Plays values('16:15:00', '18:12:00', 4, 'Frozen', 2013);
INSERT INTO Plays values('19:15:00', '21:12:00', 4, 'Frozen', 2013);
INSERT INTO Plays values('21:30:00', '23:27:00', 4, 'Frozen', 2013);
INSERT INTO Plays values('11:30:00', '14:01:00', 5, 'Star Wars', 2016);
INSERT INTO Plays values('18:00:00', '20:31:00', 5, 'Star Wars', 2016);
INSERT INTO Plays values('14:30:00', '16:54:00', 5, 'Beauty and the Beast', 2017);
INSERT INTO Plays values('20:45:00', '23:09:00', 5, 'Beauty and the Beast', 2017);

-- INSERT Tickets data
-- INSERT INTO Associated_Tickets values('Zootopia', 2016, 1, 1, '1672789028338884', 'seanlennaerts', 9, "15:30:00");
-- INSERT INTO Associated_Tickets values('Zootopia', 2016, 2, 1, '1234809109292392', 'ginnieisawesome304', 9, "15:30:00");
-- INSERT INTO Associated_Tickets values('Frozen', 2013, 3, 1, '1672789028338884', 'seanlennaerts', 9, "15:00:00");
-- INSERT INTO Associated_Tickets values('Frozen', 2013, 4, 1, '8948392921823922', 'jwpark', 9, "15:00:00");
-- INSERT INTO Associated_Tickets values('Frozen', 2013, 5, 1, '9000340918239329', 'prettyprincess', 9, "15:00:00");

-- INSERT Bundle data
-- INSERT INTO Bundle values('Popcorn', 'Star Wars', 2015, "15:00:00");
-- INSERT INTO Bundle values('Pretzels', 'Cinderella', 2015, "15:00:00");
-- INSERT INTO Bundle values('Corndogs', 'Frozen', 2013, "15:00:00");
-- INSERT INTO Bundle values('Poutine', 'Zootopia', 2016, "15:00:00");
-- INSERT INTO Bundle values('Nachos', 'LaLaLand', 2016, "15:00:00");
