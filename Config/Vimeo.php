<?php
/**
 * A Vimeo API Method Map
 *
 * Refer to the apis plugin for how to build a method map
 * https://github.com/ProLoser/CakePHP-Api-Datasources
 *
 */
$config['Apis']['Vimeo']['hosts'] = array(
	'oauth' => 'vimeo.com/oauth',
	'rest' => 'vimeo.com/api/rest/v2',
);
// http://vimeo.com/api/docs/advanced-api
$config['Apis']['Vimeo']['oauth'] = array(
	'authorize' => 'authorize', // Example URI: api.vimeo.com/uas/oauth/authorize
	'request' => 'request_token',
	'access' => 'access_token',
	'login' => 'authenticate', // Like authorize, just auto-redirects
	'logout' => 'invalidate_token',
);
$config['Apis']['Vimeo']['read'] = array(
	// field
	'getAll' => array(
		// api method
		'vimeo.videos.getAll' => array(
			// required conditions
			// optional conditions the api call can take
			'optional' => array(
				'user_id',
				'full_response',
				'page',
				'per_page',
				'sort',
			)
		),
	),
);

$config['Apis']['Vimeo']['write'] = array(
);

$config['Apis']['Vimeo']['update'] = array(
);

$config['Apis']['Vimeo']['delete'] = array(
);
