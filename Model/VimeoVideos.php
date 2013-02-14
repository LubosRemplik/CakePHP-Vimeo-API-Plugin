<?php
App::uses('VimeoApi', 'Vimeo.Model');
class VimeoVideos extends VimeoApi {

	public function __call($method, $params) {
		$params2 = array();
		if (!empty($params[0])) {
			$params2 = $params[0];
		}
		$request = array();
		if (!empty($params[1])) {
			$request = $params[1];
		}
		return $this->_request(sprintf('vimeo.videos.%s', $method), $params2, $request);
	}

	/**
	 * Returns list of videos, cached
	 * 
	 * Available options are user_id, limit, sort
	 **/
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
			$data = $this->getAll($params);
			if(!$data) return false;
			$results = $results + Set::combine($data, 'videos.video.{n}.id', 'videos.video.{n}.title');
			$params['page'] += 1;
			if ((count($results) == $data['videos']['total'])
			|| (isset($params['limit']) && $params['limit'] < 50)) {
				$finished = true;
			}
		}
		return $results;
	}

}
