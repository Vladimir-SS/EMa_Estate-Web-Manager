-- SELECT table_name FROM user_tables;
-- drop old tables:
DROP TABLE accounts CASCADE CONSTRAINTS;
DROP TABLE announcements CASCADE CONSTRAINTS;
DROP TABLE images CASCADE CONSTRAINTS;
DROP TABLE apartments CASCADE CONSTRAINTS;
DROP TABLE houses CASCADE CONSTRAINTS;
DROP TABLE offices CASCADE CONSTRAINTS;

--create table for accounts
CREATE TABLE accounts (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  name VARCHAR2(64) NOT NULL,
  phone VARCHAR2(16) NOT NULL,
  email VARCHAR2(64) NOT NULL,
  image BLOB,
  password_hash VARCHAR(255) NOT NULL,
  password_salt VARCHAR(20) NOT NULL,
  business_name VARCHAR2(32),
  
  created_at DATE,
  updated_at DATE
);
/
--create table for announcement/property
CREATE TABLE announcements (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  account_id INT NOT NULL,
  title VARCHAR2(64) NOT NULL,
  address VARCHAR2(128) NOT NULL,
  price INT NOT NULL,
  surface INT NOT NULL,
  land NUMBER(1) NOT NULL,
  building_state VARCHAR2(32) NOT NULL,
  tranzaction_type VARCHAR2(64) NOT NULL, --rent BOOLEAN NOT NULL,
  description VARCHAR2(4000),
  created_at DATE,
  updated_at DATE,
  
  CONSTRAINT fk_announcements_account_id FOREIGN KEY (account_id) REFERENCES accounts(id)
);
/
CREATE OR REPLACE TRIGGER announcements_inc
    BEFORE INSERT ON announcements
    FOR EACH ROW
BEGIN
    :new.created_at := sysdate();
    :new.updated_at := sysdate();
END;
/
--create table for images
CREATE TABLE images (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  announcement_id INT NOT NULL,
  name VARCHAR2(64) NOT NULL,
  image BLOB NOT NULL,
  
  created_at DATE,
  updated_at DATE,

  CONSTRAINT fk_images_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
CREATE OR REPLACE TRIGGER images_inc
    BEFORE INSERT ON images
    FOR EACH ROW
BEGIN
    :new.created_at := sysdate();
    :new.updated_at := sysdate();
END;
/
--create table for apartments
CREATE TABLE apartments (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  announcement_id INT NOT NULL UNIQUE,
  
  floor INT NOT NULL,

  rooms INT NOT NULL,
  type VARCHAR2(32),
  bathrooms INT DEFAULT 1 NOT NULL ,
  construction_year INT NOT NULL,
  elevator NUMBER(1),
  parking_space NUMBER(1),

  CONSTRAINT fk_apartments_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--create table for houses
CREATE TABLE houses (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  announcement_id INT NOT NULL UNIQUE,
  
  number_of_floors INT NOT NULL,

  rooms INT NOT NULL,
  --type VARCHAR2(32),
  bathrooms INT DEFAULT 1 NOT NULL,
  construction_year INT NOT NULL,
  basement NUMBER(1),
  garage NUMBER(1),

  CONSTRAINT fk_houses_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
--create table for offices
CREATE TABLE offices (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  announcement_id INT NOT NULL UNIQUE,
  
  number_of_floors INT NOT NULL,

  rooms INT NOT NULL,
  offices INT NOT NULL,
  bathrooms INT DEFAULT 1 NOT NULL ,
  construction_year INT NOT NULL,
  basement NUMBER(1),
  underground_garage NUMBER(1),

  CONSTRAINT fk_offices_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--select * from USER_TRIGGERS;
--select * from accounts;
--desc accounts;