<?php

namespace Mekit\Bundle\RelationshipBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Mekit\Bundle\RelationshipBundle\DependencyInjection\Compiler\MigrationExtensionPass;

class MekitRelationshipBundle extends Bundle {
	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new MigrationExtensionPass());
	}
}
