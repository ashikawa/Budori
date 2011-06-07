-- iCalendarの仕様に合わせる。
-- それ以上の事はテーブル継承かな？

CREATE TABLE event (
 key       serial primary key,
 name      varchar(255),
 place     varchar(255),
 longitude float,--緯度
 latitude  float,--経度
 date      timestamp without time zone,
 status    boolean  DEFAULT true
);
