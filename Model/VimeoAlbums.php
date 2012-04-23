<?php
App::uses('VimeoAppModel', 'Vimeo.Model');
class VimeoAlbums extends VimeoAppModel {

	/**
	 * Returns all albums, cached
	 *
	 * http://vimeo.com/api/docs/methods/vimeo.albums.getAll
	 **/
	public function getAll($conditions = null) {
		$cacheKey = $this->_generateCacheKey('getAll', $conditions);
		if (($data = Cache::read($cacheKey)) === false) {
			$data = $this->find('all', array(
				'fields' => 'albums.getAll',
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
	 * Returns list of albums, cached
	 * 
	 * Uses getAll methot go get list of all albums with single array in 
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
			if (($data = Cache::read($cacheKey)) === false) {
				$data = $this->getAll($conditions);
				Cache::write($cacheKey, $data);
			}
			if(!$data) return false;
			$results = $results + Set::combine($data, 'albums.album.{n}.id', 'albums.album.{n}.title');
			$conditions['page'] += 1;
			if ((count($results) == $data['albums']['total'])
			|| (isset($conditions['limit']) && $conditions['limit'] < 50)) {
				$finished = true;
			}
		}
		return $results;
	}

	/**
	 * Returns all videos from album, cached
	 *
	 * http://vimeo.com/api/docs/methods/vimeo.albums.getVideos
	 **/
	public function getVideos($conditions) {
	//debug($conditions);die;
		$cacheKey = $this->_generateCacheKey('getVideos', $conditions);
		if (($data = Cache::read($cacheKey)) === false) {
			$data = $this->find('all', array(
				'fields' => 'albums.getVideos',
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
	 * Returns all videos by album title
	 **/
	public function getVideosByTitle($title, $conditions = array()) {
		$list = $this->getList();
		$albumID = false;
		foreach ($list as $key => $value) {
			if ($title == $value) {
				$albumID = $key;
			}
		}
		if (!$albumID) {
			return false;
		}
		$conditions['album_id'] = $albumID;
		return $this->getVideos($conditions);
	}

	/**
	 * Returns list of videos by album title, cached
	 **/
	public function getVideosListByTitle($title, $conditions = array()) {
		$allowed = array_flip(array('limit', 'sort'));
		$conditions = array_intersect_key($conditions, $allowed);
		$conditions['page'] = 1;
		if (isset($conditions['limit']) && $conditions['limit'] < 50) {
			$conditions['per_page'] = $conditions['limit'];
		}
		$results = array();
		$finished = false;
		while (!$finished) {
			$cacheKey = $this->_generateCacheKey(
				'getVideosListByTitle', $conditions
			);
			if (($data = Cache::read($cacheKey)) === false) {
				$data = $this->getVideosByTitle($title, $conditions);
				Cache::write($cacheKey, $data);
			}
			if(!$data) return false;
			$results = $results + Set::combine($data, 'videos.video.{n}.id', 'videos.video.{n}.title');
			$conditions['page'] += 1;
			if ((count($results) == $data['videos']['total'])
			|| (isset($conditions['limit']) && $conditions['limit'] < 50)) {
				$finished = true;
			}
		}
		return $results;
	}
}
