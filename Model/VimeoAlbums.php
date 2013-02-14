<?php
// test
App::uses('VimeoApi', 'Vimeo.Model');
class VimeoAlbums extends VimeoApi {

	public function __call($method, $params) {
		$params2 = array();
		if (!empty($params[0])) {
			$params2 = $params[0];
		}
		$request = array();
		if (!empty($params[1])) {
			$request = $params[1];
		}
		return $this->_request(sprintf('vimeo.albums.%s', $method), $params2, $request);
	}

	/**
	 * Returns list of albums
	 * 
	 * Available params are user_id, limit, sort
	 **/
	public function getList($params = array(), $request = array()) {
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
			$results = $results + Set::combine(
				$data, 'albums.album.{n}.id', 
				'albums.album.{n}.title'
			);
			$params['page'] += 1;
			if ((count($results) == $data['albums']['total'])
			|| (isset($params['limit']) && $params['limit'] < 50)) {
				$finished = true;
			}
		}
		return $results;
	}

	/**
	 * Returns all videos by album title
	 **/
	public function getVideosByTitle($title, $params = array()) {
		$list = $this->getList();
		if (!$list) {
			return false;
		}
		$albumID = false;
		foreach ($list as $key => $value) {
			if ($title == $value) {
				$albumID = $key;
			}
		}
		if (!$albumID) {
			return false;
		}
		$params['album_id'] = $albumID;
		return $this->getVideos($params);
	}

	/**
	 * Returns list of videos by album title
	 **/
	public function getVideosListByTitle($title, $params = array()) {
		$allowed = array_flip(array('limit', 'sort'));
		$params = array_intersect_key($params, $allowed);
		$params['page'] = 1;
		if (isset($params['limit']) && $params['limit'] < 50) {
			$params['per_page'] = $params['limit'];
		}
		$results = array();
		$finished = false;
		while (!$finished) {
			$data = $this->getVideosByTitle($title, $params);
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
