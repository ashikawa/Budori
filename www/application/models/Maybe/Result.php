<?php
class Maybe_Result
{
	protected $_lev;
	
	protected $_value;
	
	public function __construct($lev, $value)
	{
		$this->_lev		= $lev;
		$this->_value	= $value;
	}
	
	public function getLevenshtein()
	{
		return $this->_lev;
	}
	
	public function getValue()
	{
		return $this->_value;
	}
}
