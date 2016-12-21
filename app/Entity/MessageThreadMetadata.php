<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * @ORM\Entity
 */
class MessageThreadMetadata extends BaseThreadMetadata {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(
	 *   targetEntity="App\Entity\MessageThread",
	 *   inversedBy="metadata"
	 * )
	 * @var \FOS\MessageBundle\Model\ThreadInterface
	 */
	protected $thread;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @var \FOS\MessageBundle\Model\ParticipantInterface
	 */
	protected $participant;
}
