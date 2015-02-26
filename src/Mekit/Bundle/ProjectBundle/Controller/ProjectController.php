<?php
namespace Mekit\Bundle\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;

use Mekit\Bundle\ProjectBundle\Entity\Project;

class ProjectController extends Controller {
	/**
	 * @Route(
	 *      "/{_format}",
	 *      name="mekit_project_index",
	 *      requirements={"_format"="html|json"},
	 *      defaults={"_format" = "html"}
	 * )
	 * @Template
	 * @AclAncestor("mekit_project_view")
	 * @return array
	 */
	public function indexAction() {
		return array(
			'entity_class' => $this->container->getParameter('mekit_project.project.entity.class')
		);
	}

	/**
	 * @Route("/view/{id}", name="mekit_project_view", requirements={"id"="\d+"})
	 * @Template
	 * @Acl(
	 *      id="mekit_project_view",
	 *      type="entity",
	 *      class="MekitProjectBundle:Project",
	 *      permission="VIEW"
	 * )
	 * @param Project $project
	 * @return array
	 */
	public function viewAction(Project $project) {
		return [
			'entity' => $project
		];
	}

	/**
	 * @Route("/create", name="mekit_project_create")
	 * @Acl(
	 *      id="mekit_project_create",
	 *      type="entity",
	 *      permission="CREATE",
	 *      class="MekitProjectBundle:Project"
	 * )
	 * @Template("MekitProjectBundle:Project:update.html.twig")
	 * @return array
	 */
	public function createAction() {
		return $this->update();
	}

	/**
	 * @Route("/update/{id}", name="mekit_project_update", requirements={"id"="\d+"})
	 * @Acl(
	 *      id="mekit_project_update",
	 *      type="entity",
	 *      permission="EDIT",
	 *      class="MekitProjectBundle:Project"
	 * )
	 * @Template()
	 * @param Project $project
	 * @return array
	 */
	public function updateAction(Project $project) {
		return $this->update($project);
	}

	/**
	 * @param Project $entity
	 * @return array
	 */
	protected function update(Project $entity = null) {
		if (!$entity) {
			/** @var Project $entity */
			$entity = $this->getManager()->createEntity();

			//assign to current user
			//$entity->setAssignedTo($this->getUser());
		}

		return $this->get('oro_form.model.update_handler')->handleUpdate(
			$entity,
			$this->get('mekit_project.form.project'),
			function (Project $entity) {
				return array(
					'route' => 'mekit_project_update',
					'parameters' => array('id' => $entity->getId())
				);
			},
			function (Project $entity) {
				return array(
					'route' => 'mekit_project_view',
					'parameters' => array('id' => $entity->getId())
				);
			},
			$this->get('translator')->trans('mekit.project.controller.project.saved.message'),
			$this->get('mekit_project.form.handler.project')
		);
	}


	/**
	 * @Route("/widget/info/{id}", name="mekit_project_widget_info", requirements={"id"="\d+"})
	 * @AclAncestor("mekit_project_view")
	 * @Template(template="MekitProjectBundle:Project/widget:info.html.twig")
	 * @param Project $project
	 * @return array
	 */
	public function infoAction(Project $project) {
		return [
			'entity' => $project
		];
	}

	/**
	 * @return ApiEntityManager
	 */
	protected function getManager() {
		return $this->get('mekit_project.project.manager.api');
	}





}
