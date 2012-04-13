<?php
/**
 * Vimeo DataSource
 **/
App::uses('ApisSource', 'Apis.Model/Datasource');
class Vimeo extends ApisSource {
	
	/**
	 * The description of this data source
	 * @var string
	 */
	public $description = 'Vimeo DataSource Driver';
	
	/**
	 * Set the datasource to use OAuth
	 *
	 * @param array $config
	 * @param HttpSocket $Http
	 */
	public function __construct($config) {
		$config['method'] = 'OAuth';
		parent::__construct($config);
	}
	
	public function read(&$model, $queryData = array()) {
		if (!isset($model->request)) {
			$model->request = array();
		}
		$model->request = array_merge(array('method' => 'GET'), $model->request);
		if (empty($model->request['uri']['path']) && !empty($queryData['path'])) {
			$model->request['uri']['path'] = $queryData['path'];
		} elseif (!empty($this->map['read']) && is_string($queryData['fields'])) {
			if (!isset($queryData['conditions'])) {
				$queryData['conditions'] = array();
			}
			$scan = $this->scanMap($model, 'read', $queryData['fields'], array_keys($queryData['conditions']));
			$model->request['uri']['query'] = array();
			$model->request['uri']['query']['method'] = $scan[0];
			$usedConditions = array_intersect(array_keys($queryData['conditions']), array_merge($scan[1], $scan[2]));
			foreach ($usedConditions as $condition) {
				$model->request['uri']['query'][$condition] = $queryData['conditions'][$condition];
			}
		}
		return $this->request($model);
	}

	public function beforeRequest($model, $request) {
		$request['uri']['query']['format'] = $this->options['format'];
		return $request;
	}
}
