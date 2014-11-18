<?php
namespace Mekit\Bundle\AccountBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\AddressBundle\Entity\AbstractPhone;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity
 * @ORM\Table("mekit_account_phone", indexes={
 *      @ORM\Index(name="primary_phone_idx", columns={"phone", "is_primary"})
 * })
 * @ORM\Entity(repositoryClass="Mekit\Bundle\AccountBundle\Entity\Repository\AccountPhoneRepository")
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-phone"
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
class AccountPhone extends AbstractPhone {
	/**
	 * @ORM\ManyToOne(targetEntity="Account", inversedBy="phones")
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $owner;

	/**
	 * @param Account $owner
	 * @return $this
	 */
	public function setOwner(Account $owner = null) {
		$this->owner = $owner;
		return $this;
	}

	/**
	 * @return Account
	 */
	public function getOwner() {
		return $this->owner;
	}
}

