<?php

/**
 * メソッドの上書きが気持ち悪い。
 * 今のところこれ以上は厳しいか。
 */
class Budori_Db_Profiler extends Zend_Db_Profiler
{
    /**
     * logger
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @param Zend_Log $logger
     */
    public function setLogger($logger)
    {
        if ( !($logger instanceof Zend_Log ) ) {
            require_once 'Budori/Db/Profiler/Exception.php';
            throw new Budori_Db_Profiler_Exception('logger must instance of Zend_Log');
        }
        $this->_logger = $logger;
    }

    /**
     * @return Zend_Log | null
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     *
     * @override
     * @param  string       $queryText SQL statement
     * @param  integer      $queryType OPTIONAL Type of query, one of the Zend_Db_Profiler::* constants
     * @return integer|null
     */
    public function queryStart($queryText, $queryType = null)
    {
        if (!$this->_enabled) {
            return null;
        }

        // make sure we have a query type
        if (null === $queryType) {
            switch (strtolower(substr(ltrim($queryText), 0, 6))) {
                case 'insert':
                    $queryType = self::INSERT;
                    break;
                case 'update':
                    $queryType = self::UPDATE;
                    break;
                case 'delete':
                    $queryType = self::DELETE;
                    break;
                case 'select':
                    $queryType = self::SELECT;
                    break;
                default:
                    $queryType = self::QUERY;
                    break;
            }
        }

        require_once 'Budori/Db/Profiler/Query.php';

        $logger = $this->getLogger();
        $pq = new Budori_Db_Profiler_Query($queryText, $queryType, $logger);

        $this->_queryProfiles[] = $pq;

        end($this->_queryProfiles);

        return key($this->_queryProfiles);
    }
}
