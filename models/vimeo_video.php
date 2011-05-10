<?php
/**
 * http://vimeo.com/api/ 
 * 
 * @author Luboš Remplík <lubos@lubos.me>
 * @link http://lubos.me
 * @copyright (c) 2011 Luboš Remplík
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 */
class VimeoVideo extends VimeoAppModel {
		
	/**
	 * Custom find types available on this model
	 * 
	 * @var array
	 */
	public $_findMethods = array(
		'getAll' => true,
	);
	
	/**
	 * The options allowed by each of the custom find types
	 * 
	 * @var array
	 */
	public $allowedFindOptions = array(
		'getAll'  => array('full_response', 'page', 'per_page', 'sort', 'user_id'),
  	);
  	
	public function find($type, $options = array()) {
		$this->request['uri']['query']['method'] = 'vimeo.videos.' . $type;
		if (array_key_exists($type, $this->allowedFindOptions)) {
			$this->request['uri']['query'] = array_merge(
				$this->request['uri']['query'], 
				array_intersect_key($options, array_flip($this->allowedFindOptions[$type]))
			);
		}
		$this->request['auth'] = true;
		return parent::find('all', $options);
	}
}