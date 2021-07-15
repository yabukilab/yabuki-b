# (A1)mydbというデータベースが既にあるなら削除する（危険）．
drop database if exists mydb;

# (A2)mydbというデータベースを作る．
create database mydb charset=utf8mb4;

# (A3)ユーザ名testuser，パスワードpassでmydbにアクセスできるようにする．
grant all on mydb.* to user@localhost identified by 'pass';

# (A4)mydbを使う．
use mydb;

# (A5)table1というテーブルが既にあるなら削除する（危険）．
drop table if exists reserve1;
drop table if exists user1;

create table reserve1 (
id int primary key auto_increment, # ここはいつも同じ
name varchar(6) not null,
sheet varchar(3) not null,
resdata datetime# 最後にはカンマがないことに注意．
 );

# (C1)データを作成する．
insert into reserve1 (id, name, sheet, resdata) values
(1, '千葉吉', 'A2','2021-07-01 9:15:00'),
(2, '千葉工大', 'A3','2021-07-01 9:15:00' ),
(3, '千葉工大', 'B1','2021-07-01 9:30:00' ),
(4, '千葉吉' ,'B2','2021-07-01 9:30:00' ),
(5, '長谷川大吾', 'C4','2021-07-01 9:30:00' ),
(6, '長谷川大吾', 'D4','2021-07-01 9:30:00' ),
(7, '千葉吉' ,'D5','2021-07-01 9:30:00' ),
(8, '千葉中大' ,'Q1','2021-07-01 9:45:00' ),
(9, '千葉中大' ,'Q2','2021-07-01 9:45:00' ); 



create table user1 (
userid int primary key auto_increment, # ここはいつも同じ
name varchar(6) not null);


# (C1)データを作成する．
insert into user1 (userid, name) values
(1942006, '千葉吉'),
(1942074, '千葉太郎'),
(1942038, '千葉工'),
(1942089, '長谷川大吾'),
(1942075, '千葉雄大'),
(1942093, '千葉工大'),
(1942029, '千葉高大'),
(1942064, '千葉中大'),
(1942094, '千葉小大'); 