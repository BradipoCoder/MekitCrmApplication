<?php
namespace Mekit\Bundle\ListBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;
use Symfony\Component\DomCrawler\Form;

/**
 * @outputBuffering enabled
 * @dbIsolation
 */
class ListItemControllerTest extends MekitFunctionalTest
{
	protected function setUp() {
		$this->initClient([], array_merge($this->generateBasicAuthHeader(), array('HTTP_X-CSRF-Header' => 1)));
		$this->loadFixtures(['Mekit\Bundle\ListBundle\Tests\Functional\Fixture\LoadListBundleFixtures']);
	}

	public function testCreateAction() {
		$listGroupId = $this->getReference('default_listgroup')->getId();
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_listitem_create',
			['listGroupId' => $listGroupId, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}

	public function testUpdateAction() {
		$listItemId = $this->getReference('default_listitem')->getId();
		$this->client->request(
			'GET', $this->getUrl(
			'mekit_listitem_update',
			['id' => $listItemId, '_widgetContainer' => 'dialog']
		)
		);
		//just verify response
		$result = $this->client->getResponse();
		$this->assertHtmlResponseStatusCodeEquals($result, 200);
	}
}