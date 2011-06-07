<?php
class TwitterTest extends PHPUnit_Framework_TestCase
{	
	public function setUp()
	{
		parent::setUp();
		
		require_once  '../application/defines.inc';
		
		require_once 'Zend/Cache.php';
		
		$filecache = Zend_Cache::factory('Core', 'File',
			array('lifetime'	=> '7200', 'automatic_serialization'	=> true),
			array('cache_dir'	=> CACHE_DIR) );
		
		
		require_once 'Zend/Cache/Manager.php';
		$manager = new Zend_Cache_Manager();
		$manager->setCache('file',$filecache);
		
		require_once 'Zend/Registry.php';
		Zend_Registry::set('cachemanager',$manager);
	}
	
	
	
	
	public function testShortUrlTest()
	{
		require_once 'Budori/Service/ShortUrl.php';
		
		$service	= new Budori_Service_ShortUrl();
		
		$shortUrl	= "http://t.co/X0RvHma";
		$results	= $service->search($shortUrl,false);
		
		$this->assertEquals( $results, "http://twitpic.com/54vi8m" );
	}
}
