<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 */
class MessageThread extends BaseThread {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @var \FOS\MessageBundle\Model\ParticipantInterface
	 */
	protected $createdBy;

	/**
	 * @ORM\OneToMany(
	 *   targetEntity="App\Entity\Message",
	 *   mappedBy="thread"
	 * )
	 * @var Message[]|Collection
	 */
	protected $messages;

	/**
	 * @ORM\OneToMany(
	 *   targetEntity="App\Entity\MessageThreadMetadata",
	 *   mappedBy="thread",
	 *   cascade={"all"}
	 * )
	 * @var MessageThreadMetadata[]|Collection
	 */
	protected $metadata;
}
