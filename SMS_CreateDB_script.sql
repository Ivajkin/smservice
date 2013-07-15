--- At first ,  we need to create and connect to database
--CREATE database SMS_Service ;
--USE SMS_Service ;

-- This table describes sms-receivers
CREATE TABLE receiver (
	UName varchar(50) not null ,
	UPhone bigint unsigned ,
	VKAccount varchar(20) not null ,
	
	primary key ( UPhone )
);

-- Table that uses for specifications of all audiences such as : sex(male\female), age(<20 , 20-30 , ...), salary(...) etc.
CREATE TABLE audience_specification (
	AS_Name varchar(30) unique ,
	AS_Code tinyint unsigned auto_increment ,
	
	primary key ( AS_Code )
);

-- Target audience tables
CREATE TABLE audience (
	AName varchar(50) not null unique ,
	ACode tinyint unsigned auto_increment ,
	AS_Code tinyint unsigned ,
	
	primary key ( ACode ) ,
	foreign key ( AS_Code ) references audience_specification( AS_Code )
);

-- Receiver features 
CREATE TABLE receiver_category (
	UPhone bigint unsigned ,
	ACode tinyint unsigned ,
	
	primary key ( UPhone , ACode ) ,
	foreign key ( UPhone ) references receiver( UPhone ) ,
	foreign key ( ACode ) references audience( ACode )
);

-- Advertiser table
CREATE TABLE Client (
	OrgName varchar(128) unique ,
	EMail varchar(32) unique,
	OrgActivity varchar(8) not null ,
	OrgPhone bigint unsigned not null ,
	ContactPerson varchar(32) ,
	ContactPhone bigint unsigned not null ,
	Address varchar(256) ,
	
	CID integer unsigned auto_increment,
	
	primary key ( CID )
);

-- Templates
CREATE TABLE Template (
	TTotalReceivers bigint unsigned,
	TConfirmedCount bigint unsigned,
	TName varchar(128) not null ,
	TText varchar(256) ,
	CID integer unsigned not null ,--unique,
	TID bigint unsigned auto_increment ,
	
	primary key ( TID ) ,
	foreign key ( CID ) references Client( CID )
);

CREATE TABLE TemplateAudience (
	TID bigint unsigned ,
	ACode tinyint unsigned ,
	
	primary key ( TID , ACode ) ,
	foreign key ( TID ) references Template( TID ) ,
	foreign key ( ACode ) references audience( ACode ) 
);

CREATE TABLE UnconfirmedSMS (
	UPhone bigint unsigned ,
	TID bigint unsigned ,
	ConformCode smallint unsigned ,
	
	primary key ( UPhone , ConformCode ) ,
	foreign key ( UPhone ) references receiver( UPhone ) ,
	foreign key ( TID ) references Template( TID )
);

--########################################################
INSERT INTO audience_specification values('Пол',0);
INSERT INTO audience_specification values('Образование',0);
INSERT INTO audience_specification values('Возраст',0);

INSERT INTO audience values('Мужской',0,1);
INSERT INTO audience values('Женский',0,1);

INSERT INTO audience values('Высшее',0,2);
INSERT INTO audience values('Среднее',0,2);
INSERT INTO audience values('Школьное',0,2);
INSERT INTO audience values('Отсутствует',0,2);

INSERT INTO audience values('<20 лет',0,3);
INSERT INTO audience values('20 - 30 лет',0,3);
INSERT INTO audience values('30 - 40 лет',0,3);
INSERT INTO audience values('>40 лет',0,3);

