<?php
namespace Mekit\Bundle\CallBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class CallControllerTest extends MekitFunctionalTest {
	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_call_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

	public function testCreateAction() {
		$callName = "Call someone";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_call_create'));
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$form['mekit_call_form[name]'] = $callName;
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_call_view", $currentRoute);
		$this->assertEquals($callName, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testCreateAction
	 */
	public function testUpdateAction() {
		$callName = "Call someone";
		$callNameUpdated = "Call someone else";
		$response = $this->client->requestGrid(
			'calls-grid',
			array('calls-grid[_filter][name][value]' => $callName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_call_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$nameField = $form->get("mekit_call_form[name]");
		$this->assertEquals($callName, $nameField->getValue());
		$nameField->setValue($callNameUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_call_view", $currentRoute);
		$this->assertEquals($callNameUpdated, $crawler->filter('h1.user-name')->text());

		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$callNameUpdated = "Call someone else";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_call_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertEquals($callNameUpdated, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testInfoAction($id) {
		$this->client->request(
			'GET',
			$this->getUrl(
				'mekit_call_widget_info',
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
			$this->getUrl('mekit_api_delete_call', array('id' => $id))
		);

		$result = $this->client->getResponse();
		$this->assertEmptyResponseStatusCodeEquals($result, 204);

		$this->client->request(
			'GET',
			$this->getUrl('mekit_call_view', array('id' => $id))
		);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 404);
	}
}