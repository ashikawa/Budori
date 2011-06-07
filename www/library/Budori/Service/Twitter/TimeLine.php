<?php
class Budori_Service_Twitter_TimeLine implements Iterator, Countable
{
	/**
	 * @var array
	 */
	protected $_result = null;
	
	/**
	 * @var array
	 */
	protected $_results = null;
	
	protected $_pointer = 0;
	
	
	public function __construct($result)
	{
		$this->_result = $result;
		$this->_init();
	}
	
	
	protected function _init()
	{
		$results = $this->_result['results'];
		
		require_once 'Budori/Service/Twitter/Tweet.php';

		foreach ( $results as $_key => $_value ){
			$this->_results[] = new Budori_Service_Twitter_Tweet($_value);
		}
	}
	
	
//  ["max_id"]=>
//  float(7.509408625159E+16)
//  ["since_id"]=>
//  int(0)
//  ["refresh_url"]=>
//  string(37) "?since_id=75094086251589632&q=%40noma"
//  ["next_page"]=>
//  string(42) "?page=2&max_id=75094086251589632&q=%40noma"
//  ["results_per_page"]=>
//  int(15)
//  ["page"]=>
//  int(1)
//  ["completed_in"]=>
//  float(0.104461)
//  ["since_id_str"]=>
//  string(1) "0"
//  ["max_id_str"]=>
//  string(17) "75094086251589632"
//  ["query"]=>
//  string(7) "%40noma"
	
	
	public function count()
	{
		return count($this->_results);
	}
	
	public function current()
	{
        if ($this->valid() === false) {
            return null;
        }
        // return the row object
        return $this->_results[$this->_pointer];
	}
	
	public function key()
	{
        return $this->_pointer;
	}
	
	public function next()
	{
        ++$this->_pointer;
	}
	
	public function rewind()
	{
        $this->_pointer = 0;
        return $this;
	}
	
	public function valid()
	{
        return $this->_pointer >= 0 && $this->_pointer < $this->count();
	}
}