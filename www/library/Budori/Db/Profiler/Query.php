<?php
require_once 'Zend/Db/Profiler/Query.php';
require_once 'Zend/Log.php';


/**
 * Profiler にログの分の誤差が出たりとかいろいろ弊害あるけど、とりあえずこれで行く。
 */
class Budori_Db_Profiler_Query extends Zend_Db_Profiler_Query 
{
	
	/**
	 * @var Zend_log
	 */
	protected $_logger;
	
    /**
     * @override
     * @param  string  $query
     * @param  integer $queryType
     * @return void
     */
	public function __construct($query, $queryType, $logger)
	{
    	if( !($logger instanceof Zend_Log ) ){
    		require_once 'Budori/Db/Profiler/Exception.php';
			throw new Budori_Db_Profiler_Exception('$logger is not instance of Zend_Log');
    	}
		
    	$this->_logger = $logger;
    	
	    $this->_query = $query;
        $this->_queryType = $queryType;
        $this->_startedMicrotime = microtime(true);
        
        
        if( $queryType === Zend_Db_Profiler::TRANSACTION ){
        	$this->_logger->info($query);
        }
	}
	
	/**
	 * @override
	 */
	public function start()
	{
		$this->_logging();
		parent::start();
	}
	
	
	/**
	 * @return Zend_Log
	 */
	public function getLogger()
	{
		return $this->_logger;
	}
	
	protected function _logging()
	{
		$logger		= $this->getLogger();
		$output		= $this->getQuery();
		$params		= $this->getQueryParams();
		
		if(!empty($params)){
			$output .= PHP_EOL . var_export($params,true);
		}
		
		$logger->log($output, Zend_Log::INFO);
	}
}
