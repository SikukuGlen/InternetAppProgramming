

CREATE DATABASE IF NOT EXISTS ics_e;
USE ics_e;

CREATE TABLE IF NOT EXISTS users(
    userId bigint(10) not null auto_increment,
    fullname varchar(50) not null default "",
    email varchar(50) not null default "",
    username varchar(50) not null default "",
    password varchar(60) not null default "",
    updated datetime not null default current_timestamp() on update 
    CURRENT_TIMESTAMP(),
    created datetime not null default current_timestamp(),
    genderId tinyint(1) NOT NULL DEFAULT 0,
    roleId tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (userId),
    UNIQUE KEY username(username),
    UNIQUE KEY email(email)
    

    

);