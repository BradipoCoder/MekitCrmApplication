<?php
namespace Mekit\Bundle\AccountBundle\Tests\Functional;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase as OroWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SfWebTestCase;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class AccountControllerTest extends OroWebTestCase {

	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_account_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}