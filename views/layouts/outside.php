<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $pageTitle ?></title>
		<link rel="stylesheet" href="<?php echo WWW_CSS_PATH ?>reset.css" type="text/css" media="all" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo WWW_CSS_PATH ?>outside.css" type="text/css" media="all" charset="utf-8" />
	</head>

	<body>
		<div class="page">
			<h1>
				<a href="<?php echo WWW_BASE_PATH ?>"><span class="title"><?php echo SITE_NAME ?></span></a>
			</h1>
			<div class="content">
				<?php if (!empty($pageHead)): ?>
					<h2><?php echo $pageHead ?></h2>
				<?php endif; ?>
				<?php $this->renderElement('flash') ?>
				<?php echo $layoutContent ?>
			</div>
		</div>
	</body>
</html>
<?php Flash::clean(); ?>