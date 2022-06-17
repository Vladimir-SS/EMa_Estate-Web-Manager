--SELECT table_name FROM user_tables;
--select * from user_sequences;
--select * from user_constraints;
-- drop old tables:
DROP TABLE accounts CASCADE CONSTRAINTS;
DROP TABLE announcements CASCADE CONSTRAINTS;
DROP TABLE images CASCADE CONSTRAINTS;
DROP TABLE buildings CASCADE CONSTRAINTS;
DROP TABLE saves CASCADE CONSTRAINTS;
purge recyclebin;
-- drop old views:
--DROP VIEW announcements_view
--DROP DIRECTORY AVATARDIR;
--GRANT EXECUTE ON UTL_FILE TO TW;
--GRANT CREATE ANY DIRECTORY TO TW;
--GRANT READ,WRITE ON DIRECTORY AVATARDIR TO TW;
--GRANT DROP ANY DIRECTORY TO TW;
--grant execute on sys.dbms_crypto to TW;
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
--CREATE DIRECTORY AVATARDIR AS 'D:\xampp\htdocs\Proiect\EMa_Estate-Web-Manager';
/
CREATE OR REPLACE TRIGGER accounts_trigger
    BEFORE INSERT OR UPDATE ON accounts
    FOR EACH ROW
DECLARE
  v_dir    VARCHAR2(10) := 'AVATARDIR';
  v_file   VARCHAR2(20) := 'avatar.png';
  v_bfile  BFILE;
  v_blob   BLOB;
  v_dest_offset INTEGER := 1;
  v_src_offset  INTEGER := 1;
BEGIN
--    v_bfile := BFILENAME(v_dir, v_file);
--    DBMS_LOB.fileopen(v_bfile, DBMS_LOB.file_readonly);
--    dbms_lob.createtemporary(v_blob, true);
--    DBMS_LOB.loadblobfromfile (
--    dest_lob    => v_blob,
--    src_bfile   => v_bfile,
--    amount      => DBMS_LOB.lobmaxsize,
--    dest_offset => v_dest_offset,
--    src_offset  => v_src_offset);
--
--    DBMS_LOB.fileclose(v_bfile);
--    :new.image := v_blob;
IF INSERTING THEN
    :new.created_at := sysdate();
    :new.updated_at := sysdate();
    END IF;
IF UPDATING THEN
    :new.updated_at := sysdate();
