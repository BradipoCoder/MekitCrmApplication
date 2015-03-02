<?php
namespace Mekit\Bundle\TaskBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;

class TaskControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_task_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}