<?php namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookMultiField;
use App\Entity\BookRevision;
use App\Entity\Query\BookQuery;
use App\Entity\User;
use App\Library\BookSearchCriteria;
use Doctrine\ORM\QueryBuilder;

class BookRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository {

	public function __construct(\Doctrine\Common\Persistence\ManagerRegistry $registry) {
		parent::__construct($registry, Book::class);
	}

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
		$b = $this->createQueryBuilder('b')->where('b.title = ?1')->setParameter('1', $title);
		if ($selfId) {
			$b->andWhere('b.id != ?2')->setParameter('2', $selfId);
		}
		return $b->getQuery()->getResult();
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
		$qb = $this->createQueryBuilder('b')->where('b.isIncomplete = 1');
		$qb->setParameter('1', Book::$MEDIA_PAPER);
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

	public function findAllSequences() {
		return $this->findAllForField('sequence');
	}

	public function findAllSeries() {
		return $this->findAllForField('series');
	}

	protected function findAllForField(string $field) {
		return array_column($this->createQueryBuilder('b')
			->select("b.$field")
			->distinct()
			->orderBy("b.$field")
			->getQuery()->getScalarResult(), $field);
	}

}
