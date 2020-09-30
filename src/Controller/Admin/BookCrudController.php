<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookCrudController extends AbstractCrudController {

	protected $baseImagePath = '/thumb/covers';

	/** @var Book */
	private $bookPreEdit;

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
		return $actions
			->disable(Action::DELETE)
			->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
			->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN)
			->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
			->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
		;
	}

	public function configureFields(string $pageName): iterable {
		$fields = $this->createFields($pageName);
		$this->putHelpMessagesFromWiki($fields);
		return $fields;
	}

	protected function createFields(string $pageName): iterable {
		$panelBasic = FormField::addPanel('Basic data')->setIcon('menu-icon fas fa-cog fa-fw');
		$media = ChoiceField::new('media')->setChoices(array_combine(Book::mediaValues(), Book::mediaValues()))->renderExpanded();
		$author = TextField::new('author');
		$title = TextField::new('title')->setTemplatePath('admin/Book/link.html.twig');
		$volumeTitle = TextField::new('volumeTitle');
		$subtitle = TextField::new('subtitle');
		$publisher = TextField::new('publisher');
		$publishingYear = TextField::new('publishingYear');
		$panelPaperData = FormField::addPanel('Data from paper')->setIcon('menu-icon fas fa-book fa-fw');
		$altTitle = TextField::new('altTitle');
		$subtitle2 = TextField::new('subtitle2');
		$sequence = TextField::new('sequence');
		$sequenceNr = TextField::new('sequenceNr');
		$subsequence = TextField::new('subsequence');
		$subsequenceNr = TextField::new('subsequenceNr');
		$series = TextField::new('series');
		$seriesNr = TextField::new('seriesNr');
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
		$annotation = TextareaField::new('annotation');
		$notesAboutAuthor = TextareaField::new('notesAboutAuthor');
		$marketingSnippets = TextareaField::new('marketingSnippets');
		$toc = TextareaField::new('toc');
		$notesAboutOriginal = TextareaField::new('notesAboutOriginal');
		$otherFields = TextareaField::new('otherFields');
		$panelNotes = FormField::addPanel('Notes')->setIcon('menu-icon far fa-sticky-note fa-fw');
		$notes = TextareaField::new('notes');
		$panelCategorization = FormField::addPanel('Categorization')->setIcon('menu-icon fas fa-tag fa-fw');
		$category = AssociationField::new('category');
		$genre = TextField::new('genre');
		$themes = TextField::new('themes');
		$universalDecimalClassification = TextField::new('universalDecimalClassification');
		$panelLinks = FormField::addPanel('Links')->setIcon('menu-icon fas fa-link fa-fw');
		$chitankaId = IntegerField::new('chitankaId');
		# by_reference : false => Needed to ensure that addLink() and removeLink() will be called during the flush.
		# See (last lines) : http://symfony.com/doc/master/reference/forms/types/collection.html#by-reference
		$links = CollectionField::new('links')->setEntryType(\App\Form\BookLinkType::class)->setFormTypeOptions(['by_reference' => false]);
		$panelCovers = FormField::addPanel('Covers')->setIcon('menu-icon far fa-images fa-fw');
		$coverFile = ImageField::new('coverFile')->setFormType(VichImageType::class);
		$backCoverFile = ImageField::new('backCoverFile')->setFormType(VichImageType::class);
		$otherCovers = CollectionField::new('otherCovers')->addCssClass('files')->setEntryType(\App\Form\BookCoverType::class)->setFormTypeOptions(['by_reference' => false]);;
		$panelFiles = FormField::addPanel('Files')->setIcon('menu-icon far fa-file-alt fa-fw');
		$fullContentFile = ImageField::new('fullContentFile')->setFormType(VichImageType::class);
		$availableAt = DateField::new('availableAt');
		$contentFiles = CollectionField::new('contentFiles')->addCssClass('files')->setEntryType(\App\Form\BookContentFileType::class)->setFormTypeOptions(['by_reference' => false]);
		$scans = CollectionField::new('scans')->addCssClass('files')->setEntryType(\App\Form\BookScanType::class)->setFormTypeOptions(['by_reference' => false]);;
		$panelMetadata = FormField::addPanel('Metadata')->setIcon('menu-icon far fa-folder-open fa-fw')->addCssClass('last-panel');
		$infoSources = TextareaField::new('infoSources');
		$adminComment = TextareaField::new('adminComment');
		$ocredText = TextareaField::new('ocredText');
		$hasOnlyScans = BooleanField::new('hasOnlyScans');
		$isIncomplete = BooleanField::new('isIncomplete');
		$reasonWhyIncomplete = TextField::new('reasonWhyIncomplete');
		$id = IntegerField::new('id', 'ID')->setTemplatePath('admin/Book/link.html.twig');
		$cover = ImageField::new('cover')->setBasePath($this->baseImagePath);
		$backCover = ImageField::new('backCover')->setBasePath($this->baseImagePath);

		if (Crud::PAGE_INDEX === $pageName) {
			return [$id, $cover, $backCover, $title, $author, $publisher, TextField::new('publishingYear', 'Publishing year short')];
		}
		if (Crud::PAGE_DETAIL === $pageName) {
			return [$id, $cover, $backCover, $title, $author, $publisher, $publishingYear];
		}
		if (Crud::PAGE_NEW === $pageName) {
			return [
				$panelBasic, $media,
				$panelPaperData, $author, $title, $volumeTitle, $subtitle, $publisher, $publishingYear,
			];
		}
		if (Crud::PAGE_EDIT === $pageName) {
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
				$chitankaId,
				$links,
				$panelCovers,
				$coverFile, $backCoverFile, $otherCovers,
				$panelFiles,
				$fullContentFile, $availableAt,
				$contentFiles,
				$scans,
				$panelMetadata,
				$infoSources, $adminComment, $ocredText, $hasOnlyScans, $isIncomplete, $reasonWhyIncomplete,
			];
		}
	}

	public function edit(AdminContext $context) {
		$this->denyAccessIfCannotEditBook($context);
		$this->checkForLockedBook($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $context->getEntity()->getInstance());
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

	protected function denyAccessIfCannotEditBook(AdminContext $context) {
		$user = $context->getUser();/* @var $use \App\Entity\User */
		if (!$user->canEditBook($context->getEntity()->getInstance())) {
			$context->getEntity()->markAsInaccessible();
		}
	}

	/** @param \EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface[] $fields */
	protected function putHelpMessagesFromWiki(iterable $fields) {
		$wiki = new \Chitanka\WikiBundle\Service\WikiEngine($this->getParameter('chitanka_wiki.content_dir'));
		$inflector = \Doctrine\Inflector\InflectorFactory::create()->build();
		foreach ($fields as $field) {
			$fieldDto = $field->getAsDto();
			$wikiPageName = str_replace('_', '-', $inflector->tableize($fieldDto->getProperty()));
			$page = $wiki->getPage("docs/books/$wikiPageName", false);
			if ($page->exists()) {
				$url = $this->generateUrl('chitanka_wiki_edit', ['page' => "docs/books/$wikiPageName"]);
				$fieldDto->setHelp($page->getContentHtml().' <a href="'.$url.'" tabindex="-1" class="wiki-edit-link"><span class="far fa-file-alt"></span></a>');
				$fieldDto->setCssClass($fieldDto->getCssClass(). ' field-with-help');
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

}
