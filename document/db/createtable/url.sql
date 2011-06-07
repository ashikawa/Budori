-- 単純に、アクセス数でランクしているが、
-- 係数とか掛けて細かくやる？　rank * num * .....
-- '/' の重みを0にしたりとか。
-- そのときは、インデックス張らないとダメだな。
--
DROP TABLE url;

CREATE TABLE url (
 key       serial primary key,
 url       text unique NOT NULL,
 num       integer DEFAULT 0,
 title     text NOT NULL,
 body      text,
 statement text,
 status   boolean  DEFAULT true
);

CREATE INDEX url_textsearch_idx ON url USING gin(to_tsvector('japanese', statement));
