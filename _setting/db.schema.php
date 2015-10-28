<?php
if(!defined('__KIMS__')) exit;

//블로그리스트
$_tmp = db_query( "select count(*) from ".$table[$module.'list'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'list']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
gid			INT				DEFAULT '0'		NOT NULL,
blogtype	TINYINT			DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
members		TEXT			DEFAULT ''		NOT NULL,
id			VARCHAR(50)		DEFAULT ''		NOT NULL,
name		VARCHAR(200)	DEFAULT ''		NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
d_last		VARCHAR(14)		DEFAULT ''		NOT NULL,
num_w		INT				DEFAULT '0'		NOT NULL,
num_c		INT				DEFAULT '0'		NOT NULL,
num_o		INT				DEFAULT '0'		NOT NULL,
num_m		INT				DEFAULT '0'		NOT NULL,
KEY gid(gid),
KEY blogtype(blogtype),
KEY mbruid(mbruid),
KEY id(id),
KEY num_w(num_w),
KEY num_c(num_c),
KEY num_o(num_o),
KEY num_m(num_m)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'list'],$DB_CONNECT); 
}

//블로그카테고리
$_tmp = db_query( "select count(*) from ".$table[$module.'category'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'category']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
gid			INT				DEFAULT '0'		NOT NULL,
blog		INT				DEFAULT '0'		NOT NULL,
metaurl		VARCHAR(200)	DEFAULT ''		NOT NULL,
metause		TINYINT			DEFAULT '0'		NOT NULL,
isson		TINYINT			DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
depth		TINYINT			DEFAULT '0'		NOT NULL,
id			VARCHAR(50)		DEFAULT ''		NOT NULL,
name		VARCHAR(50)		DEFAULT ''		NOT NULL,
mobile		TINYINT			DEFAULT '0'		NOT NULL,
hidden		TINYINT			DEFAULT '0'		NOT NULL,
num_open	INT				DEFAULT '0'		NOT NULL,
num_reserve	INT				DEFAULT '0'		NOT NULL,
vtype		VARCHAR(10)		DEFAULT ''		NOT NULL,
recnum		TINYINT			DEFAULT '0'		NOT NULL,
vopen		TINYINT			DEFAULT '0'		NOT NULL,
d_last		VARCHAR(14)		DEFAULT ''		NOT NULL,
KEY gid(gid),
KEY blog(blog),
KEY parent(parent),
KEY depth(depth),
KEY id(id),
KEY mobile(mobile),
KEY hidden(hidden)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'category'],$DB_CONNECT); 
}

//블로그카테고리인덱스
$_tmp = db_query( "select count(*) from ".$table[$module.'catidx'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'catidx']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
blog		INT				DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
category	INT				DEFAULT '0'		NOT NULL,
KEY blog(blog),
KEY parent(parent),
KEY category(category)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'catidx'],$DB_CONNECT); 
}
//블로그데이터
$_tmp = db_query( "select count(*) from ".$table[$module.'data'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'data']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
blog		INT				DEFAULT '0'		NOT NULL,
gid			INT				DEFAULT '0'		NOT NULL,
isreserve	TINYINT			DEFAULT '0'		NOT NULL,
isphoto		TINYINT			DEFAULT '0'		NOT NULL,
isvod		TINYINT			DEFAULT '0'		NOT NULL,
cutcomment	TINYINT			DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
tag			VARCHAR(200)	DEFAULT ''		NOT NULL,
subject		VARCHAR(200)	DEFAULT ''		NOT NULL,
review		TEXT			DEFAULT ''		NOT NULL,
content		MEDIUMTEXT		NOT NULL,
hit			INT				DEFAULT '0'		NOT NULL,
comment		INT				DEFAULT '0'		NOT NULL,
oneline		INT				DEFAULT '0'		NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
d_modify	VARCHAR(14)		DEFAULT ''		NOT NULL,
d_comment	VARCHAR(14)		DEFAULT ''		NOT NULL,
sns			VARCHAR(100)	DEFAULT ''		NOT NULL,
upload		TEXT			NOT NULL,
log			TEXT			NOT NULL,
KEY blog(blog),
KEY gid(gid),
KEY isreserve(isreserve),
KEY isphoto(isphoto),
KEY isvod(isvod),
KEY mbruid(mbruid),
KEY tag(tag),
KEY subject(subject),
KEY d_regis(d_regis),
KEY d_comment(d_comment)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'data'],$DB_CONNECT); 
}

//블로그일별수량
$_tmp = db_query( "select count(*) from ".$table[$module.'day'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'day']." (
date		CHAR(8)			DEFAULT ''		NOT NULL,
blog		INT				DEFAULT '0'		NOT NULL,
num			INT				DEFAULT '0'		NOT NULL,
KEY date(date),
KEY blog(blog)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'day'],$DB_CONNECT); 
}

