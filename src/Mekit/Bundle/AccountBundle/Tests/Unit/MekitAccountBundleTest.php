<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit;

use Mekit\Bundle\TestBundle\Tests\Helpers\MekitTests;
use Mekit\Bundle\AccountBundle\MekitAccountBundle;

class MekitAccountBundleTest extends MekitTests {

	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitAccountBundle();
		$bundle->build($container);
	}
}
