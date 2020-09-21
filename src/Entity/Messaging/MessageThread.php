<?php namespace App\Entity\Messaging;

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
	 *   targetEntity="Message",
	 *   mappedBy="thread"
	 * )
	 * @var Message[]|Collection
	 */
	protected $messages;

	/**
	 * @ORM\OneToMany(
	 *   targetEntity="MessageThreadMetadata",
	 *   mappedBy="thread",
	 *   cascade={"all"}
	 * )
	 * @var MessageThreadMetadata[]|Collection
	 */
	protected $metadata;
}
