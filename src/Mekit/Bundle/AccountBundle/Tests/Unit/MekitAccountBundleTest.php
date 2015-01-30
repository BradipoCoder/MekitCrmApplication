<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit;

use Mekit\Bundle\AccountBundle\MekitAccountBundle;

class MekitAccountBundleTest extends \PHPUnit_Framework_TestCase {

	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitAccountBundle();
		$bundle->build($container);
	}
}
