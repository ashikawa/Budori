<?php
require_once 'Zend/Application/Resource/Mail.php';

/**
 * Loaderが警告吐いて鬱陶しいので作成。
 * Zendのバージョン上がったらすぐ不要になるはず。
 */
class Budori_Application_Resource_Mail extends Zend_Application_Resource_Mail
{
    protected function _setupTransport($options)
    {
        if (!isset($options['type'])) {
            $options['type'] = 'sendmail';
        }

        if (!isset($options['namespace'])) {
            $options['namespace'] = 'Zend_Mail_Transport';
        }

        $transportName = $options['namespace'] . '_' . ucfirst(strtolower($options['type']));
        unset($options['type']);

        if (!Zend_Loader_Autoloader::autoload($transportName)) {
            throw new Zend_Application_Resource_Exception(
                "Specified Mail Transport '{$transportName}'"
                . 'could not be found'
            );
        }

        switch ($transportName) {
            case 'Zend_Mail_Transport_Smtp':
                if (!isset($options['host'])) {
                    throw new Zend_Application_Resource_Exception(
                        'A host is necessary for smtp transport,'
                        .' but none was given');
                }

                $transport = new $transportName($options['host'], $options);
                break;
            case 'Zend_Mail_Transport_Sendmail':
            default:
                $transport = new $transportName($options);
                break;
        }

        return $transport;
    }
}
