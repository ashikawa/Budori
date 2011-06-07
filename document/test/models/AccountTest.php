<?php
require_once 'Zend/Test/PHPUnit/DatabaseTestCase.php';

class LibrariesTest extends Zend_Test_PHPUnit_DatabaseTestCase
{	
	/**
	 * @var Zend_Db_Adapter_Abstract
	 */
	private $_connection = null;
		
	/**
	 * Returns the test database connection.
	 *
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected function getConnection()
	{
		if($this->_connection == null) {
			
			$connection = Zend_Db::factory('pdo_sqlite', array('dbname' => ':memory:'));
			
			$connection->query( file_get_contents(dirname(__FILE__) . '/_files/initdb.sql') );
			
			$this->_connection = $this->createZendDbConnection(
				$connection, 'zfunittests'
			);
			Zend_Db_Table_Abstract::setDefaultAdapter($connection);
			
		}
		return $this->_connection;
	}
	
	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	protected function getDataSet()
	{
		return $this->createFlatXmlDataSet(
			dirname(__FILE__) . '/_files/admin.xml'
		);
	}
	
	public function testAccountTest()
	{
		$model = new Account($db);
		$this->assertAttributeEmpty( $model->read('hoge') );
	}
}
