<?php namespace App\Entity\Messaging;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

/**
 * @ORM\Entity
 */
class MessageMetadata extends BaseMessageMetadata {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(
	 *   targetEntity="Message",
	 *   inversedBy="metadata"
	 * )
	 * @var \FOS\MessageBundle\Model\MessageInterface
	 */
	protected $message;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @var \FOS\MessageBundle\Model\ParticipantInterface
	 */
	protected $participant;
}
