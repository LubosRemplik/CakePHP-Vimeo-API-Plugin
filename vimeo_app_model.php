<?php
/**
 * Using vimeo datasource for all models extending this model
 * 
 * @author Luboš Remplík <lubos@lubos.me>
 * @link http://lubos.me
 * @copyright (c) 2011 Luboš Remplík
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 */
class VimeoAppModel extends AppModel {
	
	public $useDbConfig = 'vimeo';
	
	public $useTable = false;
	
	public $request = array();
	
	public function __construct($id = false, $table = null, $ds = null) {
		$sources = ConnectionManager::sourceList();
		if (!in_array('vimeo', $sources)) {
			ConnectionManager::create('vimeo', array('datasource' => 'Vimeo.VimeoSource'));
		}
		parent::__construct($id, $table, $ds);
	}
	
	public function setDataSourceConfig($config = array()) {
		$ds = $this->getDataSource($this->useDbConfig);
		if (!is_array($ds->config)) {
			$ds->config = array($ds->config);
		}
		$ds->config = array_merge($ds->config, $config);
		return $ds->config;
	}
}