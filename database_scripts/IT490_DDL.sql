CREATE USER 'IT490_DBUSER'@'localhost' IDENTIFIED BY 'IT490';

CREATE DATABASE IT490_MYSTERY_STEAM_THEATER;
GRANT INSERT, UPDATE, SELECT, CREATE on *.* to 'IT490_DBUSER'@'localhost';
USE IT490_MYSTERY_STEAM_THEATER;

CREATE TABLE IT490_USERS (
USER_ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
USER_NAME VARCHAR(255) NOT NULL,
USER_PASS VARCHAR(255) NOT NULL,
USER_EMAIL_ADDR VARCHAR(255) NOT NULL,
USER_REGISTRATION_DTTM DATETIME NOT NULL,
USER_LAST_LOGIN_DTTM DATETIME NOT NULL,
ADMIN_FLAG VARCHAR(1) NOT NULL);
