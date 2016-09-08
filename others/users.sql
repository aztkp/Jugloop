create database jugloop;
grant all on jugloop.* to butiyo@localhost identified by 'ohara';
use jugloop;

create table users (
  id int not null auto_increment primary key,
  tw_user_id bigint unique,
  tw_screen_name varchar(15),
  tw_access_token varchar(255),
  tw_access_token_secret varchar(255),
  tw_status tinyint default 0,
  main_tool tinyint default 1,
  circle varchar(255),
  goal varchar(64),
  introduction text,
  created datetime,
  modified datetime
);

create table posts (
  id int not null auto_increment primary key,
  user_id int not null,
  tool tinyint not null,
  kaishi datetime not null,
  owari datetime not null,
  jikan time not null,
  hitokoto varchar(255),
  created datetime,
  modified datetime
);