//첨부파일데이터
$_tmp = db_query( "select count(*) from ".$table[$module.'upload'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'upload']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
gid			INT				DEFAULT '0'		NOT NULL,
hidden		TINYINT			DEFAULT '0'		NOT NULL,
tmpcode		VARCHAR(20)		DEFAULT ''		NOT NULL,
blog		INT				DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
type		TINYINT			DEFAULT '0'		NOT NULL,
ext			VARCHAR(4)		DEFAULT '0'		NOT NULL,
fserver		TINYINT			DEFAULT '0'		NOT NULL,
url			VARCHAR(150)	DEFAULT ''		NOT NULL,
folder		VARCHAR(30)		DEFAULT ''		NOT NULL,
name		VARCHAR(250)	DEFAULT ''		NOT NULL,
tmpname		VARCHAR(100)	DEFAULT ''		NOT NULL,
thumbname	VARCHAR(100)	DEFAULT ''		NOT NULL,
size		INT				DEFAULT '0'		NOT NULL,
width		INT				DEFAULT '0'		NOT NULL,
height		INT				DEFAULT '0'		NOT NULL,
caption		TEXT			NOT NULL,
down		INT				DEFAULT '0'		NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
d_update	VARCHAR(14)		DEFAULT ''		NOT NULL,
KEY gid(gid),
KEY tmpcode(tmpcode),
KEY blog(blog),
KEY parent(parent),
KEY mbruid(mbruid),
KEY type(type),
KEY ext(ext),
KEY name(name),
KEY d_regis(d_regis)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'upload'],$DB_CONNECT); 
}


//댓글데이터
$_tmp = db_query( "select count(*) from ".$table[$module.'comment'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'comment']." (
uid			INT				PRIMARY KEY		NOT NULL,
blog		INT				DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
notice		TINYINT			DEFAULT '0'		NOT NULL,
hidden		TINYINT			DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
name		VARCHAR(30)		DEFAULT ''		NOT NULL,
url			VARCHAR(100)	DEFAULT ''		NOT NULL,
pw			VARCHAR(50)		DEFAULT ''		NOT NULL,
content		TEXT			NOT NULL,
oneline		INT				DEFAULT '0'		NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
d_modify	VARCHAR(14)		DEFAULT ''		NOT NULL,
d_oneline	VARCHAR(14)		DEFAULT ''		NOT NULL,
ip			VARCHAR(25)	 	DEFAULT ''		NOT NULL,
agent	 	VARCHAR(150)	DEFAULT ''		NOT NULL,
sns			VARCHAR(100)	DEFAULT ''		NOT NULL,
KEY blog(blog),
KEY parent(parent),
KEY notice(notice),
KEY hidden(hidden),
KEY mbruid(mbruid),
KEY d_regis(d_regis)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'comment'],$DB_CONNECT); 
}

//한줄의견데이터
$_tmp = db_query( "select count(*) from ".$table[$module.'oneline'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'oneline']." (
uid			INT				PRIMARY KEY		NOT NULL,
blog		INT				DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
hidden		TINYINT			DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
content		TEXT			NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
d_modify	VARCHAR(14)		DEFAULT ''		NOT NULL,
ip			VARCHAR(25)	 	DEFAULT ''		NOT NULL,
agent	 	VARCHAR(150)	DEFAULT ''		NOT NULL,
KEY blog(blog),
KEY parent(parent),
KEY hidden(hidden),
KEY mbruid(mbruid),
KEY d_regis(d_regis)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'oneline'],$DB_CONNECT); 
}

//구독회원
$_tmp = db_query( "select count(*) from ".$table[$module.'members'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'members']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
blog		INT				DEFAULT '0'		NOT NULL,
mbruid		INT				DEFAULT '0'		NOT NULL,
level		TINYINT			DEFAULT '0'		NOT NULL,
num_w		INT				DEFAULT '0'		NOT NULL,
num_c		INT				DEFAULT '0'		NOT NULL,
num_o		INT				DEFAULT '0'		NOT NULL,
d_regis		VARCHAR(14)		DEFAULT ''		NOT NULL,
KEY blog(blog),
KEY mbruid(mbruid),
KEY level(level),
KEY d_regis(d_regis)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'members'],$DB_CONNECT); 
}

//SEO테이블
$_tmp = db_query( "select count(*) from ".$table[$module.'seo'], $DB_CONNECT );
if ( !$_tmp ) {
$_tmp = ("

CREATE TABLE ".$table[$module.'seo']." (
uid			INT				PRIMARY KEY		NOT NULL AUTO_INCREMENT,
blog		INT				DEFAULT '0'		NOT NULL,
parent		INT				DEFAULT '0'		NOT NULL,
subject		VARCHAR(200)	DEFAULT ''		NOT NULL,
title		VARCHAR(200)	DEFAULT ''		NOT NULL,
keywords	VARCHAR(200)	DEFAULT ''		NOT NULL,
description	VARCHAR(200)	DEFAULT ''		NOT NULL,
classification	VARCHAR(200)	DEFAULT ''		NOT NULL,
replyto		VARCHAR(50)		DEFAULT ''		NOT NULL,
language	CHAR(2)			DEFAULT ''		NOT NULL,
build		VARCHAR(14)		DEFAULT ''		NOT NULL,
KEY blog(blog),
KEY parent(parent)) ENGINE=".$DB['type']." CHARSET=UTF8");                            
db_query($_tmp, $DB_CONNECT);
db_query("OPTIMIZE TABLE ".$table[$module.'seo'],$DB_CONNECT); 
}
?>