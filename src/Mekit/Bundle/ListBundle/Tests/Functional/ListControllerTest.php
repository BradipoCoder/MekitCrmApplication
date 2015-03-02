<?php
namespace Mekit\Bundle\ListBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;

class ListControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_list_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}