-- 事前に
-- createlang plpgsql DB名
-- (LINUX コマンド)を実行しておくこと

-- Function: trigger_function_date_updater()

-- DROP FUNCTION trigger_function_date_updater();
-- CREATE OR REPLACEで既に同名のトリガー関数が存在していた場合に対処
CREATE OR REPLACE FUNCTION trigger_function_date_updater()

-- 関数の中身がどこからどこまでか明示する方法は何通りかある
-- この場合はASを使って$BODY$～$BODY$であると定義している．Perlのヒアドキュメントみたいな指定方法
  RETURNS "trigger" AS
$BODY$    BEGIN
	-- TG_OPは，スクリプト起動時に自動的にセットされる定数の一つ
	-- トリガがどのような文脈で起動されたかを格納している
--	IF TG_OP = 'INSERT' THEN
		-- NEWオブジェクトを操作して値をDBに反映させる
--		NEW.update := current_timestamp;
--		NEW.register := current_timestamp;
--	ELSE
		IF TG_OP = 'UPDATE' THEN
			NEW.update := current_timestamp;
		END IF ;
--	END IF ;
	-- 操作済みNEWオブジェクトを返す．DBはこれを使ってデータを更新するのでデータベースに反映される
	RETURN NEW;
    END ;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE;



-- ALTER FUNCTION trigger_function_date_updater() OWNER TO test;

-- register は テーブル定義の　default now()　で代用
-- これは \d コマンドでテーブルを見たときの情報を多くして、一覧性を高めるため。
