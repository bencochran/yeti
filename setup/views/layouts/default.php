<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $pageTitle ?></title>
		<?php

		if (isset($requiredCss)) {
			foreach ($requiredCss as $css => $use) {
				if ($use) echo '<link rel="stylesheet" href="' . WWW_CSS_PATH . $css . '" type="text/css" media="all" charset="utf-8" />' . "\n";
			}
		}

		if (isset($requiredJs)) {
			foreach ($requiredJs as $js => $use) {
				if ($use) echo '<script type="text/javascript" language="javascript" charset="utf-8" src="' . WWW_JS_PATH . $js . '"></script>' . "\n";
			}
		}

		?>
	</head>

	<body>
		<div id="wrapper">
			<h1><span class="dark">Yeti</span> Configuration</h1>

			<div class="section section-enabled">
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
