<?php

namespace Mekit\Bundle\DomainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\DomainBundle\Entity\Domain;

class DomainController extends Controller
{


    /**
     * @Route(
     *      "/{_format}",
     *      name="mekit_domain_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @Template
     * @AclAncestor("mekit_domain_domain_view")
     * @return array
     */
    public function indexAction() {
        return array(
            'entity_class' => $this->container->getParameter('mekit_domain.domain.entity.class')
        );
    }

    /**
     * @Route("/view/{id}", name="mekit_domain_view", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="mekit_domain_domain_view",
     *      type="entity",
     *      class="MekitDomainBundle:Domain",
     *      permission="VIEW"
     * )
     * @param Domain $domain
     * @return array
     */
    public function viewAction(Domain $domain) {
        return [
            'entity' => $domain
        ];
    }

    /**
     * @Route("/create", name="mekit_domain_create")
     * @Acl(
     *      id="mekit_domain_domain_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="MekitDomainBundle:Domain"
     * )
     * @Template("MekitDomainBundle:Domain:update.html.twig")
     * @return array
     */
    public function createAction() {
        return $this->update();
    }

    /**
     * @Route("/update/{id}", name="mekit_domain_update", requirements={"id"="\d+"})
     * @Acl(
     *      id="mekit_domain_domain_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="MekitDomainBundle:Domain"
     * )
     * @Template()
     * @param Domain $domain
     * @return array
     */
    public function updateAction(Domain $domain) {
        return $this->update($domain);
    }

    /**
     * @param Domain $entity
     * @return array
     */
    protected function update(Domain $entity = null) {
        if (!$entity) {
            /** @var Domain $entity */
            $entity = $this->getManager()->createEntity();
        }

        return $this->get('oro_form.model.update_handler')->handleUpdate(
            $entity,
            $this->get('mekit_domain.form.domain'),
            function (Domain $entity) {
                return array(
                    'route' => 'mekit_domain_update',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            function (Domain $entity) {
                return array(
                    'route' => 'mekit_domain_view',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            $this->get('translator')->trans('mekit.domain.controller.domain.saved.message'),
            $this->get('mekit_domain.form.handler.domain')
        );
    }


    /**
     * @Route("/widget/info/{id}", name="mekit_domain_widget_info", requirements={"id"="\d+"})
     * @AclAncestor("mekit_domain_domain_view")
     * @Template(template="MekitDomainBundle:Domain/widget:info.html.twig")
     * @param Domain $domain
     * @return array
     */
    public function infoAction(Domain $domain) {
        return [
            'domain' => $domain
        ];
    }

    /**
     * @return ApiEntityManager
     */
    protected function getManager() {
        return $this->get('mekit_domain.domain.manager.api');
    }

}
