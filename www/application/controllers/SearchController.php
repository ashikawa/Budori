<?php
class SearchController extends Neri_Controller_Action_Http
{
    public function resultAction()
    {
        $params = array_merge(
            array(
                'q'	=> null,
                'p'	=> 1,
            ), $this->_getAllParams() );

        $page	= intval($params['p']);

        if ( $this->getRequest()->isGet() ) {
            $params['q'] = urldecode($params['q']);
        }

        $db = Budori_Db::factory();

        $select = new Neri_Db_Select_Url($db);
        $select->searchBodyText($params['q']);

        $record	= $db->fetchAll($select);

        $this->view->assign('record', $record);
    }
}
