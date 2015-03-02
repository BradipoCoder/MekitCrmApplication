<?php
namespace Mekit\Bundle\MeetingBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;

class MeetingControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_meeting_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}
}