<?php namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

trait BookLinks {

	/**
	 * @var BookLink[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="BookLink", mappedBy="book", cascade={"persist","remove"}, orphanRemoval=true)
	 */
	private $links;

	/** @param BookLink[] $links */
	public function setLinks($links) { $this->links = $links; }

	public function addLink(BookLink $link) {
		if (!empty($link->getUrl())) {
			$link->setBook($this);
			$this->links[] = $link;
		}
	}

	public function removeLink(BookLink $link) {
		$this->links->removeElement($link);
	}

	/** @return BookLink[][] */
	public function getLinksByCategory() {
		$linksByCategory = [];
		foreach ($this->links as $link) {
			$linksByCategory[$link->getCategory()][] = $link;
		}
		$linksByCategorySorted = array_filter(array_replace(array_fill_keys(BookLink::$categories, null), $linksByCategory));
		return $linksByCategorySorted;
	}

}
