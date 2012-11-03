<?php
/**
 * @todo Controller_Request_Mobile とかの方が良いかもしれない。
 */
class Budori_Mobile
{

    const CARRIER_DOCOMO	= "DoCoMo";
    const CARRIER_EZWEB		= "EZweb";
    const CARRIER_SOFTBANK	= "SoftBank";
    const CARRIER_WILLCOM	= "Willcom";
    const CARRIER_IPHONE	= "iPhone";

    protected $_userAgent;

    protected $_classPrefix = 'Budori_Mobile_';

    protected $_uaRegex = array(
        self::CARRIER_DOCOMO	=> array('/DoCoMo/'),
        self::CARRIER_EZWEB		=> array('/KDDI-/','/UP\.Browser/'),
        self::CARRIER_SOFTBANK	=> array('/SoftBank/','/Semulator/','/Vodafone!/','/Vemulator/',
                                                '/MOT-/','/MOTEMULATOR/','/J-PHONE/','/J-EMULATOR/'),
        self::CARRIER_WILLCOM	=> array('/Mozilla\/3\.0\((:DDIPOCKET|WILLCOM);/'),
        self::CARRIER_IPHONE	=> array('/iPhone/','/iPod/'),
    );

    public function __construct()
    {
        $this->_userAgent = @$_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * キャリアを調べる
     * @return string | null
     */
    public function searchCarrier()
    {
        $r = new ReflectionClass( $this );
        $carriers = $r->getConstants();

        $match = 0;

        foreach ($carriers as $_carrier) {

            foreach ($this->_uaRegex[$_carrier] as $_regex) {

                $match = preg_match($_regex, $this->_userAgent );

                if ($match === false) {
                    require_once 'Budori/Mobile/Exception.php';
                    throw new Budori_Mobile_Exception('preg_match failer');
                }

                if( $match > 0 ) return $_carrier;
            }
        }

        return null;
    }

    /**
     * Enter description here...
     * @return Zend_Config
     */
    public static function getDomains()
    {
        /**
         * @todo cache !!
         */
        require_once 'Budori/Config.php';

        return Budori_Config::factory('Budori/Mobile/domains.inc');
    }
}
