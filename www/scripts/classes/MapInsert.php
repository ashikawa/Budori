<?php
class MapInsert
{
	public $list = array(
		array('dummy event','sky tree','2011-11-11 11:11:11','35.710139','139.81083'),
	);
	
	public function getList()
	{
		return $this->list;
	}
	
	
	public function run()
	{
		$list = $this->getList();
		
		$db = Budori_Db::factory();
		
		$table = new Neri_Db_Table_Event( array( 'db' => $db ) );
		
		$db->beginTransaction();
		
		
		try {
			foreach ( $list as $value){
				
				$data = array(
					'name'		=> html_entity_decode($value[0]),
					'place'		=> html_entity_decode($value[1]),
					'longitude' => $value[3],
					'latitude'	=> $value[4],
					'date'		=> $value[2],
					'status'	=> true,
				);
				
				
				$table->insert($data);
				echo $data['name'] . PHP_EOL;
			}
			
		}catch (Exception $e){
			$db->rollBack();
			var_export($e);
			exit;
		}
		
		$db->commit();
		echo 'success';
	}
	
	
}

require_once '../init.inc';
$model = new MapInsert();
$model->run();

