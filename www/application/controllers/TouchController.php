<?php
/**
 * iPod用サイト
 * デモ メインはテンプレートないのjs
 */
class TouchController extends Neri_Controller_Action_Http
{
    public function init()
    {
        parent::init();
        $this->disableLayout();
    }

    public function indexAction()
    {
        $carrier = new Budori_Mobile();
        var_export($carrier->searchCarrier());
    }

}
