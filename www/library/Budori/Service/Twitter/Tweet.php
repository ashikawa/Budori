<?php
class Budori_Service_Twitter_Tweet implements ArrayAccess
{
	protected $_tweet = null;
	
	public function __construct($tweet)
	{
		$this->_tweet = $tweet;
	}
	
	// getUserLink
	// getPermaLink etc ...
	
	/**
	 * @todo 
	 */
	public function __toString()
	{
		return $this->_tweet['text'];
	}
	
	public function offsetExists($offset)
	{
		return isset($this->_tweet[$offset]);
	}
	
	public function offsetGet($offset)
	{
		return $this->_tweet[$offset];
	}
	
	public function offsetSet($offset,$value)
	{
		$this->_tweet[$offset] = $value;
	}
	
	public function offsetUnset($offset)
	{
		unset($this->_tweet[$offset]);
	}
	
}
