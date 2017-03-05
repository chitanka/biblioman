<?php namespace App\Http;

class Request extends \Symfony\Component\HttpFoundation\Request {

	const PARAM_PAGER_PAGE = 'page';
	const PARAM_SEARCH_QUERY = 'q';
	const PARAM_BOOK_SORT = 'sort';
	const PARAM_SHELF_GROUP = 'group';

	public function getPagerPage() {
		return $this->getFromQuery(self::PARAM_PAGER_PAGE, 1);
	}

	public function getSearchQuery() {
		return $this->getFromQuery(static::PARAM_SEARCH_QUERY);
	}

	public function getBookSort() {
		return $this->getFromQuery(static::PARAM_BOOK_SORT);
	}

	public function getShelfGroup() {
		return $this->getFromQuery(static::PARAM_SHELF_GROUP);
	}

	public function getCurrentRoute() {
		return $this->get('_route');
	}

	protected function getFromQuery($param, $default = null) {
		return $this->query->get($param, $default);
	}
}