END IF;
END;
/
--create table for announcements
CREATE TABLE announcements (
  id INT GENERATED ALWAYS as IDENTITY(START with 1 INCREMENT by 1) PRIMARY KEY,
  account_id INT NOT NULL,
  title VARCHAR2(64) NOT NULL, 
  type VARCHAR2(32) DEFAULT 'land',
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
ALTER TABLE announcements
ADD CONSTRAINT announcements_unique_account_id_title UNIQUE (account_id, title);
/
CREATE OR REPLACE TRIGGER announcements_trigger
    BEFORE INSERT ON announcements
    FOR EACH ROW
BEGIN
    :new.created_at := sysdate();
    :new.updated_at := sysdate();
END;
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
--SET SERVEROUTPUT ON;
--DECLARE
--  TYPE varr IS VARRAY(1000) OF varchar2(255);
--  list_last_name varr := varr('Ababei','Acasandrei','Adascalitei','Afanasie','Agafitei','Agape','Aioanei','Alexandrescu','Alexandru','Alexe','Alexii','Amarghioalei','Ambroci','Andonesei','Andrei','Andrian','Andrici','Andronic','Andros','Anghelina','Anita','Antochi','Antonie','Apetrei','Apostol','Arhip','Arhire','Arteni','Arvinte','Asaftei','Asofiei','Aungurenci','Avadanei','Avram','Babei','Baciu','Baetu','Balan','Balica','Banu','Barbieru','Barzu','Bazgan','Bejan','Bejenaru','Belcescu','Belciuganu','Benchea','Bilan','Birsanu','Bivol','Bizu','Boca','Bodnar','Boistean','Borcan','Bordeianu','Botezatu','Bradea','Braescu','Budaca','Bulai','Bulbuc-aioanei','Burlacu','Burloiu','Bursuc','Butacu','Bute','Buza','Calancea','Calinescu','Capusneanu','Caraiman','Carbune','Carp','Catana','Catiru','Catonoiu','Cazacu','Cazamir','Cebere','Cehan','Cernescu','Chelaru','Chelmu','Chelmus','Chibici','Chicos','Chilaboc','Chile','Chiriac','Chirila','Chistol','Chitic','Chmilevski','Cimpoesu','Ciobanu','Ciobotaru','Ciocoiu','Ciofu','Ciornei','Citea','Ciucanu','Clatinici','Clim','Cobuz','Coca','Cojocariu','Cojocaru','Condurache','Corciu','Corduneanu','Corfu','Corneanu','Corodescu','Coseru','Cosnita','Costan','Covatariu','Cozma','Cozmiuc','Craciunas','Crainiceanu','Creanga','Cretu','Cristea','Crucerescu','Cumpata','Curca','Cusmuliuc','Damian','Damoc','Daneliuc','Daniel','Danila','Darie','Dascalescu','Dascalu','Diaconu','Dima','Dimache','Dinu','Dobos','Dochitei','Dochitoiu','Dodan','Dogaru','Domnaru','Dorneanu','Dragan','Dragoman','Dragomir','Dragomirescu','Duceac','Dudau','Durnea','Edu','Eduard','Eusebiu','Fedeles','Ferestraoaru','Filibiu','Filimon','Filip','Florescu','Folvaiter','Frumosu','Frunza','Galatanu','Gavrilita','Gavriliuc','Gavrilovici','Gherase','Gherca','Ghergu','Gherman','Ghibirdic','Giosanu','Gitlan','Giurgila','Glodeanu','Goldan','Gorgan','Grama','Grigore','Grigoriu','Grosu','Grozavu','Gurau','Haba','Harabula','Hardon','Harpa','Herdes','Herscovici','Hociung','Hodoreanu','Hostiuc','Huma','Hutanu','Huzum','Iacob','Iacobuta','Iancu','Ichim','Iftimesei','Ilie','Insuratelu','Ionesei','Ionesi','Ionita','Iordache','Iordache-tiroiu','Iordan','Iosub','Iovu','Irimia','Ivascu','Jecu','Jitariuc','Jitca','Joldescu','Juravle','Larion','Lates','Latu','Lazar','Leleu','Leon','Leonte','Leuciuc','Leustean','Luca','Lucaci','Lucasi','Luncasu','Lungeanu','Lungu','Lupascu','Lupu','Macariu','Macoveschi','Maftei','Maganu','Mangalagiu','Manolache','Manole','Marcu','Marinov','Martinas','Marton','Mataca','Matcovici','Matei','Maties','Matrana','Maxim','Mazareanu','Mazilu','Mazur','Melniciuc-puica','Micu','Mihaela','Mihai','Mihaila','Mihailescu','Mihalachi','Mihalcea','Mihociu','Milut','Minea','Minghel','Minuti','Miron','Mitan','Moisa','Moniry-abyaneh','Morarescu','Morosanu','Moscu','Motrescu','Motroi','Munteanu','Murarasu','Musca','Mutescu','Nastaca','Nechita','Neghina','Negrus','Negruser','Negrutu','Nemtoc','Netedu','Nica','Nicu','Oana','Olanuta','Olarasu','Olariu','Olaru','Onu','Opariuc','Oprea','Ostafe','Otrocol','Palihovici','Pantiru','Pantiruc','Paparuz','Pascaru','Patachi','Patras','Patriche','Perciun','Perju','Petcu','Pila','Pintilie','Piriu','Platon','Plugariu','Podaru','Poenariu','Pojar','Popa','Popescu','Popovici','Poputoaia','Postolache','Predoaia','Prisecaru','Procop','Prodan','Puiu','Purice','Rachieru','Razvan','Reut','Riscanu','Riza','Robu','Roman','Romanescu','Romaniuc','Rosca','Rusu','Samson','Sandu','Sandulache','Sava','Savescu','Schifirnet','Scortanu','Scurtu','Sfarghiu','Silitra','Simiganoschi','Simion','Simionescu','Simionesei','Simon','Sitaru','Sleghel','Sofian','Soficu','Sparhat','Spiridon','Stan','Stavarache','Stefan','Stefanita','Stingaciu','Stiufliuc','Stoian','Stoica','Stoleru','Stolniceanu','Stolnicu','Strainu','Strimtu','Suhani','Tabusca','Talif','Tanasa','Teclici','Teodorescu','Tesu','Tifrea','Timofte','Tincu','Tirpescu','Toader','Tofan','Toma','Toncu','Trifan','Tudosa','Tudose','Tuduri','Tuiu','Turcu','Ulinici','Unghianu','Ungureanu','Ursache','Ursachi','Urse','Ursu','Varlan','Varteniuc','Varvaroi','Vasilache','Vasiliu','Ventaniuc','Vicol','Vidru','Vinatoru','Vlad','Voaides','Vrabie','Vulpescu','Zamosteanu','Zazuleac');
--  list_first_girls varr := varr('Adina','Alexandra','Alina','Ana','Anca','Anda','Andra','Andreea','Andreia','Antonia','Bianca','Camelia','Claudia','Codrina','Cristina','Daniela','Daria','Delia','Denisa','Diana','Ecaterina','Elena','Eleonora','Elisa','Ema','Emanuela','Emma','Gabriela','Georgiana','Ileana','Ilona','Ioana','Iolanda','Irina','Iulia','Iuliana','Larisa','Laura','Loredana','Madalina','Malina','Manuela','Maria','Mihaela','Mirela','Monica','Oana','Paula','Petruta','Raluca','Sabina','Sanziana','Simina','Simona','Stefana','Stefania','Tamara','Teodora','Theodora','Vasilica','Xena');
--  list_first_boys varr := varr('Adrian','Alex','Alexandru','Alin','Andreas','Andrei','Aurelian','Beniamin','Bogdan','Camil','Catalin','Cezar','Ciprian','Claudiu','Codrin','Constantin','Corneliu','Cosmin','Costel','Cristian','Damian','Dan','Daniel','Danut','Darius','Denise','Dimitrie','Dorian','Dorin','Dragos','Dumitru','Eduard','Elvis','Emil','Ervin','Eugen','Eusebiu','Fabian','Filip','Florian','Florin','Gabriel','George','Gheorghe','Giani','Giulio','Iaroslav','Ilie','Ioan','Ion','Ionel','Ionut','Iosif','Irinel','Iulian','Iustin','Laurentiu','Liviu','Lucian','Marian','Marius','Matei','Mihai','Mihail','Nicolae','Nicu','Nicusor','Octavian','Ovidiu','Paul','Petru','Petrut','Radu','Rares','Razvan','Richard','Robert','Roland','Rolland','Romanescu','Sabin','Samuel','Sebastian','Sergiu','Silviu','Stefan','Teodor','Teofil','Theodor','Tudor','Vadim','Valentin','Valeriu','Vasile','Victor','Vlad','Vladimir','Vladut');
--  
--  v_last_name VARCHAR2(32);
--  v_first_name VARCHAR2(32);
--  v_first_name1 VARCHAR2(32);
--  v_first_name2 VARCHAR2(32);
--  v_phone VARCHAR2(16);
--  v_email varchar2(64);
--  v_password VARCHAR(255);
--  v_password_salt VARCHAR(20);
--  v_temp int;
--  v_temp1 int;
--  
--BEGIN
--   DBMS_OUTPUT.PUT_LINE('Insertin random accounts');
--   FOR v_i IN 1..40 LOOP
--      v_last_name := list_last_name(TRUNC(DBMS_RANDOM.VALUE(0,list_last_name.count))+1);
--      IF (DBMS_RANDOM.VALUE(0,2) = 0) THEN      
--         v_first_name1 := list_first_girls(TRUNC(DBMS_RANDOM.VALUE(0,list_first_girls.count))+1);
--         LOOP
--            v_first_name1 := list_first_girls(TRUNC(DBMS_RANDOM.VALUE(0,list_first_girls.count))+1);
--            exit when v_first_name1<>v_first_name2;
--         END LOOP;
--       ELSE
--         v_first_name1 := list_first_boys(TRUNC(DBMS_RANDOM.VALUE(0,list_first_boys.count))+1);
--         LOOP
--            v_first_name2 := list_first_boys(TRUNC(DBMS_RANDOM.VALUE(0,list_first_boys.count))+1);
--            exit when v_first_name1<>v_first_name2;
--         END LOOP;       
--       END IF;
--     
--     IF (DBMS_RANDOM.VALUE(0,100)<60) THEN  
--        IF LENGTH(v_first_name1 || '-' || v_first_name2) <= 20 THEN
--          v_first_name := v_first_name1 || '-' || v_first_name2;
--        END IF;
--        else 
--           v_first_name := v_first_name1;
--      END IF;       
--       
--      LOOP
--         v_phone := 40 || FLOOR(dbms_random.value(100000000, 999999999));
--         select count(*) into v_temp from accounts where phone like 'v_phone';
--         exit when v_temp=0;
--      END LOOP;
--      
--      v_temp :='';
--      v_temp1 := '';
--      v_email := lower(v_last_name ||'.'|| v_first_name1);
--      
--      LOOP
--         select count(*) into v_temp from accounts where email IN (v_email||v_temp1||'@gmail.com',v_email||v_temp1||'@info.ro');
--         IF (v_temp = 0 ) THEN 
--            v_email := v_email||v_temp1;
--            exit;
--         END IF;
--         v_temp1 :=  FLOOR(DBMS_RANDOM.VALUE(0,1000));
--      END LOOP;
--      
--      if (TRUNC(DBMS_RANDOM.VALUE(0,2))=0) then v_email := v_email ||'@gmail.com';
--         else v_email := v_email ||'@info.ro';
--      end if;
--      
--      v_password_salt := DBMS_CRYPTO.RANDOMBYTES(4);
--      
--      v_password := v_first_name || v_password_salt || 'ZION'; -- nume + salt + pepper
--      v_password := dbms_crypto.hash( UTL_I18N.STRING_TO_RAW(v_password, 'AL32UTF8'), 3);
--      --SELECT STANDARD_HASH(v_password) INTO v_password FROM dual;
--                      
--      DBMS_OUTPUT.PUT_LINE ('insert into accounts (last_name, first_name, phone, email, password, password_salt) values('
--      || v_last_name ||', '
--      || v_first_name ||', '
--      || v_phone ||', '
--      || v_email ||', '
--      || v_password ||', '
--      || v_password_salt || ');'); 
--      insert into accounts (last_name, first_name, phone, email, password, password_salt) values( v_last_name, v_first_name, v_phone, v_email, v_password, v_password_salt);
--   END LOOP;
--
--DBMS_OUTPUT.PUT_LINE('Done');
--END;

--select * from USER_TRIGGERS;
--select * from accounts;
--select * from buildings;
--select * from announcements;
--commit;
--select * from images;
--desc accounts;

--SELECT * FROM announcements a left join buildings b on a.id = b.announcement_id; 