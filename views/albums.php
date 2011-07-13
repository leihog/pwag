<?php include('header.php'); ?>


<ul id="albums">
<?php foreach ($this->albums as $album): ?>
	<li>
    	<div class="album">
    		<div class="thumbnail">
    		    <a href="?album=<?php echo $album->getId();?>"><?php echo $this->imageToHTML($album->getThumbnail()); ?></a>
    		</div>
    		<div class="description">
    			<h3><a href="?album=<?php echo $album->getId();?>"><?php echo $album->getTitle(); ?></a></h3>
    		    <div class="text">
    		    	<p>
    		    	    <?php
    		    	        $c = $album->getNumberOfPhotos();
    		    	        printf('%d image%s', $c, ($c==1?'':'s') );
    		    	    ?>
    		    	</p>
    		        <p>
    		            <?php echo $album->getDescription(); ?>
    		        </p>
    		    </div>
    		</div>
    		
    	</div>
	</li> 
<?php endforeach;?>
</ul>

<?php include('footer.php'); ?>