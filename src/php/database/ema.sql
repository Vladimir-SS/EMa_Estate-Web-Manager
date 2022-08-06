create table accounts
(
    id            serial
        constraint accounts_pk
            primary key,
    first_name    varchar(32)             not null,
    last_name     varchar(32)             not null,
    phone         varchar(16)             not null,
    email         varchar(64)             not null,
    image         bytea,
    image_type    varchar(32),
    password      varchar(255)            not null,
    password_salt varchar(20)             not null,
    business_name varchar(32),
    created_at    timestamp default now() not null,
    updated_at    timestamp default now() not null
);

create unique index accounts_email_uindex
    on accounts (email);

create unique index accounts_phone_uindex
    on accounts (phone);


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