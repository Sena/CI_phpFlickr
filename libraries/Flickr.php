<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/phpFlickr/phpFlickr.php"; 
 
class Flickr extends phpFlickr {

    const FLICKR_API_KEY = 'your_api_key';
    const FLICKR_API_SECRET = 'yout_api_secret'; 

    public function __construct($api_key = self::FLICKR_API_KEY, $secret = self::FLICKR_API_SECRET, $die_on_error = false){
        parent::__construct($api_key, $secret, $die_on_error);
    }

    public function enableCache ($type, $connection = NULL, $cache_expire = 600, $table = 'flickr_cache') {
        switch ($type) {

            case 'fs':
                if($connection === NULL) {
                    $connection = APPPATH . 'cache';
                }
                if (is_dir($connection) === FALSE) {
                    @mkdir ($connection, 0777);
                }elseif ((string)fileperms($connection) != '0777') {
                    chmod($connection, 0777);
                }

                if (is_dir($connection) === FALSE){//Needs to check if is 0777
                    return FALSE;
                }
                break;

            case 'db':
                if($connection === NULL) {
                    $ci =& get_instance();

                    if($ci->db->dbdriver == 'mysqli'
                        || $ci->db->dbdriver == 'mysql') {
                        $this->enableCache('db', 'mysql://' . $ci->db->username . ':' . $ci->db->password . '@' . $ci->db->hostname . '/' . $ci->db->database);
                    } else{
                        return FALSE;
                    }
                }
                break;
        }
        parent::enableCache ($type, $connection, $cache_expire, $table);
    }
}
