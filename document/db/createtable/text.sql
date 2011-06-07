-- 全文検索用、独立テーブル
-- Insert はプログラムでやるか、テーブルの制約とかでやるか悩み中。

CREATE TABLE text (
 key       serial primary key,
 name      varchar(255),
 start_tim timestamp without time zone,
 data      text,
 tsv       tsvector
);



CREATE TRIGGER tsvupdater BEFORE INSERT OR UPDATE
ON text FOR EACH ROW EXECUTE PROCEDURE

tsvector_update_trigger(tsv,'pg_catalog.japanese',name,data);

CREATE INDEX textgin ON text USING gin(tsv);



--CREATE INDEX textgin ON text USING gin(to_tsvector('japanese', name || ' ' || data));
