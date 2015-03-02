<?php
namespace Mekit\Bundle\ContactBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ContactControllerTest extends MekitFunctionalTest {
    protected function setUp() {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testIndexAction() {
        $this->client->request('GET', $this->getUrl('mekit_contact_index',[]));
        $result = $this->client->getResponse();
        $this->assertTrue($result->isOk());
    }

	public function testCreateAction() {
		$contactFirstName = "Test";
		$contactLastName = "Contact";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_contact_create'));
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$form['mekit_contact_form[firstName]'] = $contactFirstName;
		$form['mekit_contact_form[lastName]'] = $contactLastName;
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_contact_view", $currentRoute);
		$this->assertContains($contactFirstName, $crawler->filter('h1.user-name')->text());
		$this->assertContains($contactLastName, $crawler->filter('h1.user-name')->text());
	}

	/**
	 * @depends testCreateAction
	 */
	public function testUpdateAction() {
		$contactFirstName = "Test";
		$contactLastName = "Contact";
		$contactFirstNameUpdated = "Test #2";
		$contactLastNameUpdated = "Contact #2";

		$response = $this->client->requestGrid(
			'contacts-grid',
			array('contacts-grid[_filter][firstName][value]' => $contactFirstName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$response = $this->client->requestGrid(
			'contacts-grid',
			array('contacts-grid[_filter][lastName][value]' => $contactLastName)
		);
		$result = $this->getJsonResponseContent($response, 200);
		$result = reset($result['data']);
		$id = $result['id'];
		$this->assertNotEmpty($id);
		$this->assertTrue($id > 0);

		$crawler = $this->client->request('GET', $this->getUrl('mekit_contact_update', array('id' => $id)));
		$this->assertTrue($this->client->getResponse()->isOk());
		/** @var Form $form */
		$form = $crawler->selectButton('Save and Close')->form();
		$firstNameField = $form->get("mekit_contact_form[firstName]");
		$lastNameField = $form->get("mekit_contact_form[lastName]");
		$this->assertEquals($contactFirstName, $firstNameField->getValue());
		$this->assertEquals($contactLastName, $lastNameField->getValue());
		$firstNameField->setValue($contactFirstNameUpdated);
		$lastNameField->setValue($contactLastNameUpdated);
		$this->client->followRedirects(true);
		$crawler = $this->client->submit($form);
		//
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		//check if we are on view page
		$request = $this->client->getRequest();
		$currentRoute = $request->attributes->get('_route');
		$this->assertEquals("mekit_contact_view", $currentRoute);
		$this->assertContains($contactFirstNameUpdated, $crawler->filter('h1.user-name')->text());
		$this->assertContains($contactLastNameUpdated, $crawler->filter('h1.user-name')->text());
		return $id;
	}

	/**
	 * @depends testUpdateAction
	 * @param Integer $id
	 */
	public function testViewAction($id) {
		$contactFirstNameUpdated = "Test #2";
		$contactLastNameUpdated = "Contact #2";
		$crawler = $this->client->request('GET', $this->getUrl('mekit_contact_view', array('id' => $id)));
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
		$this->assertContains($contactFirstNameUpdated, $crawler->filter('h1.user-name')->text());
		$this->assertContains($contactLastNameUpdated, $crawler->filter('h1.user-name')->text());
	}
}