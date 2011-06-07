<?php
require_once 'Zend/Auth/Adapter/DbTable.php';
/**
 * 通常の認証アダプタ
 * Zend_Auth_Adapter_DbTableの初期設定
 */
class Neri_Auth_Adapter_DbTable extends Zend_Auth_Adapter_DbTable 
{
    /**
     * $_tableName - the table name to check
     * @var string
     */
    protected $_tableName = 'member';
    
    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = 'key';
    
    /**
     * $_credentialColumns - columns to be used as the credentials
     *
     * @var string
     */
    protected $_credentialColumn = 'pass';
    
    /**
     * $_credentialTreatment - Treatment applied to the credential, such as MD5() or PASSWORD()
     *
     * @var string
     */
	protected $_credentialTreatment = '"member"."status" = \'1\'';
//    protected $_credentialTreatment = 'MD5(?) AND "member"."status" = \'1\'';

}
