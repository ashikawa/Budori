CREATE TABLE zip (
 code   varchar(255) NOT NULL,
 pref   varchar(255),
 city   varchar(255),
 town   varchar(255)
);
CREATE INDEX zip_index1 ON zip (code);
