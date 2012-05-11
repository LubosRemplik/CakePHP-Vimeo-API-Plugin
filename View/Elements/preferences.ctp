<?php
$connectUrl = array(
	'plugin' => 'vimeo', 'controller' => 'vimeo',
	'action' => 'connect'
);
if (isset($url)) {
	$url = serialize($url);
	$url = bin2hex($url);
	$connectUrl[] = $url;
}
$connectLink = $this->Html->link('Connect with Vimeo', $connectUrl);
echo $this->Form->inputs(array(
	'legend' => 'Vimeo',
	'vimeo_oauth_token' => array(
		'type' => 'text',
		'label' => 'OAuth Token',
		'value' => $this->Session->read('OAuth.vimeo.oauth_token')
	),
	'vimeo_oauth_token_secret' => array(
		'type' => 'text',
		'label' => 'OAuth Token Secret',
		'value' => $this->Session->read('OAuth.vimeo.oauth_token_secret'),
		'after' => 
			$this->Html->tag('br /').
			$this->Html->div('note', $connectLink)
	),
	'vimeo_album' => array(
		'label' => 'Album'
	),
));
