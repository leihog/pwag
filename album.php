<?php
class PWAG_Album
{
    protected $id;
    protected $title;
    protected $thumbnail;
    protected $description;
    protected $numberOfPhotos;
    protected $photos;
    protected $photosByIndex;
    
    public function __construct($mixed)
    {        
        if ($mixed instanceOf SimpleXMLElement)
        {
            $this->parseUserFeed($mixed);
        }
        else
        {
            $this->id = $mixed;
            $this->getData();
        }
    }
    
    protected function getData()
    {
        require_once('feed.php');
        $this->parseAlbumFeed(PWAG_Feed::getInstance()->getPhotos($this->id));
    }
    
    public function getPhoto($photoId)
    {
        return $this->photos[$photoId];
    }
    
    public function getPhotoByPosition($index)
    {
        if (isset($this->photosByIndex[$index]))
        {
            return $this->photosByIndex[$index];
        }
        
        return false;
    }
    
    /**
     * @return Array of PWAG_Photo
     */
    public function getPhotos()
    {
        if ( !isset($this->photos) && (isset($this->numberOfPhotos) && $this->numberOfPhotos > 0) )
        {
            $this->getData();
        }
        return ( isset($this->photos) ? $this->photos : array() );
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getNumberOfPhotos()
    {
        return $this->numberOfPhotos;
    }
    
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
    
    protected function parseUserFeed($xml)
    {
        $gphoto = $xml->children('http://schemas.google.com/photos/2007');
        $media = $xml->children('http://search.yahoo.com/mrss/');
        
        $this->id = $gphoto->id;
        $this->title = $xml->title;        
        $this->description = $media->group->description;
        $this->numberOfPhotos = $gphoto->numphotos;
        
        $this->thumbnail = PWAG_Feed::attrToArray($media->group->thumbnail->attributes());
    }

    protected function parseAlbumFeed($xml)
    {
        $gphoto = $xml->children('http://schemas.google.com/photos/2007');
        
        $this->id = (string)$gphoto->id;
        $this->title = (string)$xml->title;
        $this->description = (string)$xml->subtitle;
        $this->numberOfPhotos = (string)$gphoto->numphotos;
        // thumbnail doesn't exists in the album feed. Should we overwrite the thumbnail if we already have one? I'm uncertain.       
        $this->thumbnail = array('url'=>(string)$xml->icon);
        
        require_once('photo.php');
        $this->photos = array();
        $this->photosByIndex = array();
        foreach($xml->entry as $entry)
        {
            $photo = new PWAG_Photo($entry);
            $this->photos[$photo->getId()] = $photo;
            $this->photosByIndex[$photo->getPosition()] = $photo;
        }
    }

}