<?php

/**
 * 開発環境、コード保管用ダミー
 * 
 */
class Budori_Log_Interface
{
	
    public function log( $messages, $priority );
	
	public function emerg( $messages );
    
    public function alert( $messages );
    
	public function crit( $messages );
	
    public function err( $messages );
    
    public function warn( $messages );
    
    public function notice( $messages );
    
    public function info( $messages );
    
    public function debug( $messages );
	
}
