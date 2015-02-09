<?php
namespace Mekit\Bundle\DomainBundle\Tests\Unit;

use Mekit\Bundle\DomainBundle\MekitDomainBundle;

class MekitDomainBundleTest extends \PHPUnit_Framework_TestCase {

	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitDomainBundle();
		$bundle->build($container);
	}
}
