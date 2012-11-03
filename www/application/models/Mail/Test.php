<?php
/**
 * @todo もっとすっきり書く。
 *
 */
class Mail_Test
{
    /**
     * Enter description here...
     * @var Zend_Config
     */
    protected $_options;

    /**
     * Enter description here...
     * @var Zend_View
     */
    protected $_view	= null;

    /**
     * Enter description here...
     * @var Mail_AddressList_Interface
     */
    protected $_addressList = array();

    /**
     * Enter description here...
     * @var Zend_Mail
     */
    protected $_mail	= null;

    public function __construct(Zend_Config $config)
    {
        $this->_options = $config;

        $class  = $this->getOption('class','Zend_Mail');
        $this->_mail	= new $class();
    }

    /**
     * Enter description here...
     * @param  Mail_AddressList_Interface $list
     * @return Mail_Test
     */
    public function setAddressList(Mail_AddressList_Interface $list)
    {
        $this->_addressList = $list;

        return $this;
    }

    public function getAddressList()
    {
        return $this->_addressList;
    }

    public function getSubject()
    {
        return $this->getOption('subject');
    }

    public function assign($spec, $value = null)
    {
        $this->_getView()->assign($spec, $value);
    }

    public function getOption( $name, $defaulet=null)
    {
        if (isset($this->_options->$name)) {
            return $this->_options->$name;
        }

        return $defaulet;
    }

    public function getViewOption($name, $default=null)
    {
        if (isset($this->_options->view->$name)) {
            return $this->_options->view->$name;
        }

        return $default;
    }

    /**
     * Enter description here...
     * @return Zend_View
     */
    public function getView()
    {
        if (is_null($this->_view)) {
            $view = clone Zend_Layout::getMvcInstance()->getView();

            $view->clearVars();
            $view->setBasePath($this->_options->view->basepath);

            $this->_view = $view;
        }

        return $this->_view;
    }

    /**
     * @param  Zend_View_Interface $view
     * @return Mail_Test
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;

        return $this;
    }

    public function send()
    {
        $cnt		= 0;

        $view		= $this->getView();
        $mail 		= $this->_mail;
        $subject	= $this->getSubject();

        $pathHtml	= $this->getViewOption('pathHtml');
        $pathText	= $this->getViewOption('pathText');

        $mail->setSubject( $subject );

        $assigns = array(
            'charset'	=> $this->_mail->getCharset(),
        );

        $view->assign($assigns);

        $address	= $this->getAddressList();

        if (empty($address)) {
            throw new Budori_Exception('address list is empty');
        }

        foreach ($address as $_key => $_value) {
            $bodyHtml = $view->render($pathHtml);
            $bodyText = $view->render($pathText);

            $toAddress	= $_value->getAddress();
            $toName		= $_value->getName();

            $view->assign($_value->getOptions());

            $mail->setBodyHtml($bodyHtml)
                    ->setBodyText($bodyText)
                    ->addTo( $toAddress, $toName )
                    ->send();

            $mail->clearRecipients();

            $cnt++;
        }

        return $cnt;
    }
}
