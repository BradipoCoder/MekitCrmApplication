<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\DependencyInjection;

use Mekit\Bundle\TestBundle\Tests\Helpers\MekitTests;
use Mekit\Bundle\AccountBundle\DependencyInjection\Configuration;

class ConfigurationTest extends MekitTests {

	public function testGetConfigTreeBuilder() {
		$configuration = new Configuration();
		$builder = $configuration->getConfigTreeBuilder();
		$this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder', $builder);
	}

}