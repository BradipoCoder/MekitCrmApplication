<?php
namespace Mekit\Bundle\ProjectBundle\Tests\Functional;

use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ProjectControllerTest extends MekitFunctionalTest {
	/** @var  Account */
	protected static $account;

	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
		$this->loadFixtures(['Mekit\Bundle\ProjectBundle\Tests\Functional\Fixture\LoadProjectBundleFixtures']);
	}

	protected function postFixtureLoad() {
		self::$account = $this->getReference('default_account');
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_project_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

	public function testCreateAction() {
		$projectName = "Project";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_project_create'));
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$form['mekit_project_form[name]'] = $projectName;
		$form['mekit_project_form[account]'] = self::$account->getId();
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_project_view", $currentRoute);
		$this->assertEquals($projectName, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testCreateAction
	 */
	public function testUpdateAction() {
		$projectName = "Project";
		$projectNameUpdated = "Project #2";
		$response = $this->client->requestGrid(
			'projects-grid',
			array('projects-grid[_filter][name][value]' => $projectName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_project_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$nameField = $form->get("mekit_project_form[name]");
		$this->assertEquals($projectName, $nameField->getValue());
		$nameField->setValue($projectNameUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_project_view", $currentRoute);
		$this->assertEquals($projectNameUpdated, $crawler->filter('h1.user-name')->text());

		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$accountNameUpdated = "Project #2";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_project_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertEquals($accountNameUpdated, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testInfoAction($id) {
		$this->client->request(
			'GET',
			$this->getUrl(
				'mekit_project_widget_info',
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
			$this->getUrl('mekit_api_delete_project', array('id' => $id))
		);

		$result = $this->client->getResponse();
		$this->assertEmptyResponseStatusCodeEquals($result, 204);

		$this->client->request(
			'GET',
			$this->getUrl('mekit_project_view', array('id' => $id))
		);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 404);
	}
}