<?php
class MapSearch extends Budori_Batch_Abstruct 
{
	
	public $list = array(
		'スカイツリー'
	);
	
	public function run()
	{
		
		$google = new Budori_Service_Google_MapSearch();
		$decoder = new Zend_Json();
		
		foreach ( $this->list as $value ){
			
			$word = mb_split("[\s,　]",$value);
			$this->_log->info("query [$value]");
			
			$responce = $google->search($word[0]);
			
			$status = $responce->getStatus();
			$this->_log->info("status [$status]");
			
			if( $status == '200' ){
				$data = $decoder->decode($responce->getBody());
				
				if( isset($data['Status'])&& $data['Status']['code'] == '200' ){
					
					foreach ( $data['Placemark'] as $_val){
						echo $_val['address'] . PHP_EOL;
						echo $_val['Point']['coordinates'][0] . PHP_EOL;
						echo $_val['Point']['coordinates'][1] . PHP_EOL;
					}
				}else{
					$this->_log->warn("status [{$data['Status']['code']}]");
				}
			}
		}
	}
}
