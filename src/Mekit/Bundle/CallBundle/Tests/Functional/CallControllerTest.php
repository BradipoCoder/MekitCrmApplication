<?php
namespace Mekit\Bundle\CallBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;

class CallControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_call_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}