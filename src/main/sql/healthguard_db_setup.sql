
CREATE DATABASE healthguard;

CREATE TABLE healthguard.healthStats (
	
	statsId INT PRIMARY KEY AUTO_INCREMENT,
	userHeight FLOAT NULL,
	userWeight FLOAT NULL,
	userDateBirth DATE NULL,
	userSex VARCHAR(10) NULL
);

CREATE TABLE healthguard.deviceParams (

    paramsId INT PRIMARY KEY,
    steps INT DEFAULT 0,
	bpm INT DEFAULT 0,
	saturation INT DEFAULT 0,
	sleepHours FLOAT DEFAULT 0,
	sweatAmount FLOAT DEFAULT 0
);

CREATE TABLE healthguard.users (
	
	userId INT PRIMARY KEY AUTO_INCREMENT,
	userName VARCHAR(15) NOT NULL, 
	userEmail VARCHAR(30) NOT NULL,
	userPassword VARCHAR(12) NOT NULL,
	bioStatsId INT DEFAULT NULL,
	FOREIGN KEY (bioStatsId) REFERENCES healthStats(statsId),
	devParamsId INT DEFAULT NULL,
	FOREIGN KEY (devParamsId) REFERENCES 	deviceParams(paramsId)
);

CREATE TABLE healthguard.friendsList (

	joinId INT PRIMARY KEY AUTO_INCREMENT,
	userId INT NOT NULL,
	FOREIGN KEY (userId) REFERENCES users(userId),
	friendId INT NOT NULL,
	FOREIGN KEY (friendId) REFERENCES users(userId)
);
