<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit;

use Mekit\Bundle\TestBundle\Helpers\MekitTest;
use Mekit\Bundle\AccountBundle\MekitAccountBundle;

class MekitAccountBundleTest extends MekitTest {

	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitAccountBundle();
		$bundle->build($container);
	}
}
