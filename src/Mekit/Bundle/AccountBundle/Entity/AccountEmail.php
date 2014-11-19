<?php
namespace Mekit\Bundle\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

use Oro\Bundle\AddressBundle\Entity\AbstractEmail;
use Oro\Bundle\EmailBundle\Entity\EmailInterface;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_account_email", indexes={
 *      @ORM\Index(name="primary_email_idx", columns={"email", "is_primary"})
 * })
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-envelope"
 *          },
 *          "note"={
 *              "immutable"=true
 *          },
 *          "activity"={
 *              "immutable"=true
 *          },
 *          "attachment"={
 *              "immutable"=true
 *          }
 *      }
 * )
 */
class AccountEmail extends AbstractEmail implements EmailInterface {
	/**
	 * @ORM\ManyToOne(targetEntity="Account", inversedBy="emails")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $owner;

	/**
	 * {@inheritdoc}
	 */
	public function getEmailField()
	{
		return 'email';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmailOwner()
	{
		return $this->getOwner();
	}

	/**
	 * Set contact as owner.
	 *
	 * @param Account $owner
	 */
	public function setOwner(Account $owner = null)
	{
		$this->owner = $owner;
	}

	/**
	 * Get owner contact.
	 *
	 * @return Account
	 */
	public function getOwner()
	{
		return $this->owner;
	}
}