<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookCrudController extends AbstractCrudController {

	protected $baseImagePath = 'https://biblioman.chitanka.info/thumb/covers';

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

		#$this->putHelpMessagesFromWiki();
	}

	public function configureActions(Actions $actions): Actions {
		return $actions
			->disable('delete');
	}

	public function configureFields(string $pageName): iterable {
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

	public function edit(\EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext $context) {
		$this->denyAccessIfCannotEditBook($context);
		return parent::edit($context);
	}

	protected function denyAccessIfCannotEditBook(\EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext $context) {
		$user = $context->getUser();/* @var $use \App\Entity\User */
		if (!$user->canEditBook($context->getEntity()->getInstance())) {
			$context->getEntity()->markAsInaccessible();
		}
	}
}
