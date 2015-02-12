<?php
namespace Mekit\Bundle\AccountBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase as OroWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SfWebTestCase;

class FunctionalTestTest extends OroWebTestCase {

	protected function setUp() {
		$this->initClient(
			[],
			$this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$crawler = $this->client->request('GET', $this->getUrl('mekit_playground_index',[]));
		$result = $this->client->getResponse();
		$resCode = $result->getStatusCode();

		echo "\n\nRESULT($resCode): ";
		if(!$result->isOk()) {
			$exception = $crawler->filter('div.text-exception')->extract(['_text']);
			print_r($exception);
		} else {
			$exception = $crawler->filter('p.environment')->extract(['_text']);
			print_r($exception);
		}

		$this->assertTrue($result->isOk());

	}
}