<?php
namespace Mekit\Bundle\ListBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ListControllerTest extends MekitFunctionalTest
{
	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_list_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

	public function testUpdateAction() {
		$listName = "ACCOUNT_TYPE";
		$listLabel = "Type";
		$listLabelUpdated = "Account Type";
		$response = $this->client->requestGrid(
			'lists-grid', array('lists-grid[_filter][name][value]' => $listName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_list_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$nameField = $form->get("mekit_listgroup_form[label]");
		$this->assertEquals($listLabel, $nameField->getValue());
		$nameField->setValue($listLabelUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_list_view", $currentRoute);
		$this->assertContains($listLabelUpdated, $crawler->filter('h1.user-name')->text());

		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$listLabelUpdated = "Account Type";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_list_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertContains($listLabelUpdated, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testInfoAction($id) {
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_list_widget_info',
			['id' => $id, '_widgetContainer' => 'dialog']
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
	public function testListItemsAction($id) {
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_list_widget_listitems',
			['id' => $id, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}

}