<?php
namespace Mekit\Bundle\ProjectBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;

class ProjectControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_project_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}