CREATE USER 'IT490_DBUSER'@'localhost' IDENTIFIED BY 'IT490';

CREATE DATABASE IT490_MYSTERY_STEAM_THEATER;
GRANT INSERT, UPDATE, SELECT, CREATE, REFERENCES, DELETE on *.* to 'IT490_DBUSER'@'localhost';
USE IT490_MYSTERY_STEAM_THEATER;

CREATE TABLE IT490_USERS (
USER_ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
USER_NAME VARCHAR(255) NOT NULL UNIQUE,
USER_PASS VARCHAR(255) NOT NULL,
USER_EMAIL_ADDR VARCHAR(255) NOT NULL,
USER_REGISTRATION_DTTM DATETIME NOT NULL,
USER_LAST_LOGIN_DTTM DATETIME NOT NULL,
ADMIN_FLAG VARCHAR(1) NOT NULL);

ALTER TABLE IT490_USERS ADD CONSTRAINT C_DISTINCT_USERNAMES UNIQUE (USER_NAME); 

CREATE TABLE IT490_EVENT_LOG (
EVENT_ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
EVENT_DTTM DATETIME NOT NULL,
EVENT_CODE BIGINT NOT NULL,
EVENT_MESSAGE_TEXT VARCHAR(1000) NOT NULL,
EVENT_SERVER_IP VARCHAR(30));

CREATE TABLE IT490_SERVERS(
SERVER_IP VARCHAR(30)  NOT NULL PRIMARY KEY,
SERVER_DESC VARCHAR(100) NOT NULL,
INSERT_DTTM DATETIME NOT NULL ) ;

INSERT INTO IT490_SERVERS VALUES ('192.168.0.106','DMZ Server',CURRENT_TIMESTAMP());
INSERT INTO IT490_SERVERS VALUES ('192.168.0.105','Message Queue Server',CURRENT_TIMESTAMP());
INSERT INTO IT490_SERVERS VALUES ('192.168.0.107','Front-End Server',CURRENT_TIMESTAMP());
INSERT INTO IT490_SERVERS VALUES ('192.168.0.103','Database Server',CURRENT_TIMESTAMP());

CREATE TABLE IT490_STEAM_USER (
STEAM_ASSOC_ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
USER_ID BIGINT NOT NULL UNIQUE,
STEAM64_ID BIGINT NOT NULL,
INSERT_DTTM DATETIME NOT NULL,
CONSTRAINT FK_USER_STEAM FOREIGN KEY(USER_ID) REFERENCES IT490_USERS(USER_ID) );

                                  
CREATE TABLE IT490_MATCH_HISTORY(
	MATCH_KEY BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	SELF VARCHAR(255) NOT NULL,
	FRIEND VARCHAR(255) NOT NULL,
	LOSSES BIGINT NULL,
	WINS BIGINT NULL,
	INSERT_DTTM DATETIME NOT NULL );
