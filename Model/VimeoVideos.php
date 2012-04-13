<?php
App::uses('VimeoAppModel', 'Vimeo.Model');
class VimeoVideos extends VimeoAppModel {

	/**
	 * Returns all videos
	 *
	 * http://vimeo.com/api/docs/methods/vimeo.videos.getAll
	 **/
	public function getAll($conditions = null) {
		$data = $this->find('all', array(
			'fields' => 'getAll',
			'conditions' => $conditions
		));
		if ($data['stat'] == 'fail') {
			return false;
		}
		return $data;
	}

	/**
	 * Returns list of videos
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
			$data = $this->getAll($conditions);
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
}
