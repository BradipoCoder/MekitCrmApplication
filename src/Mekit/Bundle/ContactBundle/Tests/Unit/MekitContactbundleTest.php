<?php
namespace Mekit\Bundle\ContactBundle\Tests\Unit;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitTest;
use Mekit\Bundle\ContactBundle\MekitContactBundle;

class MekitContactbundleTest extends MekitUnitTest {
	public function testBuild() {
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$bundle = new MekitContactBundle();
		$bundle->build($container);
	}
}