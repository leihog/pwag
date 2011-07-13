<?php
class PWAG_Photo
{
    protected $id;
    protected $image;
    protected $thumbnail;
    protected $description;
    protected $position;
    protected $dimensions;
    protected $access;
    protected $exif;
    protected $timestamp;
    
    public function __construct($mixed)
    {        
        if ($mixed instanceOf SimpleXMLElement)
        {
            $this->parseXml($mixed);
        }
        else
        {
            $this->id = $mixed;
            
            require_once('feed.php');
            $this->parseXml(PWAG_Feed::getInstance()->getPhoto($this->id));
        }
    }
        
    public function getId()
    {
        return $this->id;
    }
        
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    public function getExif()
    {
        return $this->exif;
    }
    
    public function getWidth()
    {
        return $this->dimensions['width'];
    }

    public function getHeight()
    {
        return $this->dimensions['height'];
    }
    
    public function getSize()
    {
        return $this->size;
    }
    
    public function isPublic()
    {
        return ( $this->access !== 'public' ? false : true);
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
    
    public function getTimestamp()
    {
        return $this->timestamp  / 1000;
    }
    
    protected function parseXml($xml)
    {
        $gphoto = $xml->children('http://schemas.google.com/photos/2007');
        $media  = $xml->children('http://search.yahoo.com/mrss/');
       
        $this->id           = (string) $gphoto->id;
        $this->description  = (string) $media->group->description;
        $this->position     = (int)    $gphoto->position;
        $this->size         = (int)    $gphoto->size;
        $this->access       = (string) $gphoto->access;
        $this->timestamp    = (string) $gphoto->timestamp;
                
        $this->dimensions   = array(
        	'width' => (int)$gphoto->width,
            'height' => (int)$gphoto->height
        );
       
        $exifTags    = $xml->children('http://schemas.google.com/photos/exif/2007');
        $this->exif  = PWAG_Feed::childrenToArray($exifTags->tags, 'http://schemas.google.com/photos/exif/2007');
        
        $this->image        = PWAG_Feed::attrToArray($media->group->content->attributes());
        $this->thumbnail    = PWAG_Feed::attrToArray($media->group->thumbnail->attributes());
                
    }
        
}