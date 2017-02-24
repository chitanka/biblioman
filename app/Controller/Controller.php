<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Repository\BookRepository;
use App\Repository\ShelfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends BaseController {

	const ITEMS_PER_PAGE = 24;

	/** @return EntityManager */
	protected function em() {
		return $this->getDoctrine()->getManager();
	}

	/** @return BookRepository */
	protected function bookRepo() {
		return $this->repo(Book::class);
	}

	/** @return ShelfRepository */
	protected function shelfRepo() {
		return $this->repo(Shelf::class);
	}

	protected function repo($repoClass) {
		return $this->em()->getRepository($repoClass);
	}

	protected function pager(Request $request, $query, $maxPerPage = null) {
		return $this->createPager(new DoctrineORMAdapter($query), $request, $maxPerPage);
	}

	protected function collectionPager(Request $request, Collection $collection, $maxPerPage = null) {
		return $this->createPager(new DoctrineCollectionAdapter($collection), $request, $maxPerPage);
	}

	/**
	 * @param Book[] $books
	 * @return FormView[]
	 */
	protected function createAddToShelfForms($books) {
		if (!$this->getUser()) {
			return null;
		}
		$this->shelfRepo()->fetchForBooks($books);
		$shelves = $this->shelfRepo()->findForUser($this->getUser());
		if ($shelves->isEmpty()) {
			$shelves = $this->shelfRepo()->createShelves($this->getUser(), $this->getParameter('default_shelves'));
			$this->save($shelves);
		}
		$choices = [];
		foreach ($shelves as $shelf) {
			$choices[$shelf->getGroup() ?: ''][] = $shelf;
		}
		$ungroupedChoices = $choices[''];
		unset($choices['']);
		$choices += ['' => $ungroupedChoices];
		$builder = $this->createFormBuilder();
		$builder->add('shelves', ChoiceType::class, [
			'choices' => $choices,
			'choice_label' => function(Shelf $shelf) { return $shelf->getName(); },
			'choice_value' => function(Shelf $shelf) { return $shelf->getId(); },
			'choice_attr' => function(Shelf $shelf) {
				return ['data-icon' => $shelf->getIcon()];
			},
			'multiple' => true,
			'choice_translation_domain' => false,
			'preferred_choices' => function(Shelf $shelf) { return $shelf->isImportant(); },
		]);
		$addToShelfForms = [];
		foreach ($books as $book) {
			$addToShelfForms[$book->getId()] = $builder->getForm()->createView();
		}
		return $addToShelfForms;
	}

	protected function save($entities) {
		if (!is_array($entities) && !$entities instanceof ArrayCollection) {
			$entities = [$entities];
		}
		$em = $this->em();
		foreach ($entities as $entity) {
			$em->persist($entity);
		}
		$em->flush();
	}

	protected function delete($entities) {
		if (!is_array($entities) && !$entities instanceof ArrayCollection) {
			$entities = [$entities];
		}
		$em = $this->em();
		foreach ($entities as $entity) {
			$em->remove($entity);
		}
		$em->flush();
	}

	protected function addSuccessFlash($message, $params = []) {
		$this->addFlash('success', $this->translate($message, $params));
	}

	protected function addErrorFlash($message, $params = []) {
		$this->addFlash('error', $this->translate($message, $params));
	}

	protected function translate($message, $params = []) {
		return $this->get('translator')->trans($message, $params);
	}

	private function createPager($adapter, Request $request, $maxPerPage) {
		$pager = new Pagerfanta($adapter);
		$pager->setMaxPerPage($maxPerPage ?: self::ITEMS_PER_PAGE);
		$pager->setCurrentPage($request->query->get('page', 1));
		return $pager;
	}
}
