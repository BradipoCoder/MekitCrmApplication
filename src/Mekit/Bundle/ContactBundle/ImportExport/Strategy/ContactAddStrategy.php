<?php
namespace Mekit\Bundle\ContactBundle\ImportExport\Strategy;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Oro\Bundle\SecurityBundle\Authentication\Token\OrganizationContextTokenInterface;
use Oro\Bundle\ImportExportBundle\Strategy\Import\AbstractImportStrategy;
use Mekit\Bundle\ContactBundle\Entity\Contact;

class ContactAddStrategy extends AbstractImportStrategy
{
    /**
     * @var ContactImportHelper
     */
    protected $contactImportHelper;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @param ContactImportHelper $contactImportHelper
     */
    public function setContactImportHelper(ContactImportHelper $contactImportHelper)
    {
        $this->contactImportHelper = $contactImportHelper;
    }

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function process($entity)
    {
        $this->assertEnvironment($entity);

        /** @var Contact $entity */
        $entity = $this->beforeProcessEntity($entity);
        $entity = $this->processEntity($entity);
        $entity = $this->afterProcessEntity($entity);
        $entity = $this->validateAndUpdateContext($entity);

        return $entity;
    }

    /**
     * @param Contact $entity
     * @return Contact
     */
    protected function processEntity(Contact $entity)
    {
        $this->databaseHelper->resetIdentifier($entity);

        $this->processSingleRelations($entity);
        $this->processMultipleRelations($entity);
        $this->processSecurityRelations($entity);

        return $entity;
    }

    /**
     * @param Contact $entity
     */
    protected function processSingleRelations(Contact $entity)
    {
        // update assigned to
//        $assignedTo = $entity->getAssignedTo();
//        if ($assignedTo) {
//            $entity->setAssignedTo($this->findExistingEntity($assignedTo));
//        }
    }

    /**
     * @param Contact $entity
     */
    protected function processMultipleRelations(Contact $entity)
    {
        // clear accounts
        foreach ($entity->getAccounts() as $account) {
            $entity->removeAccount($account);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessEntity($entity)
    {
        /** @var Contact $entity */
        $entity = parent::afterProcessEntity($entity);

        $this->contactImportHelper->updateScalars($entity);
        $this->contactImportHelper->updatePrimaryEntities($entity);

        return $entity;
    }

    /**
     * @param Contact $entity
     */
    protected function processSecurityRelations(Contact $entity)
    {
        // update owner
        $owner = $entity->getOwner();
        if ($owner) {
            $owner = $this->findExistingEntity($owner);
        }
        if (!$owner) {
            $token = $this->securityContext->getToken();
            if ($token) {
                $owner = $token->getUser();
            }
        }
        $entity->setOwner($owner);

        // update organization
        $organization = $entity->getOrganization();
        if ($organization) {
            $organization = $this->findExistingEntity($organization);
        }
        if (!$organization) {
            $token = $this->securityContext->getToken();
            if ($token && $token instanceof OrganizationContextTokenInterface) {
                $organization = $token->getOrganizationContext();
            }
        }
        $entity->setOrganization($organization);
    }

    /**
     * @param Contact $entity
     * @return null|Contact
     */
    protected function validateAndUpdateContext(Contact $entity)
    {
        // validate entity
        $validationErrors = $this->strategyHelper->validateEntity($entity);
        if ($validationErrors) {
            $this->context->incrementErrorEntriesCount();
            $this->strategyHelper->addValidationErrors($validationErrors, $this->context);
            return null;
        }

        // increment context counter
        $this->context->incrementAddCount();

        return $entity;
    }
}
