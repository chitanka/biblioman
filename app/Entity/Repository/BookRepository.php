<?php namespace App\Entity\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookRevision;
use App\Entity\Query\BookQuery;
use App\Library\BookSearchCriteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends EntityRepository {

	/**
	 * @return QueryBuilder
	 */
	public function recent() {
		return $this->createQueryBuilder('b')
			->orderBy('b.createdAt', 'DESC');
	}

	/**
	 * @param string $title
	 * @param int $selfId
	 * @return Book[]
	 */
	public function findDuplicatesByTitle($title, $selfId) {
		return $this->createQueryBuilder('b')
			->where('b.id != ?1')->setParameter('1', $selfId)
			->andWhere('b.titling.title = ?2')->setParameter('2', $title)
			->getQuery()
			->getResult();
	}

	/**
	 * @param BookSearchCriteria $criteria
	 * @return QueryBuilder
	 */
	public function filterByCriteria(BookSearchCriteria $criteria) {
		return (new BookQuery($this, $criteria))->getQueryBuilder();
	}

	/**
	 * @param BookCategory $category
	 * @return QueryBuilder
	 */
	public function filterByCategory(BookCategory $category) {
		$qb = $this->createQueryBuilder('b')->where('b.category IN (:categories)');
		$categories = array_merge([$category], $this->getCategoryRepository()->children($category));
		$qb->setParameter('categories', $categories);
		return $qb;
	}

	/**
	 * @return QueryBuilder
	 */
	public function filterIncomplete() {
		return $this->createQueryBuilder('b')
			->where('b.isIncomplete = 1')
			->orWhere('b.nbScans = 0');
	}

	/** @return BookCategoryRepository */
	private function getCategoryRepository() {
		return $this->_em->getRepository(BookCategory::class);
	}

	public function revisions() {
		return $this->getRevisionRepository()->allInReverse();
	}

	/** @return BookRevisionRepository */
	private function getRevisionRepository() {
		return $this->_em->getRepository(BookRevision::class);
	}
}
