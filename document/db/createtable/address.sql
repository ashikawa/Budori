CREATE TABLE address (
 key     serial primary key,
 member  varchar(255) NOT NULL REFERENCES member (key),
 value   varchar(255) NOT NULL,
 status boolean DEFAULT true
);
