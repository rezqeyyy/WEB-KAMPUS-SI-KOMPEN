CREATE TABLE login_attempts (
    nim VARCHAR2(20) PRIMARY KEY,
    attempts NUMBER DEFAULT 0,
    last_attempt TIMESTAMP
);

CREATE TABLE failed_jobs (
    id NUMBER(20) NOT NULL,
    uuid VARCHAR2(255) NOT NULL,
    connection CLOB NOT NULL,
    queue CLOB NOT NULL,
    payload CLOB NOT NULL,
    exception CLOB NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

ALTER TABLE failed_jobs
ADD CONSTRAINT pk_failed_jobs PRIMARY KEY (id);

ALTER TABLE failed_jobs
ADD CONSTRAINT uq_failed_jobs UNIQUE (uuid);

CREATE TABLE tbl_mahasiswa (
    id_mhs NUMBER(20),
    nim VARCHAR2(50),
    nama VARCHAR2(50),
    email VARCHAR2(50),
    prodi VARCHAR2(50),
    kelas VARCHAR2(50),
    semester VARCHAR2(50),
    notelp VARCHAR2(50),
    password VARCHAR2(255),
    edit_password VARCHAR2(30) DEFAULT '0',
    remember_token VARCHAR2(64),
    reset_token VARCHAR2(64),
    reset_expires TIMESTAMP,
    user_role VARCHAR2(50) DEFAULT 'Mahasiswa' NOT NULL,
    jumlah_terlambat VARCHAR2(50),
    jumlah_alfa VARCHAR2(50),
    total VARCHAR2(255),
    user_create VARCHAR2(255),
    user_update VARCHAR2(255),
    user_uid CHAR(40),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

ALTER TABLE tbl_mahasiswa
ADD CONSTRAINT pk_id_mhs PRIMARY KEY (id_mhs);

ALTER TABLE tbl_mahasiswa
ADD CONSTRAINT uq_nim_mhs UNIQUE (nim);

CREATE SEQUENCE seq_id_mhs
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE users (
    id NUMBER DEFAULT seq_id_user.NEXTVAL PRIMARY KEY,
    name VARCHAR2(255) NOT NULL,
    email VARCHAR2(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP,
    password VARCHAR2(255) NOT NULL,
    remember_token VARCHAR2(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_users_email CHECK (REGEXP_LIKE(email, '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'))
);


CREATE SEQUENCE seq_id_user
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_USER (
    ID NUMBER DEFAULT seq_id_user.NEXTVAL PRIMARY KEY,
    NAMA_USER VARCHAR2(100) NOT NULL,
    NIP VARCHAR2(20) NOT NULL UNIQUE,
    EMAIL VARCHAR2(100) NOT NULL UNIQUE,
    ROLE VARCHAR2(50) NOT NULL,
    PASSWORD VARCHAR2(255) NOT NULL,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UPDATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    STATUS VARCHAR2(20) DEFAULT 'ACTIVE' CHECK (STATUS IN ('ACTIVE', 'INACTIVE', 'SUSPENDED')),
    LAST_LOGIN TIMESTAMP,
    CONSTRAINT email_format CHECK (REGEXP_LIKE(EMAIL, '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$')),
    CONSTRAINT valid_role CHECK (ROLE IN ('ADMIN', 'PLP', 'KALAB', 'PENGAWAS'))
);

ALTER TABLE tbl_USER
ADD TTD VARCHAR2(255);

CREATE TABLE tbl_bebas_kompen (
    id_bebas_kompen NUMBER(20) NOT NULL,
    id_pengajuan NUMBER(20),
    kode_user VARCHAR2(50),
    nama_user VARCHAR2(50),
    kelas VARCHAR2(50),
    prodi VARCHAR2(50),
    semester VARCHAR2(50),
    jumlah_terlambat VARCHAR2(50),	
	jumlah_alfa	VARCHAR2(50),
	total VARCHAR2(50),	
	sisa VARCHAR2(50),
	dosen_pembimbing_akademik VARCHAR2(50),
	status_approval1 VARCHAR2(50),	
	approval1_by VARCHAR2(50),	
	status_approval2 VARCHAR2(50),	
	approval2_by VARCHAR2(50),	
	status_approval3 VARCHAR2(50),	
	approval3_by VARCHAR2(50),		
	user_uid CHAR(36),
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

ALTER TABLE tbl_bebas_kompen
ADD CONSTRAINT pk_id_bk PRIMARY KEY (id_bebas_kompen);

CREATE SEQUENCE seq_id_bk
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_kelas (
    id_kelas NUMBER(20) NOT NULL,
    kelas VARCHAR2(50),
    user_uid CHAR(36),
    created_at TIMESTAMP,
	updated_at TIMESTAMP
);

ALTER TABLE tbl_kelas
ADD CONSTRAINT pk_id_kelas PRIMARY KEY (id_kelas);

ALTER TABLE tbl_kelas
ADD CONSTRAINT uq_kelas UNIQUE (kelas);

CREATE SEQUENCE seq_id_kelas
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_pekerjaan(
    id_pekerjaan NUMBER(20) NOT NULL,	
	kode_pekerjaan varchar2(50),	
	nama_pekerjaan varchar2(255),		
	jam_pekerjaan varchar2(50),	
	batas_pekerja NUMBER(11),			
	id_penanggung_jawab	varchar2(50),	
	penanggung_jawab varchar2(50),		
	user_create	varchar2(255),		
	user_update	varchar2(255),		
	user_uid char(36) NOT NULL,	
	created_at timestamp,				
	updated_at timestamp
);

ALTER TABLE tbl_pekerjaan
ADD CONSTRAINT pk_id_pekerjaan PRIMARY KEY (id_pekerjaan);

ALTER TABLE tbl_pekerjaan
ADD CONSTRAINT uq_kode_pekerjaan UNIQUE (kode_pekerjaan);

ALTER TABLE tbl_pekerjaan
MODIFY id_penanggung_jawab NUMBER(20);

ALTER TABLE tbl_pekerjaan MODIFY user_uid NULL;


ALTER TABLE tbl_pekerjaan
ADD CONSTRAINT fk_penanggung_jawab
FOREIGN KEY (id_penanggung_jawab)
REFERENCES tbl_user (id)
ON DELETE SET NULL;

CREATE SEQUENCE seq_id_pekerjaan
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_pengajuan(
    id_pengajuan NUMBER(20) NOT NULL,		
	kode_kegiatan varchar2(50),	
	kode_user varchar2(50),
	nama_user varchar2(50),	
	kelas varchar2(50),
	prodi varchar2(50),		
	semester varchar2(50),		
	jumlah_terlambat varchar2(50),	
	jumlah_alfa	varchar2(50),		
	total varchar2(50),		
	sisa varchar2(50),		
	keterangan CLOB,	
	id_penanggung_jawab	varchar2(50),		
	penanggung_jawab varchar2(50),		
	tanggal_pengajuan DATE,		
	status_approval1 varchar2(50),		
	keterangan_approval1 varchar2(255),		
	bukti_tambahan varchar2(50),		
	approval1_by varchar2(50),	
	status_approval2 varchar2(50),	
	keterangan_approval2 varchar2(255),	
	approval2_by varchar2(50),		
	status_approval3 varchar2(50),		
	keterangan_approval3 varchar2(255),		
	approval3_by varchar2(50),	
	status	varchar2(50),		
	created_at	timestamp,			
	updated_at	timestamp,				
	user_create	varchar(255),		
	user_update	varchar(255),	
	user_uid char(36),	
	perkiraan_sisa_jam varchar2(30)
);

ALTER TABLE tbl_pengajuan
ADD CONSTRAINT pk_id_pengajuan PRIMARY KEY (id_pengajuan);

ALTER TABLE tbl_pengajuan
ADD CONSTRAINT uq_kode_pengajuan UNIQUE (kode_kegiatan);

CREATE SEQUENCE seq_id_pengajuan
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_pengajuan_detail (
    id_pengajuan_detail NUMBER(20) NOT NULL,
    kode_kegiatan VARCHAR2(50),
    kode_pekerjaan VARCHAR2(50),
    nama_pekerjaan VARCHAR2(50),
    jam_pekerjaan VARCHAR2(50),
    batas_pekerja VARCHAR2(50),
    before_pekerjaan VARCHAR2(50),
    after_pekerjaan VARCHAR2(50),
    bukti_tambahan VARCHAR2(50),
    user_create VARCHAR2(255),
    user_update VARCHAR2(255),
    user_uid char(36),
    created_at timestamp,			
	updated_at timestamp
);

ALTER TABLE tbl_pengajuan_detail
ADD CONSTRAINT pk_id_pengdet PRIMARY KEY (id_pengajuan_detail);

ALTER TABLE tbl_pengajuan_detail
ADD CONSTRAINT uq_kode_kegiatan UNIQUE (kode_kegiatan);

CREATE SEQUENCE seq_id_peng_det
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

CREATE TABLE tbl_prodi (
    id_prodi NUMBER(20) NOT NULL,
    prodi VARCHAR2(50),
    user_uid CHAR(36) NOT NULL,
    created_at timestamp,			
	updated_at timestamp
);

ALTER TABLE tbl_prodi
ADD CONSTRAINT pk_id_prodi PRIMARY KEY (id_prodi);

ALTER TABLE tbl_prodi
ADD CONSTRAINT uq_prodi UNIQUE (prodi);

CREATE SEQUENCE seq_id_prodi
START WITH 1
INCREMENT BY 1
NOMAXVALUE
NOCACHE;

ALTER TABLE tbl_pekerjaan ADD status VARCHAR2(20) DEFAULT 'Active';

ALTER TABLE tbl_pengajuan_detail DROP CONSTRAINT uq_kode_kegiatan;
ALTER TABLE tbl_pengajuan_detail ADD CONSTRAINT pk_pengajuan_detail UNIQUE (kode_kegiatan, kode_pekerjaan);

ALTER TABLE tbl_pengajuan_detail 
ADD CONSTRAINT fk_pengdet_pengajuan 
FOREIGN KEY (kode_kegiatan) REFERENCES tbl_pengajuan(kode_kegiatan);

ALTER TABLE tbl_pengajuan_detail 
ADD CONSTRAINT fk_pengdet_pekerjaan 
FOREIGN KEY (kode_pekerjaan) REFERENCES tbl_pekerjaan(kode_pekerjaan);

CREATE INDEX idx_pengajuan_status ON tbl_pengajuan(status_approval1, status_approval2, status_approval3);
CREATE INDEX idx_pekerjaan_status ON tbl_pekerjaan(status);

ALTER TABLE tbl_pengajuan_detail DROP CONSTRAINT PK_ID_PENGDET;
ALTER TABLE tbl_pengajuan_detail DROP CONSTRAINT PK_PENGAJUAN_DETAIL;

CREATE INDEX idx_pengdet_kode ON tbl_pengajuan_detail(kode_kegiatan, kode_pekerjaan);

ALTER TABLE tbl_pekerjaan ADD DETAIL_PEKERJAAN LONG;

CREATE OR REPLACE TRIGGER TRG_ID_PEKERJAAN
BEFORE INSERT ON tbl_pekerjaan
FOR EACH ROW
BEGIN
    IF :NEW.id_pekerjaan IS NULL THEN
        SELECT SEQ_ID_PEKERJAAN.NEXTVAL INTO :NEW.id_pekerjaan FROM DUAL;
    END IF;
END;

CREATE OR REPLACE TRIGGER system.TRG_ID_PEKERJAAN
BEFORE INSERT ON TBL_PEKERJAAN
FOR EACH ROW
BEGIN
    IF :NEW.ID_PEKERJAAN IS NULL THEN
        SELECT seq_id_pekerjaan.NEXTVAL
        INTO :NEW.ID_PEKERJAAN
        FROM DUAL;
    END IF;
END;


-- Create the SETUP_BERTUGAS table
CREATE TABLE SETUP_BERTUGAS (
    ID NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    NIP VARCHAR2(20) NOT NULL,
    NAMA_USER VARCHAR2(100) NOT NULL,
    ROLE VARCHAR2(50) NOT NULL,
    TANGGAL_BERTUGAS TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_nip FOREIGN KEY (NIP) REFERENCES TBL_USER(NIP)
);

-- Insert data from TBL_USER into SETUP_BERTUGAS
INSERT INTO SETUP_BERTUGAS (NIP, NAMA_USER, ROLE, TANGGAL_BERTUGAS)
SELECT 
    NIP,
    NAMA_USER,
    ROLE,
    CURRENT_TIMESTAMP
FROM TBL_USER
WHERE STATUS = 'ACTIVE';



-- Insert new record
CREATE OR REPLACE PROCEDURE INSERT_SETUP_BERTUGAS (
    p_nip IN VARCHAR2,
    p_tanggal_bertugas IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO SETUP_BERTUGAS (NIP, NAMA_USER, ROLE, TANGGAL_BERTUGAS)
    SELECT 
        NIP,
        NAMA_USER,
        ROLE,
        p_tanggal_bertugas
    FROM TBL_USER
    WHERE NIP = p_nip;
    COMMIT;
END;


-- Delete record
CREATE OR REPLACE PROCEDURE DELETE_SETUP_BERTUGAS (
    p_id IN NUMBER
)
IS
BEGIN
    DELETE FROM SETUP_BERTUGAS WHERE ID = p_id;
    COMMIT;
END;


-- Delete all records
CREATE OR REPLACE PROCEDURE DELETE_ALL_SETUP_BERTUGAS
IS
BEGIN
    DELETE FROM SETUP_BERTUGAS;
    COMMIT;
END;


-- Update record
CREATE OR REPLACE PROCEDURE UPDATE_SETUP_BERTUGAS (
    p_id IN NUMBER,
    p_tanggal_bertugas IN TIMESTAMP
)
IS
BEGIN
    UPDATE SETUP_BERTUGAS 
    SET TANGGAL_BERTUGAS = p_tanggal_bertugas
    WHERE ID = p_id;
    COMMIT;
END;



------------------------------------------------
-- Drop foreign key constraints first
ALTER TABLE tbl_pekerjaan DROP CONSTRAINT fk_penanggung_jawab;
ALTER TABLE tbl_pengajuan_detail DROP CONSTRAINT fk_pengdet_pengajuan;
ALTER TABLE tbl_pengajuan_detail DROP CONSTRAINT fk_pengdet_pekerjaan;

-- Drop any indexes
DROP INDEX idx_pengajuan_status;
DROP INDEX idx_pekerjaan_status;
DROP INDEX idx_pengdet_kode;

-- Drop the tables
DROP TABLE tbl_pengajuan_detail CASCADE CONSTRAINTS;
DROP TABLE tbl_pengajuan CASCADE CONSTRAINTS;
DROP TABLE tbl_pekerjaan CASCADE CONSTRAINTS;
DROP TABLE tbl_kelas CASCADE CONSTRAINTS;
DROP TABLE tbl_bebas_kompen CASCADE CONSTRAINTS;
DROP TABLE tbl_mahasiswa CASCADE CONSTRAINTS;
DROP TABLE tbl_USER CASCADE CONSTRAINTS;
DROP TABLE tbl_prodi CASCADE CONSTRAINTS;
DROP TABLE failed_jobs CASCADE CONSTRAINTS;
DROP TABLE login_attempts CASCADE CONSTRAINTS;

-- Drop the sequences
DROP SEQUENCE seq_id_bk;
DROP SEQUENCE seq_id_kelas;
DROP SEQUENCE seq_id_mhs;
DROP SEQUENCE seq_id_user;
DROP SEQUENCE seq_id_pekerjaan;
DROP SEQUENCE seq_id_pengajuan;
DROP SEQUENCE seq_id_peng_det;
DROP SEQUENCE seq_id_prodi;


