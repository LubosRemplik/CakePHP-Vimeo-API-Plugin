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
		'getLikes' => true,
	);
	
	/**
	 * The options allowed by each of the custom find types
	 * 
	 * @var array
	 */
	public $allowedFindOptions = array(
		'getAll'  => array('full_response', 'page', 'per_page', 'sort', 'user_id'),
		'getLikes'  => array('full_response', 'page', 'per_page', 'sort', 'user_id'),
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

	public function getList($params = array()) {
		$allowed = array_flip(array('user_id', 'limit', 'sort'));
		$params = array_intersect_key($params, $allowed);
		$params['page'] = 1;
		if (isset($params['limit']) && $params['limit'] < 50) {
			$params['per_page'] = $params['limit'];
		}
		$results = array();
		$finished = false;
		while (!$finished) {
			$data = $this->find('getAll', $params);
			if(!$data) return false;
			$results = $results + Set::combine(
				$data, 
				'videos.video.{n}.id', 
				'videos.video.{n}.title'
			);
			$params['page'] += 1;
			if ((count($results) == $data['videos']['total'])
			|| (isset($params['limit']) && $params['limit'] < 50)) {
				$finished = true;
			}
		}
		asort($results);
		return $results;
	}

	public function getListLiked($params = array()) {
		$allowed = array_flip(array('user_id', 'limit', 'sort'));
		$params = array_intersect_key($params, $allowed);
		$params['page'] = 1;
		if (isset($params['limit']) && $params['limit'] < 50) {
			$params['per_page'] = $params['limit'];
		}
		$results = array();
		$finished = false;
		while (!$finished) {
			$data = $this->find('getLikes', $params);
			if(!$data) return false;
			$results = $results + Set::combine(
				$data, 
				'videos.video.{n}.id', 
				'videos.video.{n}.title'
			);
			$params['page'] += 1;
			if ((count($results) == $data['videos']['total'])
			|| (isset($params['limit']) && $params['limit'] < 50)) {
				$finished = true;
			}
		}
		asort($results);
		return $results;
	}
}
