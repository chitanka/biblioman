<?php namespace App\Entity\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookField\BookField;
use App\Entity\BookMultiField;
use App\Entity\BookRevision;
use App\Entity\Query\BookQuery;
use App\Entity\User;
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
			->andWhere('b.title = ?2')->setParameter('2', $title)
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

	public function filterIncomplete(BookSearchCriteria $criteria): QueryBuilder {
		$qb = $this->createQueryBuilder('b')->where('b.isIncomplete = 1 OR b.nbScans = 0');
		return (new BookQuery($this, $criteria, $qb))->getQueryBuilder();
	}

	/** @return BookCategoryRepository */
	private function getCategoryRepository() {
		return $this->_em->getRepository(BookCategory::class);
	}

	public function revisions() {
		return $this->getRevisionRepository()->allInReverse();
	}

	public function revisionsFromUser(User $user) {
		return $this->getRevisionRepository()->fromCreatorInReverse($user);
	}

	/** @return BookRevisionRepository */
	private function getRevisionRepository() {
		return $this->_em->getRepository(BookRevision::class);
	}

	/** @return BookMultiFieldRepository */
	public function getBookMultiFieldRepository() {
		return $this->_em->getRepository(BookMultiField::class);
	}
}
