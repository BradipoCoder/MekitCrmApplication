<?php
namespace Mekit\Bundle\TestBundle\phpunitBootstrap;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase as OroWebTestCase;

/**
 * The purpose of this class is to boot kernel before any unit test(provider) is executed
 * So all entities will be created with extendEntity features
 * Failing to do so will make functional tests fail if executed together with unit tests
 * Reference: http://www.orocrm.com/forums/topic/functional-tests-401
 *
 * Class KernelBooter
 */
class KernelBooter extends OroWebTestCase {

	public function boot() {
		$kernel = static::createKernel();
		$kernel->boot();
	}
}
