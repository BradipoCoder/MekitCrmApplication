<?php
namespace Mekit\Bundle\ContactBundle\Tests\Unit\DependencyInjection;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitTest;
use Mekit\Bundle\ContactBundle\DependencyInjection\Configuration;

class ConfigurationTest extends MekitUnitTest {
	public function testGetConfigTreeBuilder() {
		$configuration = new Configuration();
		$builder = $configuration->getConfigTreeBuilder();
		$this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder', $builder);
	}
}