--SELECT table_name FROM user_tables;
--select * from user_sequences;
--select * from user_constraints;
-- drop old tables:
DROP TABLE accounts CASCADE CONSTRAINTS;
DROP TABLE announcements CASCADE CONSTRAINTS;
DROP TABLE images CASCADE CONSTRAINTS;
DROP TABLE buildings CASCADE CONSTRAINTS;
DROP TABLE saves CASCADE CONSTRAINTS;
PURGE RECYCLEBIN;

--create table for accounts
CREATE TABLE accounts (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  last_name VARCHAR2(32) NOT NULL,
  first_name VARCHAR2(32) NOT NULL,
  phone VARCHAR2(16) NOT NULL UNIQUE,
  email VARCHAR2(64) NOT NULL UNIQUE,
  image BLOB,
  image_type VARCHAR2(32),
  password VARCHAR(255) NOT NULL,
  password_salt VARCHAR(20) NOT NULL,
  business_name VARCHAR2(32),
  created_at DATE,
  updated_at DATE
);
/
--create table for announcements
CREATE TABLE announcements (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  account_id INT NOT NULL,
  title VARCHAR2(128) NOT NULL, 
  type VARCHAR2(32) DEFAULT 'land',
  price INT NOT NULL,
  surface INT NOT NULL,
  address VARCHAR2(255) NOT NULL,
  lat FLOAT NOT NULL,
  lon FLOAT NOT NULL,
  transaction_type VARCHAR2(64) NOT NULL,
  description VARCHAR2(4000),
  created_at DATE,
  updated_at DATE,
  CONSTRAINT fk_announcements_account_id FOREIGN KEY (account_id) REFERENCES accounts(id)
);
/
ALTER TABLE announcements
ADD CONSTRAINT announcements_unique_account_id_title UNIQUE (account_id, title);
/
--create table for saves
CREATE TABLE saves (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  account_id INT NOT NULL,
  announcement_id INT NOT NULL
);
/
ALTER TABLE saves
ADD CONSTRAINT saves_unique_account_id_announcement_id UNIQUE (account_id, announcement_id);
/
--create table for images
CREATE TABLE images (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  announcement_id INT NOT NULL,
  name VARCHAR2(255) NOT NULL,
  type VARCHAR2(32) NOT NULL,
  image BLOB NOT NULL,
  CONSTRAINT fk_images_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
--create table for buildings
CREATE TABLE buildings (
  announcement_id INT PRIMARY KEY,
  floor INT,
  bathrooms INT DEFAULT 1,
  parking_lots INT DEFAULT 1,
  built_in INT,
  ap_type VARCHAR2(32),
  rooms INT,
  basement NUMBER(1),
  CONSTRAINT fk_buildings_announcement_id FOREIGN KEY (announcement_id) REFERENCES announcements(id)
);
/
CREATE OR REPLACE TRIGGER accounts_trigger BEFORE
    INSERT OR UPDATE OR DELETE ON accounts
    FOR EACH ROW
BEGIN
    IF inserting THEN
        :new.created_at := sysdate();
        :new.updated_at := sysdate();
    END IF;

    IF updating THEN
        :new.updated_at := sysdate();
    END IF;
    
    IF deleting THEN
        DELETE FROM announcements WHERE account_id = :OLD.id;
    END IF;
END;
/
CREATE OR REPLACE TRIGGER announcements_trigger BEFORE
    INSERT OR DELETE ON announcements
    FOR EACH ROW
BEGIN
    IF inserting THEN
        :new.created_at := sysdate();
        :new.updated_at := sysdate();
    END IF;
    
    IF deleting THEN
        DELETE FROM saves WHERE announcement_id = :OLD.id;
        DELETE FROM images WHERE announcement_id = :OLD.id;
        DELETE FROM buildings WHERE announcement_id = :OLD.id;
    END IF;
END;
/
--select * from USER_TRIGGERS;
--select * from accounts;
--select * from buildings;
--select * from announcements;
--commit;
--select * from images;
--desc accounts;

--SELECT * FROM announcements a left join buildings b on a.id = b.announcement_id; 