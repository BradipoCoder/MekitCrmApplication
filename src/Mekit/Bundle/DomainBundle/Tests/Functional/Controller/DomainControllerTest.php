<?php

namespace Mekit\Bundle\DomainBundle\Tests\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class DomainControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->initClient(array(), $this->generateBasicAuthHeader());
    }


    public function testCreate()
    {
        $this->client->request(
            'GET',
            $this->getUrl('mekit_domain_create')
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('mekit_domain_form_description', $result->getContent());
    }

    public function testIndex()
    {
        $this->client->request(
            'GET',
            $this->getUrl('mekit_domain_index')
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('grid table-hover table table-bordered table-condensed', $result->getContent());
    }

    public function testView()
    {
        $this->client->request(
            'GET',
            $this->getUrl('mekit_domain_view')
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('data-target="#domainView"',$result->getContent());
    }

}
