<?php
class VimeoHelper extends AppHelper {

	public $helpers = array('Html', 'Form');

	/* $options:
	*	album - set album to show
	*	url - url fot connect link back addres
	*	optionOnly - if true, only options returned
	*/
	public function input($options = array()) {
		extract($options);
		if (empty($album)) {
			$album = AppPreference::get('vimeo_album');
		}
		$videos = ClassRegistry::init('Vimeo.VimeoAlbums')->getVideosListByTitle($album);

		$connectUrl = array(
			'plugin' => 'vimeo', 'controller' => 'vimeo',
			'action' => 'connect'
		);
		if (isset($url)) {
			$url = serialize($url);
			$url = bin2hex($url);
			$connectUrl[] = $url;
		}

		$options = array(
			'type' => 'select',
			'options' => isset($videos) ? $videos : null,
			'empty' => 'Select',
			'between' => 
				$this->Html->div('note', "please ".$this->Html->link('connect', $connectUrl)
				." CMS with your Vimeo account to see list with your videos"),
		);
		if (isset($optionsOnly) && $optionsOnly) {
			return $options;
		} else {
			return $this->Form->inputs(array(
				'legend' => __('Video (*optional)'),
				'video_id' => $options 
			));
		}
	}
}
