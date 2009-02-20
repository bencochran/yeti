<?php if($flash = Flash::get()) : ?>
	<p class="<?php echo $flash['status'] ?>">
		<?php echo $flash['message']; ?>
	</p>
<?php endif; ?>