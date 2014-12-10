<?php
namespace Mekit\Bundle\RelationshipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity(repositoryClass="Mekit\Bundle\RelationshipBundle\Entity\Repository\RelationshipRepository")
 * @ORM\Table(name="mekit_relationship")
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-sitemap"
 *          }
 *      }
 * )
 */
class Relationship {
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
	 * @var string
	 *
	 * @ORM\Column(name="owner_entity", type="string", length=255, nullable=false)
	 */
	protected $ownerEntity;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="owner_id", type="integer", nullable=false)
	 */
	protected $ownerId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="owned_entity", type="string", length=255, nullable=false)
	 */
	protected $ownedEntity;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="owned_id", type="integer", nullable=false)
	 */
	protected $ownedId;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOwnerEntity() {
		return $this->ownerEntity;
	}

	/**
	 * @param string $ownerEntity
	 * @return $this
	 */
	public function setOwnerEntity($ownerEntity) {
		$this->ownerEntity = $ownerEntity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOwnerId() {
		return $this->ownerId;
	}

	/**
	 * @param int $ownerId
	 * @return $this
	 */
	public function setOwnerId($ownerId) {
		$this->ownerId = $ownerId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOwnedEntity() {
		return $this->ownedEntity;
	}

	/**
	 * @param string $ownedEntity
	 * @return $this
	 */
	public function setOwnedEntity($ownedEntity) {
		$this->ownedEntity = $ownedEntity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOwnedId() {
		return $this->ownedId;
	}

	/**
	 * @param int $ownedId
	 * @return $this
	 */
	public function setOwnedId($ownedId) {
		$this->ownedId = $ownedId;
		return $this;
	}
}