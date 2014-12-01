<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use Mekit\Bundle\RelationshipBundle\Model\ExtendRelationship;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\NoteBundle\Model\ExtendNote;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\RelationshipBundle\Entity\Repository\RelationshipRepository")
 * @ORM\Table(name="mekit_relationship")
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-sitemap"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="user_owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "activity"={
 *              "immutable"=true
 *          }
 *      }
 * )
 */
class Relationship extends ExtendRelationship {
	const ENTITY_NAME = 'Mekit\Bundle\RelationshipBundle\Entity\Relationship';

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="user_owner_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $owner;

	/**
	 * @var Organization
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization", inversedBy="businessUnits")
	 * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $organization;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="updated_by_user_id", referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $updatedBy;

	/**
	 * @var \Datetime $created
	 *
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 * @ConfigField(
	 *      defaultValues={
	 *          "entity"={
	 *              "label"="oro.ui.created_at"
	 *          }
	 *      }
	 * )
	 */
	protected $createdAt;

	/**
	 * @var \Datetime $updated
	 *
	 * @ORM\Column(name="updated_at", type="datetime", nullable=false)
	 * @ConfigField(
	 *      defaultValues={
	 *          "entity"={
	 *              "label"="oro.ui.updated_at"
	 *          }
	 *      }
	 * )
	 */
	protected $updatedAt;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param \Datetime $createdAt
	 *
	 * @return $this
	 */
	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
		return $this;
	}

	/**
	 * @return \Datetime
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \Datetime $updatedAt
	 *
	 * @return $this
	 */
	public function setUpdatedAt($updatedAt) {
		$this->updatedAt = $updatedAt;
		return $this;
	}

	/**
	 * @return \Datetime
	 */
	public function getUpdatedAt() {
		return $this->updatedAt;
	}

	/**
	 * Not using type hint due to the fact that entity setter can be called when no logged user available
	 * So $updatedBy will be null
	 *
	 * @param UserInterface|null $updatedBy
	 *
	 * @return $this
	 */
	public function setUpdatedBy($updatedBy) {
		$this->updatedBy = $updatedBy;
		return $this;
	}

	/**
	 * @return UserInterface
	 */
	public function getUpdatedBy() {
		return $this->updatedBy;
	}

	/**
	 * @param UserInterface|null $owningUser
	 *
	 * @return $this
	 */
	public function setOwner($owningUser) {
		$this->owner = $owningUser;
		return $this;
	}

	/**
	 * @return UserInterface
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * Set organization
	 *
	 * @param Organization $organization
	 * @return $this
	 */
	public function setOrganization($organization) {
		$this->organization = $organization;
		return $this;
	}

	/**
	 * Get organization
	 *
	 * @return Organization
	 */
	public function getOrganization() {
		return $this->organization;
	}
}