<?php
$connectUrl = array(
	'plugin' => 'vimeo', 'controller' => 'vimeo',
	'action' => 'connect'
);
if (!isset($url)) {
	$url = array(
		'plugin' => 'app_admin', 'controller' => 'app_admin_preferences',
		'action' => 'index'
	);
}
$url = serialize($url);
$url = bin2hex($url);
$connectUrl[] = $url;
$connectLink = $this->Html->link('Connect with Vimeo', $connectUrl);
echo $this->Form->inputs(array(
	'legend' => 'Vimeo',
	'AppPreference.vimeo_oauth_token' => array(
		'type' => 'text',
		'label' => 'OAuth Token',
		'value' => $this->Session->read('OAuth.vimeo.oauth_token')
	),
	'AppPreference.vimeo_oauth_token_secret' => array(
		'type' => 'text',
		'label' => 'OAuth Token Secret',
		'value' => $this->Session->read('OAuth.vimeo.oauth_token_secret'),
		'after' => 
			$this->Html->tag('br /').
			$this->Html->div('note', $connectLink)
	),
	'AppPreference.vimeo_album' => array(
		'label' => 'Album'
	),
));
