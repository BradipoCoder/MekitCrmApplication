<?php

namespace Mekit\Bundle\DomainBundle\Entity;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Doctrine\ORM\Mapping as ORM;
use Mekit\Bundle\RelationshipBundle\Entity\Referenceable;
use Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement;
use Oro\Bundle\DataAuditBundle\Metadata\Annotation as Oro;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="mekit_domain",
 *      indexes={
 *          @ORM\Index(name="idx_domain_owner", columns={"owner_id"}),
 *          @ORM\Index(name="idx_domain_organization", columns={"organization_id"}),
 *          @ORM\Index(name="idx_domain_created_at", columns={"createdAt"}),
 *          @ORM\Index(name="idx_domain_updated_at", columns={"updatedAt"}),
 *          @ORM\Index(name="idx_domain_name", columns={"name"}),
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Oro\Loggable
 * @Config(
 *      routeName="mekit_domain_index",
 *      routeView="mekit_domain_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-suitcase"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"=""
 *          },
 *          "merge"={
 *              "enable"=true
 *          },
 *          "form"={
 *              "form_type"="mekit_domain_select",
 *              "grid_name"="domains-select-grid",
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "relationship"={
 *              "referenceable"=true,
 *              "label"="mekit.domain.entity_plural_label",
 *              "can_reference_itself"=false,
 *              "datagrid_name_list"="domains-related-relationship",
 *              "datagrid_name_select"="domains-related-select",
 *              "autocomplete_search_columns"={"name"}
 *          }
 *      }
 * )
 */
class Domain implements Referenceable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Soap\ComplexType("int", nillable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=10
     *          }
     *      }
     * )
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Soap\ComplexType("string")
     * @Oro\Versioned
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "identity"=true,
     *              "order"=20
     *          }
     *      }
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Soap\ComplexType("string", nillable=true)
     * @Oro\Versioned
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=70
     *          }
     *      }
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Soap\ComplexType("string")
     * @Oro\Versioned
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "identity"=true,
     *              "order"=20
     *          }
     *      }
     * )
     */
    private $provider;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime", nullable=true)
     * @Soap\ComplexType("dateTime", nillable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $expiration;



    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="SET NULL")
     * @Soap\ComplexType("string", nillable=true)
     * @Oro\Versioned
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "importexport"={
     *              "order"=100,
     *              "short"=true
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $organization;


    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Soap\ComplexType("dateTime", nillable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.created_at"
     *          },
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Soap\ComplexType("dateTime", nillable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="oro.ui.updated_at"
     *          },
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $updatedAt;



    /**
     * @var ReferenceableElement
     *
     * @ORM\OneToOne(targetEntity="Mekit\Bundle\RelationshipBundle\Entity\ReferenceableElement", cascade={"persist"}, orphanRemoval=true, mappedBy="domain")
     */
    protected $referenceableElement;

    /**
     * @return ReferenceableElement
     */
    public function getReferenceableElement() {
        return $this->referenceableElement;
    }

    /**
     * @param ReferenceableElement $referenceableElement
     */
    public function setReferenceableElement(ReferenceableElement $referenceableElement) {
        $this->referenceableElement = $referenceableElement;
        $referenceableElement->setDomain($this);
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param  int $id
     * @return Domain
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }



    /**
     * Set provider
     *
     * @param string $provider
     * @return Domain
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return string 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set expiration
     *
     * @param \DateTime $expiration
     * @return Domain
     */
    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Get expiration
     *
     * @return \DateTime 
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }


    /**
     * @return User
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * @param User $owningUser
     * @return $this
     */
    public function setOwner(User $owningUser) {
        $this->owner = $owningUser;
        return $this;
    }

    /**
     * Get created date/time
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param \DateTime
     * @return $this
     */
    public function setCreatedAt(\DateTime $created) {
        $this->createdAt = $created;
        return $this;
    }

    /**
     * Get last update date/time
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updated) {
        $this->updatedAt = $updated;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return (string)$this->getName();
    }

    /**
     * @return string
     */
    public function getClass() {
        return 'Mekit\Bundle\DomainBundle\Entity\Domain';
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave() {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Pre update event handler
     *
     * @ORM\PreUpdate
     */
    public function doPreUpdate() {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
