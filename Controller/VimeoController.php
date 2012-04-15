<?php
App::uses('AppController', 'Controller');
class VimeoController extends AppController {

	public $uses = array(
		'Vimeo.VimeoVideos',
	);

	public $components = array(
		'Apis.Oauth' => 'vimeo',
		'Encrypt.Decrypt'
	);

	public function connect($redirect = null) {
		$this->Oauth->connect(unserialize($this->Decrypt->hex2bin($redirect)));
	}

	public function vimeo_callback() {
		Cache::clear();
		$this->Oauth->callback();
	}
}
