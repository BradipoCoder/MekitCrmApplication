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
		$this->client->request('GET', $this->getUrl('mekit_account_index',[]));
		$result = $this->client->getResponse();
		$this->assertTrue($result->isOk());
	}

    public function testCreateAction() {
        $crawler = $this->client->request('GET', $this->getUrl('mekit_account_create'));
        /** @var Form $form */
        $form = $crawler->selectButton('Save and Close')->form();
        $form['mekit_account_form[name]'] = 'Mekit';
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        //check if we are on view page
	    $request = $this->client->getRequest();
	    $currentRoute = $request->attributes->get('_route');
	    $this->assertEquals("mekit_account_view", $currentRoute);
	    //$currentId = $request->attributes->get('id');
    }

}