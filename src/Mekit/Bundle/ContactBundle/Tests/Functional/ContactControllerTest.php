<?php
namespace Mekit\Bundle\ContactBundle\Tests\Functional;

use Mekit\Bundle\TestBundle\Helpers\MekitFunctionalTest;


class ContactControllerTest extends MekitFunctionalTest {
    protected function setUp() {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testIndexAction() {
        $this->client->request('GET', $this->getUrl('mekit_contact_index',[]));
        $result = $this->client->getResponse();
        $this->assertTrue($result->isOk());
    }
}