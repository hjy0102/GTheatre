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
     HallNumber int,
     Title      char(20),
     RYear      int,
    --  PRIMARY KEY (STime, ETime),
     PRIMARY KEY (HNumber, Title, RYear),
     FOREIGN KEY (HNumber) REFERENCES TheatreHalls (HNumber) ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (Title) REFERENCES Movies (Title) ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (RYear) REFERENCES Movies (RYear) ON DELETE CASCADE ON UPDATE CASCADE
  );

CREATE TABLE Users (
  Login char(20) NOT NULL,
  Password char(20) NOT NULL
  PRIMARY KEY (Login)
)
CREATE TABLE Customers
  (
     CreditCard char(16) NOT NULL,
     Customer_Login      char(20) NOT NULL,
     Customer_Password   char(20) NOT NULL,
     FirstName  char(20),
     PRIMARY KEY (Customer_Login),
     FOREIGN KEY (Customer_Login) REFERENCES Users(Login) ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (Customer_Password) REFERENCES Users(Password) ON DELETE CASCADE ON UPDATE CASCADE
  );

CREATE TABLE Employees
  (
     SIN       int NOT NULL,
     Employee_Login     char(20) NOT NULL,
     Employee_Password  char(20) NOT NULL,
     FirstName char(20),
     UNIQUE (SIN),
     PRIMARY KEY (Employee_Login),
     FOREIGN KEY (Employee_Login) REFERENCES Users(Login) ON DELETE CASCADE ON UPDATE CASCADE,
     FOREIGN KEY (Employee_Password) REFERENCES Users(Password) ON DELETE CASCADE ON UPDATE CASCADE

  );

CREATE TABLE Foods
  (
     FType  char(16) NOT NULL,
     FPrice int NOT NULL,
     PRIMARY KEY (FType)
  );

-- CREATE TABLE Buys
--   (
--      Title char(20) NOT NULL,
--      RYear int NOT NULL,
--      Qty   int NOT NULL,
--      PRIMARY KEY (Title, RYear),
--      FOREIGN KEY (Title) REFERENCES Movie(Title) ON DELETE CASCADE ON UPDATE CASCADE,
--      FOREIGN KEY (RYear) REFERENCES Movie(RYear) ON DELETE CASCADE ON UPDATE CASCADE
--   );

  CREATE TABLE Bundle
    (
      FType char(16) NOT NULL,
      Title char(16) NOT NULL,
      RYear int(4) NOT NULL,
      TicketNo int NOT NULL,
      Primary Key (FType, Title, RYear, TicketNo),
      FOREIGN KEY (FType) REFERENCES Foods(FType) ON DELETE SET NULL ON UPDATE CASCADE,
      FOREIGN KEY (Title) REFERENCES Movies(Title) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (RYear) REFERENCES Movies(RYear) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (TicketNo) REFERENCES Associated_Tickets(TicketNo) ON DELETE CASCADE ON UPDATE CASCADE
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

-- INSERT User data
INSERT INTO Users values('seanlennaerts', 'password123');
INSERT INTO Users values('ginnieisawesome304', '123456789');
INSERT INTO Users values('jwpark', '304bestcourseever');
INSERT INTO Users values('mikeeyoon', 'makecpscgreatagain');
INSERT INTO Users values('prettyprincess', 'MakeMikeGreatAgain2');
INSERT INTO Users values('TABen', 'password');
INSERT INTO Users values('unicorns', 'areReal'),
INSERT INTO Users values('employeeOTMonth', 'all12months');
INSERT INTO Users values('daduck', 'password');
INSERT INTO Users values('TomHanks', 'isdabest');

-- INSERT Customer data
INSERT INTO Customers values(1672789028338884, 'seanlennaerts', 'password123', 'Sean');
INSERT INTO Customers values(1234809109292392, 'ginnieisawesome304', '123456789', 'Ginnie');
INSERT INTO Customers values(8948392921823922, 'jwpark', '304bestcourseever', 'David');
INSERT INTO Customers values(8934712734823923, 'mikeeyoon', 'makecpscgreatagain', 'Mike');
INSERT INTO Customers values(9000340918239329, 'prettyprincess', 'MakeMikeGreatAgain2', 'Mike');

-- INSERT Employee data
INSERT INTO Employees values(871623499, 'TABen', 'password', 'Ben');
INSERT INTO Employees values(923184183, 'unicorns', 'areReal', NULL);
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
INSERT INTO Movies values('Star Wars: The Force Awakens', 2015, 'PG-13', 136);
INSERT INTO Movies values('Cinderella', 2015, 'G', 106);
INSERT INTO Movies values('Frozen', 2013, 'G', 102);
INSERT INTO Movies values('Zootopia', 2016, 'G', 108);
INSERT INTO Movies values('LaLaLand', 2016, 'PG', 128);

-- INSERT Bundle data
INSERT INTO Bundle values('Popcorn', 'Star Wars: The Force Awakens', 2015, 1);
INSERT INTO Bundle values('Pretzels', 'Cinderella', 2015, 2);
INSERT INTO Bundle values('Corndogs', 'Frozen', 2013, 3);
INSERT INTO Bundle values('Poutine', 'Zootopia', 2016, 4);
INSERT INTO Bundle values('Nachos', 'LaLaLand', 2016, 5);

-- INSERT Plays data
INSERT INTO Plays values(TIME 17:00:00, TIME 19:00:00, 1, "Star Wars: The Force Awakens", 2015);
INSERT INTO Plays values(TIME 11:00:00, TIME 13:00:00, 2, "Star Wars: The Force Awakens", 2015);
INSERT INTO Plays values(TIME 9:00:00, TIME 10:30:00, 3, "Cinderella", 2015);
INSERT INTO Plays values(TIME 15:00:00, TIME 16:30:00, 4, "Frozen", 2013);
INSERT INTO Plays values(TIME 15:30:00, TIME 17:00:00, 5, "Zootopia", 2016);
