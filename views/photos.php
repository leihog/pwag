<?php include('header.php'); ?>

<h2><a href="<?php echo $this->baseUrl(); ?>">Albums</a> / <?php echo $this->album->getTitle(); ?> [<?php echo $this->album->getNumberOfPhotos(); ?>]</h2>

<ul id="thumbnails">
<?php foreach ($this->album->getPhotos() as $photo): ?>

	<li>
		<span class="outer">
			<span class="inner">
				<a href="?album=<?php echo $this->album->getId();?>&photo=<?php echo $photo->getId(); ?>"><?php echo $this->imageToHTML($photo->getThumbnail()); ?></a>
			</span>
			<?php echo $photo->getDescription(); ?>
		</span>
	</li>
	
<?php endforeach;?>
</ul>

<?php include('footer.php'); ?>