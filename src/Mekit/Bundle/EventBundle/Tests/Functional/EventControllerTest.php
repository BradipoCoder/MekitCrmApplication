<?php
namespace Mekit\Bundle\EventBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Mekit\Bundle\TaskBundle\Entity\Task;
use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class EventControllerTest extends MekitFunctionalTest
{
	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
		$this->loadFixtures(['Mekit\Bundle\EventBundle\Tests\Functional\Fixture\LoadEventBundleFixtures']);
	}

	public function testInfoActionTask() {
		/** @var Task $task */
		$task = $this->getReference('default_task');
		$id = $task->getEvent()->getId();
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_event_widget_info',
			['id' => $id, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}

	public function testInfoActionCall() {
		/** @var Call $call */
		$call = $this->getReference('default_call');
		$id = $call->getEvent()->getId();
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_event_widget_info',
			['id' => $id, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}

	public function testInfoActionMeeting() {
		/** @var Meeting $meeting */
		$meeting = $this->getReference('default_meeting');
		$id = $meeting->getEvent()->getId();
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_event_widget_info',
			['id' => $id, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}
}