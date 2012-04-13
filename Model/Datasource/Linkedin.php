<?php
/**
 * Vimeo DataSource
 * 
 * [Short Description]
 *
 * @package Vimeo Plugin
 * @author Dean Sofer
 **/
App::uses('ApisSource', 'Apis.Model/Datasource');
class Vimeo extends ApisSource {
	
	/**
	 * The description of this data source
	 *
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
	
	/**
	 * Lets you use the fields in Model::find() for vimeo
	 *
	 * @param string $model 
	 * @param string $queryData 
	 * @return void
	 * @author Dean Sofer
	 */
	public function read($model, $queryData = array()) {
		$path = '';
		if (!isset($model->request)) {
			$model->request = array();
		}
		if (isset($model->request['uri']['path'])) {
			$path = $model->request['uri']['path'];
		} elseif (!empty($queryData['path'])) {
			$path = $queryData['path'];
		}
		$model->request['uri']['path'] = $path . $this->fieldSelectors($queryData['fields']);
		
		return parent::read($model, $queryData);
	}
	
	/**
	 * Sets method = POST in request if not already set
	 *
	 * @param AppModel $model
	 * @param array $fields Unused
	 * @param array $values Unused
	 */
	public function create($model, $fields = null, $values = null) {
		$data = array_combine($fields, $values);
		$data = json_encode($data);
		$model->request['body'] = $data;
		$model->request['header']['content-type'] = 'application/json';
		$fields = $values = null;
		return parent::create($model, $fields, $values);
	}
	
	/**
	 * Formats an array of fields into the url-friendly nested format
	 *
	 * @param array $fields 
	 * @return string $fields
	 * @link http://developer.vimeo.com/docs/DOC-1014
	 */
	public function fieldSelectors($fields = array()) {
		$result = '';
		if (!empty($fields)) {
			if (is_array($fields)) {
				foreach ($fields as $group => $field) {
					if (is_string($group)) {
						$fields[$group] = $group . $this->fieldSelectors($field);
					}
				}
				$fields = implode(',', $fields);
			}
			$result .= ':(' . $fields . ')';
		}
		return $result;
	}

	/**
	 * Just-In-Time callback for any last-minute request modifications
	 *
	 * @param object $model 
	 * @param array $request 
	 * @return array $request
	 * @author Dean Sofer
	 */
	public function beforeRequest($model, $request) {
		$request['header']['x-li-format'] = $this->options['format'];
		return $request;
	}
}