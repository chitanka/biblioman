<?php namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitBookCategoriesCommand extends Command {

	public function getName() {
		return 'db:init-book-categories';
	}

	public function getDescription() {
		return 'Initialize the book categories in the database';
	}

	protected function getRequiredArguments() {
		return [
			'file' => 'A file returning an array',
		];
	}

	public function getHelp() {
		return 'The <info>%command.name%</info> command initializes the book categories from a given array.';
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$inputArray = require $input->getArgument('file');
		$em = $this->getEntityManager();
		$this->createTree($em, $inputArray);
		$em->flush();
		$output->writeln('Done.');
	}

	private function createTree(EntityManager $em, array $categoryArray, $parent = null) {
		foreach ($categoryArray as $categoryFields) {
			if (in_array($categoryFields['name'], ['@Некатегоризирани', 'Разкази в картинки'])) {
				continue;
			}
			$category = new \App\Entity\BookCategory();
			$category->setName($categoryFields['name']);
			$category->setSlug($categoryFields['slug']);
			if ($parent !== null) {
				$category->setParent($parent);
			}
			$em->persist($category);
			if (isset($categoryFields['children'])) {
				$this->createTree($em, $categoryFields['children'], $category);
			}
		}
	}
}
