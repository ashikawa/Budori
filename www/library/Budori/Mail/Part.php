<?php
/**
 * Zend_Mail_Part の日本語変換用
 */
class Budori_Mail_Part extends Zend_Mail_Part
{

    /**
     * Get a header in specificed format
     *
     * Internally headers that occur more than once are saved as array, all other as string. If $format
     * is set to string implode is used to concat the values (with Zend_Mime::LINEEND as delim).
     *
     * @param  string              $name   name of header, matches case-insensitive, but camel-case is replaced with dashes
     * @param  string              $format change type of return value to 'string' or 'array'
     * @return string|array        value of header in wanted or internal format
     * @throws Zend_Mail_Exception
     */
    public function getHeader($name, $format = null)
    {
        $header = parent::getHeader($name, $format);

        if (is_string($header)) {
            return  mb_decode_mimeheader($header);
        }

        return array_map("mb_decode_mimeheader", $header);
    }

    /**
     * Get all headers
     *
     * The returned headers are as saved internally. All names are lowercased. The value is a string or an array
     * if a header with the same name occurs more than once.
     *
     * @return array headers as array(name => value)
     */
    public function getHeaders()
    {
        $headers = parent::getHeaders();

        return array_map("mb_decode_mimeheader", $headers);
    }

    /**
     * Body of part
     *
     * If part is multipart the raw content of this part with all sub parts is returned
     *
     * @return string              body
     * @throws Zend_Mail_Exception
     */
    public function getContent()
    {
        $content	= parent::getContent();

        if ( $this->headerExists("CONTENT-TRANSFER-ENCODING") ) {

            $transfer = $this->getHeader("CONTENT-TRANSFER-ENCODING");

            switch ( strtolower($transfer) ) {
                case Zend_Mime::ENCODING_BASE64:
                    $content = base64_decode($content);
                    break;

                case Zend_Mime::ENCODING_7BIT:
                case Zend_Mime::ENCODING_8BIT:
                default:
                    break;
            }
        }

        return mb_convert_encoding($content, mb_internal_encoding(), "auto");
    }
}
