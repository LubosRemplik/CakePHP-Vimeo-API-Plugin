<?php
App::uses('VimeoAppModel', 'Vimeo.Model');
class VimeoVideos extends VimeoAppModel {

	/**
	 * Returns all videos, cached
	 *
	 * http://vimeo.com/api/docs/methods/vimeo.videos.getAll
	 **/
	public function getAll($conditions = null) {
		$cacheKey = $this->_generateCacheKey('getAll', $conditions);
		if ($data = Cache::read($cacheKey) === false) {
			$data = $this->find('all', array(
				'fields' => 'getAll',
				'conditions' => $conditions
			));
			if ($data['stat'] == 'fail') {
				return false;
			}
			Cache::write($cacheKey, $data);
		}
		return $data;
	}

	/**
	 * Returns list of videos, cached
	 * 
	 * Uses getAll methot go get list of all videos with single array in 
	 * format id => title
	 * 
	 * Available options are user_id, limit, sort
	 **/
	public function getList($conditions = array()) {
		$allowed = array_flip(array('user_id', 'limit', 'sort'));
		$conditions = array_intersect_key($conditions, $allowed);
		$conditions['page'] = 1;
		if (isset($conditions['limit']) && $conditions['limit'] < 50) {
			$conditions['per_page'] = $conditions['limit'];
		}
		$results = array();
		$finished = false;
		while (!$finished) {
			$cacheKey = $this->_generateCacheKey('getList', $conditions);
			if ($data = Cache::read($cacheKey) === false) {
				$data = $this->getAll($conditions);
				Cache::write($cacheKey, $data);
			}
			if(!$data) return false;
			$results = am($results, Set::combine($data, 'videos.video.{n}.id', 'videos.video.{n}.title'));
			$conditions['page'] += 1;
			if ((count($results) == $data['videos']['total'])
			|| (isset($conditions['limit']) && $conditions['limit'] < 50)) {
				$finished = true;
			}
		}
		return $results;
	}

	protected function _generateCacheKey($fceName, $conditions = null) {
		$cacheKey = '';
		$cacheKey .= $this->alias;
		$cacheKey .= $fceName;
		if ($conditions) {
			$cacheKey .= md5(serialize($conditions));	
		}
		return $cacheKey;
	}
}
