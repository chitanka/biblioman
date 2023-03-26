<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Repository\BookMultiFieldRepository;
use App\Repository\BookRepository;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookCrudController extends CrudController {

	protected $baseImagePath = '/thumb/covers';

	/** @var Book */
	private $bookPreEdit;
	/** @var BookRepository */
	private $bookRepository;
	private LabelRepository $labelRepository;
	/** @var TranslatorInterface|\Symfony\Component\Translation\DataCollectorTranslator */
	private $translator;

	public function __construct(BookRepository $bookRepository, LabelRepository $labelRepository, TranslatorInterface $translator) {
		$this->bookRepository = $bookRepository;
		$this->labelRepository = $labelRepository;
		$this->translator = $translator;
	}

	public static function getEntityFqcn(): string {
		return Book::class;
	}

	public function configureCrud(Crud $crud): Crud {
		return $crud
			->setPageTitle(Crud::PAGE_INDEX, 'Books')
			->setPageTitle(Crud::PAGE_EDIT, 'Book.form_title')
			->setPageTitle(Crud::PAGE_DETAIL, 'Книга (№%entity_short_id%)')
			->setPageTitle(Crud::PAGE_NEW, 'Book.new_title')
			->setSearchFields(['id', 'lockedBy', 'createdBy', 'completedBy', 'author', 'translator', 'translatedFromLanguage', 'dateOfTranslation', 'adaptedBy', 'otherAuthors', 'compiler', 'media', 'format', 'pageCount', 'binding', 'themes', 'genre', 'trackingCode', 'litGroup', 'uniformProductClassification', 'universalDecimalClassification', 'isbn', 'isbnClean', 'contentType', 'nationality', 'language', 'notesAboutOriginal', 'annotation', 'notesAboutAuthor', 'marketingSnippets', 'toc', 'fullContent', 'cover', 'backCover', 'nbCovers', 'nbScans', 'nbContentFiles', 'sequence', 'sequenceNr', 'subsequence', 'subsequenceNr', 'series', 'seriesNr', 'otherFields', 'notes', 'infoSources', 'adminComment', 'ocredText', 'reasonWhyIncomplete', 'verifiedCount', 'printingHouse', 'typeSettingIn', 'printSigned', 'printOut', 'printerSheets', 'publisherSheets', 'provisionPublisherSheets', 'totalPrint', 'edition', 'publisher', 'publisherCity', 'publishingYear', 'publisherAddress', 'publisherCode', 'publisherOrder', 'publisherNumber', 'price', 'chiefEditor', 'managingEditor', 'editor', 'editorialStaff', 'publisherEditor', 'artistEditor', 'technicalEditor', 'consultant', 'scienceEditor', 'copyreader', 'reviewer', 'artist', 'illustrator', 'corrector', 'layout', 'coverLayout', 'libraryDesign', 'computerProcessing', 'prepress', 'title', 'altTitle', 'subtitle', 'subtitle2', 'volumeTitle', 'chitankaId'])
			->setDefaultSort(['id' => 'DESC']);
	}

	public function configureActions(Actions $actions): Actions {
		$bookInFrontend = Action::new('Cancel', null, 'fa fa-close')->linkToRoute('books_show', function (Book $book) {
			return ['id' => $book->getId()];
		});
		return $actions
			->disable(Action::DELETE)
			->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
			->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
			->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
			->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
			->add(Crud::PAGE_EDIT, $bookInFrontend)
		;
	}

	public function configureFields(string $pageName): iterable {
		$fields = $this->createFields($pageName);
		$this->renderFieldsWithFullsize($fields);
		$this->putHelpMessagesFromWiki($fields);
		return $fields;
	}

	protected function createFields(string $pageName): iterable {
		$id = IntegerField::new('id', 'ID')->setTemplatePath('admin/Book/link.html.twig');
		$cover = ImageField::new('cover')->setBasePath($this->baseImagePath);
		$backCover = ImageField::new('backCover')->setBasePath($this->baseImagePath);
		$panelBasic = $this->panel('Basic data', 'fas fa-cog');
		$panelPaperData = $this->panel('Data from paper', 'fas fa-book');
		$media = ChoiceField::new('media')->setChoices(array_combine(Book::mediaValues(), Book::mediaValues()))->renderExpanded()->setFormTypeOptions(['choice_translation_domain' => false]);
		$title = TextField::new('title')->setTemplatePath('admin/Book/link.html.twig');
		$volumeTitle = TextField::new('volumeTitle');
		$subtitle = TextField::new('subtitle');
		$author = TextField::new('author');
		$publisher = TextField::new('publisher');
		$publishingYear = TextField::new('publishingYear');
		if (Crud::PAGE_INDEX === $pageName) {
			return [$id, $cover, $backCover, $title, $author, $publisher, TextField::new('publishingYear', 'Publishing year short')];
		}
		if (Crud::PAGE_NEW === $pageName) {
			return [
				$panelBasic, $media,
				$panelPaperData, $author, $title, $volumeTitle, $subtitle, $publisher, $publishingYear,
			];
		}
		$bookFromRequest = $this->get('request_stack')->getMasterRequest()->request->get('Book');
		$altTitle = TextField::new('altTitle');
		$subtitle2 = TextField::new('subtitle2');
		$sequence = $this->choiceWithSelect2('sequence', $this->bookRepository->findAllSequences(), $bookFromRequest['sequence'] ?? null);
		$sequenceNr = Field::new('sequenceNr');
		$subsequence = TextField::new('subsequence');
		$subsequenceNr = Field::new('subsequenceNr');
		$series = $this->choiceWithSelect2('series', $this->bookRepository->findAllSeries(), $bookFromRequest['series'] ?? null);
		$seriesNr = Field::new('seriesNr');
		$translator = TextField::new('translator');
		$translatedFromLanguage = TextField::new('translatedFromLanguage');
		$dateOfTranslation = TextField::new('dateOfTranslation');
		$adaptedBy = TextField::new('adaptedBy');
		$otherAuthors = TextField::new('otherAuthors');
		$compiler = TextField::new('compiler');
		$editorialStaff = TextField::new('editorialStaff');
		$chiefEditor = TextField::new('chiefEditor');
		$managingEditor = TextField::new('managingEditor');
		$editor = TextField::new('editor');
		$publisherEditor = TextField::new('publisherEditor');
		$consultant = TextField::new('consultant');
		$artist = TextField::new('artist');
		$illustrator = TextField::new('illustrator');
		$artistEditor = TextField::new('artistEditor');
		$technicalEditor = TextField::new('technicalEditor');
		$reviewer = TextField::new('reviewer');
		$scienceEditor = TextField::new('scienceEditor');
		$copyreader = TextField::new('copyreader');
		$corrector = TextField::new('corrector');
		$layout = TextField::new('layout');
		$coverLayout = TextField::new('coverLayout');
		$libraryDesign = TextField::new('libraryDesign');
		$computerProcessing = TextField::new('computerProcessing');
		$prepress = TextField::new('prepress');
		$publisherCity = TextField::new('publisherCity');
		$publisherAddress = TextField::new('publisherAddress');
		$printingHouse = TextField::new('printingHouse');
		$contentType = TextField::new('contentType');
		$nationality = TextField::new('nationality');
		$language = TextField::new('language');
		$edition = TextField::new('edition');
		$litGroup = TextField::new('litGroup');
		$typeSettingIn = TextField::new('typeSettingIn');
		$printSigned = TextField::new('printSigned');
		$printOut = TextField::new('printOut');
		$printerSheets = TextField::new('printerSheets');
		$publisherSheets = TextField::new('publisherSheets');
		$provisionPublisherSheets = TextField::new('provisionPublisherSheets');
		$format = TextField::new('format');
		$publisherCode = TextField::new('publisherCode');
		$trackingCode = TextField::new('trackingCode');
		$publisherOrder = TextField::new('publisherOrder');
		$publisherNumber = TextField::new('publisherNumber');
		$uniformProductClassification = TextField::new('uniformProductClassification');
		$pageCount = IntegerField::new('pageCount');
		$totalPrint = TextField::new('totalPrint');
		$price = TextField::new('price');
		$binding = TextField::new('binding');
		$illustrated = BooleanField::new('illustrated');
		$isbn = TextField::new('isbn');
		$annotation = $this->textarea('annotation');
		$notesAboutAuthor = $this->textarea('notesAboutAuthor');
		$marketingSnippets = $this->textarea('marketingSnippets');
		$toc = $this->textarea('toc');
		$notesAboutOriginal = $this->textarea('notesAboutOriginal');
		$otherFields = $this->textarea('otherFields');
		$panelNotes = $this->panel('Notes', 'far fa-sticky-note');
		$notes = $this->textarea('notes');
		$panelCategorization = $this->panel('Categorization', 'fas fa-tag');
		$category = AssociationField::new('category');
		$genre = $this->multipleChoiceWithSelect2('genre', $this->labelRepository->findAllGenres(), $bookFromRequest['genre'] ?? null, false);
		$themes = $this->multipleChoiceWithSelect2('themes', $this->labelRepository->findAllCharacteristics(), $bookFromRequest['themes'] ?? null, false);
		$universalDecimalClassification = TextField::new('universalDecimalClassification');
		$panelLinks = $this->panel('Links', 'fas fa-link');
		$chitankaId = IntegerField::new('chitankaId');
		$atelieId = IntegerField::new('atelieId');
		$links = $this->collectionField('links', \App\Form\BookLinkType::class);
		$panelCovers = $this->panel('Covers', 'far fa-images');
		$coverFile = $this->uploadField('coverFile');
		$backCoverFile = $this->uploadField('backCoverFile');
		$otherCovers = $this->collectionField('otherCovers', \App\Form\BookCoverType::class);
		$panelFiles = $this->panel('Files', 'far fa-file-alt');
		$fullContentFile = $this->uploadField('fullContentFile');
		$availableAt = DateField::new('availableAt');
		$isPublic = BooleanField::new('isPublic');
		$contentFiles = $this->collectionField('contentFiles', \App\Form\BookContentFileType::class);
		$scans = $this->collectionField('scans', \App\Form\BookScanType::class);
		$panelMetadata = $this->panel('Metadata', 'far fa-folder-open')->addCssClass('last-panel');
		$infoSources = $this->textarea('infoSources');
		$adminComment = $this->textarea('adminComment');
		$ocredText = $this->textarea('ocredText');
		$hasOnlyScans = BooleanField::new('hasOnlyScans');
		$isIncomplete = BooleanField::new('isIncomplete');
		$reasonWhyIncomplete = TextField::new('reasonWhyIncomplete');

		return [
			$panelBasic,
			$media,
			$panelPaperData,
			$author,
			$title, $altTitle, $volumeTitle, $subtitle, $subtitle2,
			$sequence, $sequenceNr, $subsequence, $subsequenceNr, $series, $seriesNr,
			$translator, $translatedFromLanguage, $dateOfTranslation,
			$adaptedBy, $otherAuthors, $compiler,
			$editorialStaff, $chiefEditor, $managingEditor, $editor, $publisherEditor,
			$consultant,
			$artist, $illustrator, $artistEditor,
			$technicalEditor, $reviewer, $scienceEditor, $copyreader, $corrector,
			$layout, $coverLayout, $libraryDesign, $computerProcessing, $prepress,
			$publisher, $publisherCity, $publishingYear, $publisherAddress,
			$printingHouse,
			$contentType,
			$nationality, $language,
			$edition,
			$litGroup,
			$typeSettingIn,
			$printSigned, $printOut,
			$printerSheets, $publisherSheets, $provisionPublisherSheets,
			$format,
			$publisherCode, $trackingCode,
			$publisherOrder, $publisherNumber,
			$uniformProductClassification,
			$pageCount, $totalPrint, $price, $binding, $illustrated,
			$isbn,
			$annotation, $notesAboutAuthor, $marketingSnippets, $toc, $notesAboutOriginal,
			$otherFields,
			$panelNotes,
			$notes,
			$panelCategorization,
			$category, $genre, $themes, $universalDecimalClassification,
			$panelLinks,
			$chitankaId, $atelieId,
			$links,
			$panelCovers,
			$coverFile, $backCoverFile, $otherCovers,
			$panelFiles,
			$fullContentFile, $availableAt, $isPublic,
			$contentFiles,
			$scans,
			$panelMetadata,
			$infoSources, $adminComment, $ocredText, $hasOnlyScans, $isIncomplete, $reasonWhyIncomplete,
		];
	}

	public function edit(AdminContext $context) {
		$user = $context->getUser();/* @var $user \App\Entity\User */
		$book = $context->getEntity()->getInstance();/* @var $book Book */
		if (!$user->canEditBook($book)) {
			$this->addFlash('error', 'Нямате право да редактирате този запис.');
			return $this->redirectToRoute('books_show', ['id' => $book->getId()]);
		}
		$this->checkForLockedBook($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $book);
		return parent::edit($context);
	}

	public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface {
		$builder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
		$book = $entityDto->getInstance(); /* @var Book $book */
		if ($book->isLockedForUser($this->getUser()->getUsername())) {
			$builder->setDisabled(true);
		}
		return $builder;
	}

	public function createNewForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface {
		$form = parent::createNewForm($entityDto, $formOptions, $context);
		$request = $this->container->get('request_stack')->getCurrentRequest();/* @var \App\Http\Request $request */
		foreach ($request->query->all() as $param => $value) {
			if ($form->has($param)) {
				$form->get($param)->setData($value);
			}
		}
		return $form;
	}

	/**
	 * @Route("/admin/books/extend-lock/{id}")
	 */
	public function extendBookLock(EntityManagerInterface $entityManager, Book $book) {
		$book->disableUpdatedTracking();
		$book->extendLock();
		$entityManager->persist($book);
		$entityManager->flush();
		return new JsonResponse($book->toArray());
	}

	/** @param \EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface[] $fields */
	protected function putHelpMessagesFromWiki(iterable $fields) {
		$wiki = new \Chitanka\WikiBundle\Service\WikiEngine($this->getParameter('chitanka_wiki.content_dir'));
		$inflector = \Doctrine\Inflector\InflectorFactory::create()->build();
		$messageCatalogue = $this->translator->getCatalogue();
		foreach ($fields as $field) {
			$fieldDto = $field->getAsDto();
			$wikiPageName = str_replace('_', '-', $inflector->tableize($fieldDto->getProperty()));
			$page = $wiki->getPage("docs/books/$wikiPageName", false);
			if ($page->exists()) {
				$messageKey = $fieldDto->getProperty().'_help';
				$url = $this->generateUrl('chitanka_wiki_edit', ['page' => "docs/books/$wikiPageName"]);
				$messageCatalogue->set($messageKey, $page->getContentHtml().' <a href="'.$url.'" tabindex="-1" class="wiki-edit-link"><span class="far fa-file-alt"></span></a>');
				$fieldDto->setHelp($messageKey);
				$fieldDto->setCssClass($fieldDto->getCssClass(). ' field-with-help');
				$fieldDto->setFormTypeOption('help_html', true);
			}
		}
	}

	protected function checkForLockedBook(EntityManagerInterface $entityManager, Book $book) {
		$this->bookPreEdit = clone $book;
		if ($book->isLockedForUser($this->getUser()->getUsername())) {
			$this->addFlash('warning', 'В момента този запис се редактира от <a href="'.$this->generateUrl('users_show', ['username' => $book->getLockedBy()]).'">'.$book->getLockedBy().'</a>.');
		} else if ($book->isLockExpired()) {
			$book->disableUpdatedTracking();
			$book->setLock($this->getUser()->getUsername());
			$entityManager->persist($book);
			$entityManager->flush();
		}
	}

	/** @param Book $book */
	public function persistEntity(EntityManagerInterface $entityManager, $book): void {
		$book->setCurrentEditor($this->getUser());
		$book->setCreator($this->getUser());
		$book->setCreatedByUser($this->getUser());
		parent::persistEntity($entityManager, $book);
	}

	/** @param Book $book */
	public function updateEntity(EntityManagerInterface $entityManager, $book): void {
		$book->setCurrentEditor($this->getUser());
		if ($this->bookPreEdit) {
			$revision = $book->createRevisionIfNecessary($this->bookPreEdit, $this->getUser()->getUsername());
			if ($revision) {
				$entityManager->persist($revision);
			}
		}
		$book->setCreator($this->getUser());
		$book->clearLock();
		parent::updateEntity($entityManager, $book);
	}

	private function choiceWithSelect2(string $name, array $choices, $extraValue = null, bool $allowItemCreation = true) {
		if ($extraValue) {
			$choices = array_merge($choices, (array) $extraValue);
		}
		$options = ['choice_translation_domain' => false];
		if ($allowItemCreation) {
			$options['attr'] = ['data-ea-autocomplete-allow-item-create' => 'true'];
		}
		return ChoiceField::new($name)->setChoices(array_combine($choices, $choices))->setFormTypeOptions($options);
	}

	private function multipleChoiceWithSelect2(string $name, array $choices, $extraValue = null, bool $allowItemCreation = true) {
		return $this->choiceWithSelect2($name, $choices, $extraValue, $allowItemCreation)->allowMultipleChoices();
	}

	private function textarea(string $name) {
		return TextareaField::new($name)->setNumOfRows(1)
			->setFormTypeOption('attr.data-ea-textarea-field', false) // disable autogrow
		;
	}

	private function uploadField(string $name) {
		return TextField::new($name)->setFormType(VichImageType::class);
	}

	private function collectionField(string $name, string $class) {
		# by_reference : false => Needed to ensure that addLink() and removeLink() will be called during the flush.
		# See (last lines) : http://symfony.com/doc/master/reference/forms/types/collection.html#by-reference
		return CollectionField::new($name)->setEntryType($class)->setFormTypeOptions(['by_reference' => false]);
	}

	private function panel(string $name, string $icon) {
		return FormField::addPanel($name, 'fa-fw '.$icon)->collapsible();
	}
}
