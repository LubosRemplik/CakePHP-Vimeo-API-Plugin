<?php
App::import('Datasource', 'Rest.RestSource');

/**
 * CakePHP Datasource for accessing the Vimeo API
 * http://vimeo.com/api/
 *
 * @author Luboš Remplík <lubos@lubos.me>
 * @link http://lubos.me
 * @copyright (c) 2011 Luboš Remplík
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */
class VimeoSource extends RestSource {
	
	/**
	 * If no config is passed into the constructor, i.e. the config is not in
	 * app/config/database.php check if any config is in the config directory of
	 * the plugin, or in the configure class and use that instead.
	 *
	 * @param array $config
	 */
	public function __construct($config = null) {
		if (!is_array($config)) {
			$config = array();
		}
		
		// Default config
		$defaults = array(
			'datasource' => 'Vimeo.VimeoSource',
		);
		
		// Try and import the plugins/vimeo/config/vimeo_config.php file and
		// merge the config with the defaults above
		if (App::import(array('type' => 'File', 'name' => 'Vimeo.VIMEO_CONFIG', 'file' => 'config'.DS.'vimeo_config.php'))) {
			$VIMEO_CONFIG = new VIMEO_CONFIG();
			if (isset($VIMEO_CONFIG->vimeo)) {
				$defaults = array_merge($defaults, $VIMEO_CONFIG->vimeo);
			}
		}
		
		// Add any config from Configure class that you might have added at any
		// point before the model is instantiated.
		if (($configureConfig = Configure::read('Vimeo')) != false) {
			$defaults = array_merge($defaults, $configureConfig);
		}
		
		$config = array_merge($defaults, $config);
		
		App::import('Vendor', 'HttpSocketOauth');
		parent::__construct($config, new HttpSocketOauth());
	}
	
	/**
	 * Adds in common elements to the request such as the host and extension
	 *
	 * @param AppModel $model The model the operation is called on. Should have a
	 *  request property in the format described in HttpSocket::request
	 * @return mixed Depending on what is returned from RestSource::request()
	 */
	public function request(&$model) {
		// If auth key is set and not false, fill the request with auth params from
		// config if not already present in the request and set the method to OAuth
		// to trigger HttpSocketOauth to sign the request
		if (array_key_exists('auth', $model->request) && $model->request['auth'] !== false) {
			if (!is_array($model->request['auth'])) {
				$model->request['auth'] = array();
			}
			if (!isset($model->request['auth']['method'])) {
				$model->request['auth']['method'] = 'OAuth';
			}
			$oAuthParams = array(
				'oauth_consumer_key',
				'oauth_consumer_secret',
				'oauth_token',
				'oauth_token_secret',
			);
			foreach ($oAuthParams as $oAuthParam) {
				if (!isset($model->request['auth'][$oAuthParam])) {
					$model->request['auth'][$oAuthParam] = $this->config[$oAuthParam];
				}
			}
		}

		if (!isset($model->request['uri']['host'])) {
			$model->request['uri']['host'] = 'vimeo.com';
		}

		if (!isset($model->request['uri']['path'])) {
			$model->request['uri']['path'] = '/api/rest/v2';
		}

		if (!isset($model->request['uri']['query']['format'])) {
			$model->request['uri']['query']['format'] = 'json';
		}
		
		// Get the response from calling request on the Rest Source (it's parent)
		$response = parent::request($model);
		return $response;
	}
}