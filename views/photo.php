<?php include('header.php'); ?>

	<h2><a href="<?php echo $this->baseUrl(); ?>">Albums</a> / <?php printf('<a href="?album=%s">%s</a>', $this->album->getId(), $this->album->getTitle()); ?></h2>
	
	<div id="photo">
        <?php echo $this->imageToHTML($this->photo->getImage()); ?>
		
		<div class="navigation">
			<?php 
			    $url = '?album=' . $this->album->getId() . '&photo=';
			    if ( ($prev = $this->album->getPhotoByPosition($this->photo->getPosition()-1)) !== false )
			    {
			        printf('<a href="%s">&lt;</a> ', $url . $prev->getId());
			    }
			    else
			    {
			        echo '&lt; ';
			    }
			    
			    echo '[' . $this->photo->getPosition() . ' of ' . $this->album->getNumberOfPhotos() . ']';
			    
			    if ( ($prev = $this->album->getPhotoByPosition($this->photo->getPosition()+1)) !== false )
			    {
			        printf(' <a href="%s">&gt;</a>', $url . $prev->getId());
			    }
			    else
			    {
			        echo ' &gt;';
			    }
			    
			?>			
		</div>
		
        <p class="caption">
            <?php echo $this->photo->getDescription(); ?>
        </p>
		
        <div class="exif">
        	
        	<div>
        		<span class="header">Date</span>
        		<span class="value"><?php date_default_timezone_set('Europe/Stockholm'); echo date('F j Y', $this->photo->getTimestamp() ); ?></span>
        	</div>        	
        	<div>
        		<span class="header">Dimensions</span>
        		<span class="value"><?php echo implode('*', $this->photo->getDimensions()); ?></span>
        	</div>
        	<div>
        		<span class="header">File size</span>
        		<span class="value"><?php echo $this->photo->getSize(); ?></span>        		
        	</div>
        	<br />
        
        	<?php
        	    foreach($this->photo->getExif() as $key => $value)
        	    {
        	        if ('imageUniqueID' !== $key && 'time' !== $key)
        	        {
                        ?>
                        <div>
                        	<span class="header"><?php echo ucfirst($key); ?></span>
                        	<span class="value"><?php echo $value; ?></span>
                        </div>
                        <?php
        	        }
        	    } 
        	?>
        </div>
	</div>
	
<?php include('footer.php'); ?>