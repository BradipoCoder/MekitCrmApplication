<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitTest;
use Mekit\Bundle\AccountBundle\MekitAccountBundle;


class MekitAccountBundleTest extends MekitUnitTest {
	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitAccountBundle();
		$bundle->build($container);
	}
}
