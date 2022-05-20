-- SELECT table_name FROM user_tables;
-- drop old tables:
DROP TABLE accounts CASCADE CONSTRAINTS;
DROP TABLE announcements CASCADE CONSTRAINTS;
DROP TABLE images CASCADE CONSTRAINTS;
DROP TABLE buildings CASCADE CONSTRAINTS;
DROP TABLE lands CASCADE CONSTRAINTS;
DROP TABLE residentials CASCADE CONSTRAINTS;
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
  price INT NOT NULL,
  surface INT NOT NULL,
  address VARCHAR2(128) NOT NULL,
  transaction_type VARCHAR2(64) NOT NULL,
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
  image BLOB NOT NULL,

  CONSTRAINT fk_images_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--create table for buildings
CREATE TABLE buildings (
  announcement_id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  floor INT,
  bathrooms INT DEFAULT 1,
  parking_lots INT DEFAULT 1,
  built_on DATE,

  CONSTRAINT fk_buildings_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--create table for lands
CREATE TABLE lands (
  announcement_id INT PRIMARY KEY,

  CONSTRAINT fk_lands_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--create table for residentials
CREATE TABLE residentials (
  building_id INT PRIMARY KEY,
  rooms INT NOT NULL,

  CONSTRAINT fk_residentials_building_id FOREIGN KEY (building_id) REFERENCES buildings(announcement_id)
);
/
--create table for offices
CREATE TABLE offices (
  building_id INT PRIMARY KEY,

  CONSTRAINT fk_offices_building_id FOREIGN KEY (building_id) REFERENCES buildings(announcement_id)
);
/
--create table for apartments
CREATE TABLE apartments (
  residential_id INT PRIMARY KEY,
  type VARCHAR2(32),

  CONSTRAINT fk_apartments_residential_id FOREIGN KEY (residential_id) REFERENCES residentials(building_id)
);
/
--create table for houses
CREATE TABLE houses (
  residential_id INT PRIMARY KEY,
  basement NUMBER(1),

  CONSTRAINT fk_houses_residential_id FOREIGN KEY (residential_id) REFERENCES residentials(building_id)
);
/
--select * from USER_TRIGGERS;
--select * from accounts;
--desc accounts;