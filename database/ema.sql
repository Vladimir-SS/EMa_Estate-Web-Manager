
-- delete table account from previous database
DROP TABLE account CASCADE CONSTRAINTS;
/

--create table for account
CREATE TABLE account (
  id INT NOT NULL PRIMARY KEY,
  name VARCHAR2(64) NOT NULL,
  phone VARCHAR2(16) NOT NULL,
  email VARCHAR2(64) NOT NULL,
  image BLOB NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  bussines_name VARCHAR2(32),
  
  created_at DATE,
  updated_at DATE
);
/

DROP SEQUENCE PADB.account_SEQ;
/

CREATE SEQUENCE account_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER account_inc
    BEFORE INSERT ON account
    FOR EACH ROW
BEGIN
    SELECT account_seq.nextval
    INTO :new.id
    FROM dual;
END;
/






-- delete table property from previous database
DROP TABLE property CASCADE CONSTRAINTS;
/

--create table for announcement/property
CREATE TABLE property (
  id INT NOT NULL PRIMARY KEY,
  title VARCHAR2(64) NOT NULL,
  address VARCHAR2(128) NOT NULL,
  price INT NOT NULL,
  surface INT NOT NULL,
  land BOOLEAN NOT NULL,
  building_state VARCHAR2(32) NOT NULL,
  tranzaction_type VARCHAR2(64) NOT NULL, --rent BOOLEAN NOT NULL,
  description VARCHAR2(5000),
  created_at DATE,
  updated_at DATE
);
/

DROP SEQUENCE PADB.property_SEQ;
/

CREATE SEQUENCE property_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER property_inc
    BEFORE INSERT ON property
    FOR EACH ROW
BEGIN
    SELECT property_seq.nextval
    INTO :new.id
    FROM dual;
END;
/



-- delete table images from previous database
DROP TABLE images CASCADE CONSTRAINTS;
/

--create table for images
CREATE TABLE images (
  id INT NOT NULL PRIMARY KEY,
  property_id INT NOT NULL
  name VARCHAR2(64) NOT NULL,
  image BLOB NOT NULL,
  CONSTRAINT fk_images_property_id FOREIGN KEY (property_id) REFERENCES property(id),
  created_at DATE,
  updated_at DATE
);
/

DROP SEQUENCE PADB.images_SEQ;
/

CREATE SEQUENCE images_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER images_inc
    BEFORE INSERT ON images
    FOR EACH ROW
BEGIN
    SELECT images_seq.nextval
    INTO :new.id
    FROM dual;
END;
/




-- delete table apartments from previous database
DROP TABLE apartments CASCADE CONSTRAINTS;
/

--create table for apartments
CREATE TABLE apartments (
  id INT NOT NULL PRIMARY KEY,
  property_id INT NOT NULL,
  
  floor INT NOT NULL,

  rooms INT NOT NULL,
  type VARCHAR2(32),
  bathrooms INT NOT NULL,
  construction_year INT NOT NULL,
  elevator BOOLEAN,
  parking_space BOOLEAN,

  CONSTRAINT fk_apartments_property_id FOREIGN KEY (property_id) REFERENCES property(id)
);
/

DROP SEQUENCE PADB.apartments_SEQ;
/

CREATE SEQUENCE apartments_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER apartments_inc
    BEFORE INSERT ON apartments
    FOR EACH ROW
BEGIN
    SELECT apartments_seq.nextval
    INTO :new.id
    FROM dual;
END;
/

-- delete table houses from previous database
DROP TABLE houses CASCADE CONSTRAINTS;
/

--create table for houses
CREATE TABLE houses (
  id INT NOT NULL PRIMARY KEY,
  property_id INT NOT NULL,
  
  number_of_floors INT NOT NULL,

  rooms INT NOT NULL,
  --type VARCHAR2(32),
  bathrooms INT NOT NULL,
  construction_year INT NOT NULL,
  basement BOOLEAN,
  garage BOOLEAN,

  CONSTRAINT fk_houses_property_id FOREIGN KEY (property_id) REFERENCES property(id)
);
/

DROP SEQUENCE PADB.houses_SEQ;
/

CREATE SEQUENCE houses_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER houses_inc
    BEFORE INSERT ON houses
    FOR EACH ROW
BEGIN
    SELECT houses_seq.nextval
    INTO :new.id
    FROM dual;
END;
/

-- delete table offices from previous database
DROP TABLE offices CASCADE CONSTRAINTS;
/

--create table for offices
CREATE TABLE offices (
  id INT NOT NULL PRIMARY KEY,
  property_id INT NOT NULL,
  
  number_of_floors INT NOT NULL,

  rooms INT NOT NULL,
  offices INT NOT NULL,
  bathrooms INT NOT NULL,
  construction_year INT NOT NULL,
  basement BOOLEAN,
  underground_garage BOOLEAN,

  CONSTRAINT fk_offices_property_id FOREIGN KEY (property_id) REFERENCES property(id)
);
/

DROP SEQUENCE PADB.offices_SEQ;
/

CREATE SEQUENCE offices_seq START WITH 1;
/

CREATE OR REPLACE TRIGGER offices_inc
    BEFORE INSERT ON offices
    FOR EACH ROW
BEGIN
    SELECT offices_seq.nextval
    INTO :new.id
    FROM dual;
END;
/
