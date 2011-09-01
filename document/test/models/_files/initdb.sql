-- アカウントとメンバー(プロフィール)を別のテーブルに分岐するか悩み中。

CREATE TABLE member (
 key       varchar(255) primary key,
 pass      varchar(255),
 name      varchar(255),
 mixi_user varchar(255) unique,
 mixi_id   varchar(255) unique,
 mixi_pass varchar(255),
 status    boolean  DEFAULT true
);
