<?php
class PWAG_Feed
{
    public static $instance;
    
    public static function getInstance()
    {
        if ( !isset(self::$instance) )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function get($param)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, PWA_API_URL . $param);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
        
        if (SHOW_PRIVATE_ALBUMS)
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Authorization: AuthSub token="' . AUTH_SUB_TOKEN . '"' ));
        }
        
        $buffer = curl_exec($curl);
        curl_close($curl);
                
        return new SimpleXMLElement($buffer);
    }
    
    public function getAlbums()
    {
        return $this->get('?thumbsize=128');
        //return new SimpleXMLElement(file_get_contents('testfeed.xml'));
    }
    
    public function getPhotos($albumId)
    {
        return $this->get('/albumid/'. $albumId . '?thumbsize=128&imgmax=1024');
        //return new SimpleXMLElement(file_get_contents('photos.xml'));
    }
        
    public static function attrToArray($attr)
    {
        $result = array();
        foreach ($attr as $key => $value)
        {
            $result[trim((string)$key)] = trim((string)$value);
        }
        return $result;
    }
    
    public static function childrenToArray($xml, $ns)
    {
        $result = array();
        foreach($xml->children($ns) as $key => $value)
        {
            $result[(string)$key] = (string)$value;
        }
        return $result;
    }
    
}