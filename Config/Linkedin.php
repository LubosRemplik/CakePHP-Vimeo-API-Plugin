<?php
/**
 * A Vimeo API Method Map
 *
 * Refer to the apis plugin for how to build a method map
 * https://github.com/ProLoser/CakePHP-Api-Datasources
 *
 */
$config['Apis']['Vimeo']['hosts'] = array(
	'oauth' => 'api.vimeo.com/uas/oauth',
	'rest' => 'api.vimeo.com/v1',
);
// http://developer.vimeo.com/docs/DOC-1251
$config['Apis']['Vimeo']['oauth'] = array(
	'authorize' => 'authorize', // Example URI: api.vimeo.com/uas/oauth/authorize
	'request' => 'requestToken',
	'access' => 'accessToken',
	'login' => 'authenticate', // Like authorize, just auto-redirects
	'logout' => 'invalidateToken',
);
$config['Apis']['Vimeo']['read'] = array(
	// field
	'people' => array(
		// api url
		'people/id=' => array(
			// required conditions
			'id',
		),
		'people/url=' => array(
			'url',
		),
		'people/~' => array(),
	),
	'people-search' => array(
		'people-search' => array(
		// optional conditions the api call can take
			'optional' => array(
				'keywords',
				'first-name',
				'last-name',
				'company-name',
				'current-company',
				'title',
				'current-title',
				'school-name',
				'current-school',
				'country-code',
				'postal-code',
				'distance',
				'start',
				'count',
				'facet',
				'facets',
				'sort',
			),
		),
	),
);

$config['Apis']['Vimeo']['write'] = array(
	// http://developer.vimeo.com/docs/DOC-1044
	'mailbox' => array(
		'people/~/mailbox' => array(
			'subject',
			'body',
			'recipients',
		),
	),
);

$config['Apis']['Vimeo']['update'] = array(
);

$config['Apis']['Vimeo']['delete'] = array(
);