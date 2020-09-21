<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return Book::class;
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud
			->setPageTitle(Crud::PAGE_INDEX, 'Books')
			->setPageTitle(Crud::PAGE_EDIT, 'Book.form_title')
			->setPageTitle(Crud::PAGE_DETAIL, 'Книга (№%entity_short_id%)')
			->setPageTitle(Crud::PAGE_NEW, 'Book.new_title')
			->setSearchFields(['id', 'lockedBy', 'createdBy', 'completedBy', 'author', 'translator', 'translatedFromLanguage', 'dateOfTranslation', 'adaptedBy', 'otherAuthors', 'compiler', 'media', 'format', 'pageCount', 'binding', 'themes', 'genre', 'trackingCode', 'litGroup', 'uniformProductClassification', 'universalDecimalClassification', 'isbn', 'isbnClean', 'contentType', 'nationality', 'language', 'notesAboutOriginal', 'annotation', 'notesAboutAuthor', 'marketingSnippets', 'toc', 'fullContent', 'cover', 'backCover', 'nbCovers', 'nbScans', 'nbContentFiles', 'sequence', 'sequenceNr', 'subsequence', 'subsequenceNr', 'series', 'seriesNr', 'otherFields', 'notes', 'infoSources', 'adminComment', 'ocredText', 'reasonWhyIncomplete', 'verifiedCount', 'printingHouse', 'typeSettingIn', 'printSigned', 'printOut', 'printerSheets', 'publisherSheets', 'provisionPublisherSheets', 'totalPrint', 'edition', 'publisher', 'publisherCity', 'publishingYear', 'publisherAddress', 'publisherCode', 'publisherOrder', 'publisherNumber', 'price', 'chiefEditor', 'managingEditor', 'editor', 'editorialStaff', 'publisherEditor', 'artistEditor', 'technicalEditor', 'consultant', 'scienceEditor', 'copyreader', 'reviewer', 'artist', 'illustrator', 'corrector', 'layout', 'coverLayout', 'libraryDesign', 'computerProcessing', 'prepress', 'title', 'altTitle', 'subtitle', 'subtitle2', 'volumeTitle', 'chitankaId']);
	}

	public function configureActions(Actions $actions): Actions
	{
		return $actions
			->disable('delete');
	}

	public function configureFields(string $pageName): iterable
	{
		$panel1 = FormField::addPanel('Basic data');
		$media = TextField::new('media');
		$author = TextField::new('author');
		$title = TextField::new('title')->setTemplatePath('admin/Book/link.html.twig');
		$volumeTitle = TextField::new('volumeTitle');
		$subtitle = TextField::new('subtitle');
		$publisher = TextField::new('publisher');
		$publishingYear = TextField::new('publishingYear');
		$panel2 = FormField::addPanel('Data from paper');
		$altTitle = TextField::new('altTitle');
		$subtitle2 = TextField::new('subtitle2');
		$sequence = TextField::new('sequence');
		$sequenceNr = IntegerField::new('sequenceNr');
		$subsequence = TextField::new('subsequence');
		$subsequenceNr = IntegerField::new('subsequenceNr');
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
		$illustrated = Field::new('illustrated');
		$isbn = TextField::new('isbn');
		$annotation = TextareaField::new('annotation');
		$notesAboutAuthor = TextareaField::new('notesAboutAuthor');
		$marketingSnippets = TextareaField::new('marketingSnippets');
		$toc = TextareaField::new('toc');
		$notesAboutOriginal = TextareaField::new('notesAboutOriginal');
		$otherFields = TextareaField::new('otherFields');
		$panel3 = FormField::addPanel('Notes');
		$notes = TextareaField::new('notes');
		$panel4 = FormField::addPanel('Categorization');
		$category = AssociationField::new('category');
		$genre = TextField::new('genre');
		$themes = TextField::new('themes');
		$universalDecimalClassification = TextField::new('universalDecimalClassification');
		$panel5 = FormField::addPanel('Links');
		$chitankaId = IntegerField::new('chitankaId');
		$links = Field::new('links');
		$panel6 = FormField::addPanel('Covers');
		$coverFile = Field::new('coverFile');
		$backCoverFile = Field::new('backCoverFile');
		$otherCovers = Field::new('otherCovers')->addCssClass('files');
		$panel7 = FormField::addPanel('Files');
		$fullContentFile = Field::new('fullContentFile');
		$availableAt = DateField::new('availableAt');
		$contentFiles = Field::new('contentFiles')->addCssClass('files');
		$scans = Field::new('scans')->addCssClass('files');
		$panel8 = FormField::addPanel('Metadata');
		$infoSources = TextareaField::new('infoSources');
		$adminComment = TextareaField::new('adminComment');
		$ocredText = TextareaField::new('ocredText');
		$hasOnlyScans = Field::new('hasOnlyScans');
		$isIncomplete = Field::new('isIncomplete');
		$reasonWhyIncomplete = TextField::new('reasonWhyIncomplete');
		$id = IntegerField::new('id', 'ID')->setTemplatePath('admin/Book/link.html.twig');
		$cover = ImageField::new('cover');
		$backCover = ImageField::new('backCover');

		if (Crud::PAGE_INDEX === $pageName) {
			return [$id, $cover, $backCover, $title, $author, $publisher, $publishingYear];
		}
		if (Crud::PAGE_DETAIL === $pageName) {
			return [$id, $cover, $backCover, $title, $author, $publisher, $publishingYear];
		}
		if (Crud::PAGE_NEW === $pageName) {
			return [
			$media, $author, $title, $volumeTitle, $subtitle, $publisher, $publishingYear];
		}
		if (Crud::PAGE_EDIT === $pageName) {
			return [
				#$panel1,
				$media,
				#$panel2,
				$author,
				$title,
				$altTitle,
				$volumeTitle,
				$subtitle,
				$subtitle2,
				$sequence,
				$sequenceNr,
				$subsequence,
				$subsequenceNr,
				$series,
				$seriesNr,
				$translator,
				$translatedFromLanguage,
				$dateOfTranslation,
				$adaptedBy,
				$otherAuthors,
				$compiler,
				$editorialStaff,
				$chiefEditor,
				$managingEditor,
				$editor,
				$publisherEditor,
				$consultant,
				$artist,
				$illustrator,
				$artistEditor,
				$technicalEditor,
				$reviewer,
				$scienceEditor,
				$copyreader,
				$corrector,
				$layout,
				$coverLayout,
				$libraryDesign,
				$computerProcessing,
				$prepress,
				$publisher,
				$publisherCity,
				$publishingYear,
				$publisherAddress,
				$printingHouse,
				$contentType,
				$nationality,
				$language,
				$edition,
				$litGroup,
				$typeSettingIn,
				$printSigned,
				$printOut,
				$printerSheets,
				$publisherSheets,
				$provisionPublisherSheets,
				$format,
				$publisherCode,
				$trackingCode,
				$publisherOrder,
				$publisherNumber,
				$uniformProductClassification,
				$pageCount,
				$totalPrint,
				$price,
				$binding,
				$illustrated,
				$isbn,
				$annotation,
				$notesAboutAuthor,
				$marketingSnippets,
				$toc,
				$notesAboutOriginal,
				$otherFields,
				#$panel3,
				$notes,
				#$panel4,
				$category,
				$genre,
				$themes,
				$universalDecimalClassification,
				#$panel5,
				$chitankaId,
				#$links,
				#$panel6,
				$coverFile,
				$backCoverFile,
				$otherCovers,
				#$panel7,
				$fullContentFile,
				$availableAt,
				#$contentFiles,
				#$scans,
				#$panel8,
				$infoSources,
				$adminComment,
				$ocredText,
				$hasOnlyScans,
				$isIncomplete,
				$reasonWhyIncomplete,
			];
		}
	}
}
