<?php namespace App\Library;

use App\Entity\Book;
use App\Entity\Shelf;
use App\Entity\User;
use App\Persistence\Manager;
use App\Persistence\RepositoryFinder;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;

class ShelfStore {

	private $persistenceManager;
	private $repoFinder;

	public function __construct(Manager $manager, RepositoryFinder $repoFinder) {
		$this->persistenceManager = $manager;
		$this->repoFinder = $repoFinder;
	}

	/**
	 * @param array|\Traversable|Book[] $books
	 * @param FormBuilder $builder
	 * @param User $user
	 * @param array $defaultShelvesDefinition
	 * @return FormView[]
	 */
	public function createAddToShelfForms($books, FormBuilder $builder, User $user, array $defaultShelvesDefinition) {
		if ($user === null) {
			return null;
		}
		$shelves = $this->findShelvesForUser($user, $defaultShelvesDefinition);
		$builder->add('shelves', ChoiceType::class, [
			'choices' => $shelves->toChoices(),
			'choice_label' => function(Shelf $shelf) { return $shelf->getName(); },
			'choice_value' => function(Shelf $shelf) { return $shelf->getId(); },
			'choice_attr' => function(Shelf $shelf) {
				return ['data-icon' => $shelf->getIcon()];
			},
			'multiple' => true,
			'choice_translation_domain' => false,
			'preferred_choices' => function(Shelf $shelf) { return $shelf->isImportant(); },
		]);
		$this->repoFinder->forShelf()->loadShelfAssociationForBooks($books);
		$addToShelfForms = [];
		foreach ($books as $book) {
			$addToShelfForms[$book->getId()] = $builder->getForm()->createView();
		}
		return $addToShelfForms;
	}

	/**
	 * Find user shelves. Create default shelves if there aren’t any.
	 * @param User $user
	 * @param array $defaultShelvesDefinition
	 * @return \App\Collection\ShelfCollection
	 */
	private function findShelvesForUser(User $user, array $defaultShelvesDefinition) {
		$shelfRepo = $this->repoFinder->forShelf();
		$shelves = $shelfRepo->findForUser($user);
		if ($shelves->isEmpty()) {
			$shelves = $shelfRepo->createShelves($user, $defaultShelvesDefinition);
			$this->persistenceManager->save($shelves);
		}
		return $shelves;
	}

	public function putBookOnShelf(Book $book, Shelf $shelf) {
		if ($this->repoFinder->forShelf()->hasBookOnShelf($book, $shelf)) {
			return false;
		}
		$shelf->addBook($book);
		$this->persistenceManager->save($shelf);
		return true;
	}

	public function removeBookFromShelf(Book $book, Shelf $shelf) {
		$bookOnShelf = $this->repoFinder->forShelf()->findBookOnShelf($book, $shelf);
		if (!$bookOnShelf) {
			return false;
		}
		$shelf->removeBook($bookOnShelf);
		$this->persistenceManager->save($shelf);
		return true;
	}

	public function userCanHaveShelves(User $user) {
		return !$user->isAnonymous();
	}

	public function userCanViewShelf(User $user, Shelf $shelf) {
		return $shelf->isPublic() || $shelf->getCreator()->equals($user);
	}

	public function userCanEditShelf(User $user, Shelf $shelf) {
		return $shelf->getCreator()->equals($user);
	}

	public function userOwnsShelf(User $user, Shelf $shelf) {
		return $shelf->getCreator()->equals($user);
	}

	public function createShelf(User $user) {
		return new Shelf($user);
	}

	public function saveShelf(Shelf $shelf) {
		$this->persistenceManager->save($shelf);
	}

	public function deleteShelf(Shelf $shelf) {
		$this->persistenceManager->delete($shelf);
	}

	public function showUserShelves(User $user, $group = null) {
		return $this->repoFinder->forShelf()->forUser($user, $group);
	}

	public function showPublicShelves($group = null) {
		return $this->repoFinder->forShelf()->isPublic($group);
	}
}
