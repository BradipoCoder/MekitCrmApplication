<?php
namespace Mekit\Bundle\AccountBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase as OroWebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SfWebTestCase;

class AccountControllerTest extends OroWebTestCase {

	protected function setUp() {
		$this->initClient(
			['environment'=> 'test', 'debug' => true],
			$this->generateBasicAuthHeader());
	}

	public function testIndexAction() {
		$this->client->request('GET', $this->getUrl('mekit_account_index',[]));
		//$this->client->request('GET', '/platform/information');
		$request = $this->client->getRequest();
		$result = $this->client->getResponse();
		$resCode = $result->getStatusCode();

		echo "\n\nRESULT($resCode): ";

		/*
		echo "\n\nRESULT($resCode): ";
		if(!$result->isOk()) {
			echo "\nREQUEST: ";
			foreach($request->headers as $h => $v) {
				echo "\n$h = " . json_encode($v);
			}

			echo "\n\nRESULT($resCode): ";
			foreach($result->headers as $h => $v) {
				echo "\n$h = " . json_encode($v);
			}

			echo "\n\n" . $result->getContent();
		}*/

		$this->assertTrue($result->isOk());

	}
}