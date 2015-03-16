<?php
namespace Mekit\Bundle\MeetingBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class MeetingControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_meeting_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

	public function testCreateAction() {
		$meetingName = "Meet someone";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_meeting_create'));
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$form['mekit_meeting_form[name]'] = $meetingName;
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_meeting_view", $currentRoute);
		$this->assertEquals($meetingName, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testCreateAction
	 */
	public function testUpdateAction() {
		$meetingName = "Meet someone";
		$meetingNameUpdated = "Meet someone else";
		$response = $this->client->requestGrid(
			'meetings-grid',
			array('meetings-grid[_filter][name][value]' => $meetingName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_meeting_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$nameField = $form->get("mekit_meeting_form[name]");
		$this->assertEquals($meetingName, $nameField->getValue());
		$nameField->setValue($meetingNameUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_meeting_view", $currentRoute);
		$this->assertEquals($meetingNameUpdated, $crawler->filter('h1.user-name')->text());

		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$meetingNameUpdated = "Meet someone else";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_meeting_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertEquals($meetingNameUpdated, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testInfoAction($id) {
		$this->client->request(
			'GET',
			$this->getUrl(
				'mekit_meeting_widget_info',
				array('id' => $id, '_widgetContainer' => 'dialog')
			)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testDeleteAction($id) {
		$this->client->request(
			'DELETE',
			$this->getUrl('mekit_api_delete_meeting', array('id' => $id))
		);

		$result = $this->client->getResponse();
		$this->assertEmptyResponseStatusCodeEquals($result, 204);

		$this->client->request(
			'GET',
			$this->getUrl('mekit_meeting_view', array('id' => $id))
		);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 404);
	}
}