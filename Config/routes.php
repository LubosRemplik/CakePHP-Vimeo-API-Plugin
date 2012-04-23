<?php
Router::connect(
	'/vimeo/:controller/:action/*',
	array(
		'plugin' => 'vimeo'
	)
);
