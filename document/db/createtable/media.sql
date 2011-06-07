CREATE TABLE media (
 key     serial primary key,
 ext     varchar(255) NOT NULL,
 owner   varchar(255) REFERENCES member (key) NOT NULL,
 name    varchar(255) NOT NULL,
 mime    varchar(255) NOT NULL,
 size    int NOT NULL,
 data    text,
 status  boolean  DEFAULT true
);
