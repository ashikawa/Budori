<?php
require_once 'Zend/Filter/Input.php';

/**
 * @todo 作業中
 */
class Budori_Filter_Input extends Zend_Filter_Input
{
    public function __construct($filterRules, $validatorRules, array $data = null, array $options = null)
    {
        parent::__construct($filterRules, $validatorRules, $data, $options);

        /**
         * @todo ここの処理を外部化
         * 	Bootstrap か static facttory。
         * 	外部化できたらこのクラスが不要になる。
         */
        $this->addValidatorPrefixPath('Budori_Validate_', 'Budori/Validate');
        $this->addFilterPrefixPath('Budori_Filter_', 'Budori/Filter');

    }
}
