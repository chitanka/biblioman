<?php namespace App\Controller;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Persistence\Manager;
use App\Persistence\RepositoryFinder;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends BaseController {

	const ITEMS_PER_PAGE = 24;

	/** @return Manager */
	protected function persistenceManager() {
		return $this->get('app.persistance_manager');
	}

	/** @return RepositoryFinder */
	protected function repoFinder() {
		return $this->get('app.repository_finder');
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
		$shelfRepo = $this->repoFinder()->forShelf();
		$shelfRepo->loadShelfAssociationForBooks($books);
		$shelves = $shelfRepo->findForUser($this->getUser());
		if ($shelves->isEmpty()) {
			$shelves = $shelfRepo->createShelves($this->getUser(), $this->getParameter('default_shelves'));
			$this->persistenceManager()->save($shelves);
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
