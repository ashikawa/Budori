<?php
require_once 'Zend/View/Helper/FormElement.php';

class Budori_View_Helper_Cf_FormCheckbox extends Zend_View_Helper_FormElement
{
    protected static $_defaultCheckedOptions = array(
        'checkedValue'   => '1',
        'uncheckedValue' => '0'
    );
	
	public function Cf_formCheckbox($name, $value = null, $attribs = null, array $checkedOptions = null)
	{
		$info = $this->_getInfo($name, $value, $attribs);
		extract($info); // name, id, value, attribs, options, listsep, disable
		
		$checkedOptions = self::determineCheckboxInfo($value, $checked, $checkedOptions);
		
		$xhtml = $this->view->escape($checkedOptions['checkedValue']);
		
		return $xhtml;
	}

	/**
	 * Determine checkbox information
	 *
	 * @param  string $value
	 * @param  bool $checked
	 * @param  array|null $checkedOptions
	 * @return array
	 */
	public static function determineCheckboxInfo($value, $checked, array $checkedOptions = null)
	{
		// Checked/unchecked values
		$checkedValue   = null;
		$uncheckedValue = null;
		if (is_array($checkedOptions)) {
			if (array_key_exists('checkedValue', $checkedOptions)) {
				$checkedValue = (string) $checkedOptions['checkedValue'];
				unset($checkedOptions['checkedValue']);
			}
			if (array_key_exists('uncheckedValue', $checkedOptions)) {
				$uncheckedValue = (string) $checkedOptions['uncheckedValue'];
				unset($checkedOptions['uncheckedValue']);
			}
            if (null === $checkedValue) {
                $checkedValue = (string) array_shift($checkedOptions);
            }
            if (null === $uncheckedValue) {
                $uncheckedValue = (string) array_shift($checkedOptions);
            }
        } elseif ($value !== null) {
            $uncheckedValue = self::$_defaultCheckedOptions['uncheckedValue'];
        } else {
            $checkedValue   = self::$_defaultCheckedOptions['checkedValue'];
            $uncheckedValue = self::$_defaultCheckedOptions['uncheckedValue'];
        }

        // is the element checked?
        $checkedString = '';
        if ($checked || ((string) $value === $checkedValue)) {
            $checkedString = ' checked="checked"';
            $checked = true;
        } else {
            $checked = false;
        }

        // Checked value should be value if no checked options provided
        if ($checkedValue == null) {
            $checkedValue = $value;
        }

        return array(
            'checked'        => $checked,
            'checkedString'  => $checkedString,
            'checkedValue'   => $checkedValue,
            'uncheckedValue' => $uncheckedValue,
        );
    }
}
