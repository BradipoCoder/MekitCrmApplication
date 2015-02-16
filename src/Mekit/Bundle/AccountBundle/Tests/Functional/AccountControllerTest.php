<?php
namespace Mekit\Bundle\AccountBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class AccountControllerTest extends MekitFunctionalTest {

	protected function setUp() {
		$this->initClient([], $this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_account_index', []));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

	public function testCreateAction() {
		$accountName = "Mekit";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_account_create'));
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$form['mekit_account_form[name]'] = $accountName;
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_account_view", $currentRoute);
		$this->assertEquals($accountName, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testCreateAction
	 */
	public function testUpdateAction() {
		$accountName = "Mekit";
		$accountNameUpdated = "Mekit #2";
		$response = $this->client->requestGrid(
			'accounts-grid',
			array('accounts-grid[_filter][name][value]' => $accountName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_account_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$nameField = $form->get("mekit_account_form[name]");
		$this->assertEquals($accountName, $nameField->getValue());
		$nameField->setValue($accountNameUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_account_view", $currentRoute);
		$this->assertEquals($accountNameUpdated, $crawler->filter('h1.user-name')->text());

		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$accountNameUpdated = "Mekit #2";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_account_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertEquals($accountNameUpdated, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	/*
	public function testDeleteAction($id) {
		$this->client->request('DELETE', $this->getUrl('mekit_api_delete_account', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertEmptyResponseStatusCodeEquals($result, 204);
		$this->client->request('GET', $this->getUrl('mekit_account_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 404);
	}
	*/
}