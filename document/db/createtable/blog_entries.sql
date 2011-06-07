CREATE TABLE blog_entries (
 key       serial primary key,
 date      date DEFAULT CURRENT_DATE,
 title     varchar(255),
 content   text,
 status    boolean  DEFAULT true,
 register  timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
 update    timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX blog_entries_textsearch_idx ON url USING gin(to_tsvector('japanese', content));
