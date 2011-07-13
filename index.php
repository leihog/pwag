<?php

/**
 * PWAG - Picasa Web Album Gallery
 * 
 * PWAG is a proof of concept for integrating Picasa Web Albums (PWA) in to an existing web site.
 * PWAG is not a complete application with all the bells and whistles. If you are looking for an out of the box solution this is not for you.
 * If you however are a PHP developer wanting to learn more about how to integrate PWA with your existing site then this might be helpful.
 * 
 * 
 */

require_once('config.php');

define('AUTH_SUB_REQUEST_URL', 'http://www.google.com/accounts/AuthSubRequest?scope=http%3A%2F%2Fpicasaweb.google.com%2Fdata%2F&session=1&secure=0&next=');
define('PWA_API_URL', 'http://picasaweb.google.com/data/feed/api/user/' . PWA_USER);

class PWAG
{
    public function __construct()
    {
        if (!$this->checkConfig())
        {
            return;
        }
        
        if ( isset($_GET['photo']) )
        {
            $this->viewPhoto($_GET['album'], $_GET['photo']);
        }
        else if ( isset($_GET['album']) )
        {
            $this->listPhotos($_GET['album']);
        }
        else
        {
            $this->listAlbums();
        }
    }
    
    protected function checkConfig()
    {
        if (AUTH_SUB_TOKEN == '')
        {
            $this->tokenSetup();
            return false;
        }
        return true;
    }

    public function listAlbums()
    {        
        require_once('feed.php');
        $xml = PWAG_Feed::getInstance()->getAlbums();

        require_once('album.php');        
        $this->albums = array();
        foreach($xml->entry as $entry)
        {
            $this->albums[] = new PWAG_Album($entry);
        }
        
        include('views/albums.php');
    }
    
    protected function listPhotos($albumId)
    {
        require_once('album.php');
        $this->album = new PWAG_Album($albumId);
        
        include('views/photos.php');
    }
    
    protected function viewPhoto($albumId, $photoId)
    {
        require_once('album.php');
        $this->album = new PWAG_Album($albumId);
        $this->photo = $this->album->getPhoto($photoId);
        
        include('views/photo.php');
    }
    
    protected function imageToHTML($image)
    {
        $html = '<img src="' . $image['url'] . '" ';
        if ( isset($image['height']) && isset($image['width']) )
        {
            $html .= 'height="' . $image['height'] . '" width="' . $image['width'] . '" ';
        }
        return $html . '/>';
    }
    protected function baseUrl()
    {
        return dirname($_SERVER['REQUEST_URI']);
    }
    
    protected function requestSessionToken($token)
    {
        $curl = curl_init("https://www.google.com/accounts/AuthSubSessionToken");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Authorization: AuthSub token="' . $token . '"' ));
        $buffer = curl_exec($curl);
        curl_close($curl);
        
        list($key, $token) = split("=", $buffer);
        $this->AuthSubToken = $token;
    }
    
    public function tokenSetup()
    {
        if (isset($_GET['recievetoken']))
        {
            $this->requestSessionToken($_GET['token']);
            include('views/token-setup-result.php');
            return;
        }
        
        $url = $_SERVER['SERVER_NAME'] . ( $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '' );
        $url .= $_SERVER['PHP_SELF'];
        $url = urlencode("http://" . $url . "?recievetoken=true");
        $this->picasaAuthSubRequest = AUTH_SUB_REQUEST_URL . $url;
        include('views/token-setup-login.php');
    }
    
}
$pwag = new PWAG();
