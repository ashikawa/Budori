<?php
class TwitterClowler
{
    /**
     * @var Zend_Service_Twitter_Search
     */
    protected $_client = null;

    public function __construct()
    {
        $this->_client = new Zend_Service_Twitter_Search();
    }

    public function search()
    {
        $service	= $this->_client;

        // get max_id or reflesh

        $params		= array(
            "from" 		=> "m_s_modified",
//			"since_id"	=> $max_id,
        );

        $response	= $service->restGet("/search.json", $params);
        $result		= Zend_Json::decode($response->getBody());

        // db insert ...
        return $result;
    }

//
//  ["results"]=>
//  array(15) {
//    [0]=>
//    array(14) {
//      ["from_user_id_str"]=>
//      string(8) "74621728"
//      ["profile_image_url"]=>
//      string(70) "http://a2.twimg.com/profile_images/575934935/20095212000019_normal.jpg"
//      ["created_at"]=>
//      string(31) "Thu, 07 Jul 2011 00:56:18 +0000"
//      ["from_user"]=>
//      string(12) "m_s_modified"
//      ["id_str"]=>
//      string(17) "88773300716699648"
//      ["metadata"]=>
//      array(1) {
//        ["result_type"]=>
//        string(6) "recent"
//      }
//      ["to_user_id"]=>
//      NULL
//      ["text"]=>
//      string(149) "『Thunderbolt』ケーブルは「小型コンピューター」 « WIRED.jp 世界最強の「テクノ」ジャーナリズム http://t.co/A32rLiW"
//      ["id"]=>
//      float(8.87733007167E+16)
//      ["from_user_id"]=>
//      int(74621728)
//      ["geo"]=>
//      NULL
//      ["iso_language_code"]=>
//      string(2) "ja"
//      ["to_user_id_str"]=>
//      NULL
//      ["source"]=>
//      string(104) "&lt;a href=&quot;http://twitter.com/tweetbutton&quot; rel=&quot;nofollow&quot;&gt;Tweet Button&lt;/a&gt;"
//    }
//
//
//	.....
//
//
//  ["max_id"]=>
//  float(8.877422716715E+16)
//  ["since_id"]=>
//  int(0)
//  ["refresh_url"]=>
//  string(50) "?since_id=88774227167150081&q=+from%3Am_s_modified"
//  ["next_page"]=>
//  string(55) "?page=2&max_id=88774227167150081&q=+from%3Am_s_modified"
//  ["results_per_page"]=>
//  int(15)
//  ["page"]=>
//  int(1)
//  ["completed_in"]=>
//  float(0.436665)
//  ["since_id_str"]=>
//  string(1) "0"
//  ["max_id_str"]=>
//  string(17) "88774227167150081"
//  ["query"]=>
//  string(20) "+from%3Am_s_modified"
//}
}
