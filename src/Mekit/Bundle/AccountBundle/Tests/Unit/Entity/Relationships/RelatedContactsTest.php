<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\ContactBundle\Entity\Contact;

class RelatedContactsTest extends MekitUnitEntityTest {

	public function testRelatedContacts() {
		$contactOne = new Contact();
		$contactOne->setFirstName("contact-one");
		$contactTwo = new Contact();
		$contactTwo->setFirstName("contact-two");

		$entity = new Account();
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->addContact($contactOne));
		$this->assertTrue($entity->hasContact($contactOne));
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($contactOne), $actual->toArray());

		$this->assertSame($entity, $entity->addContact($contactTwo));
		$this->assertTrue($entity->hasContact($contactTwo));
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($contactOne, $contactTwo), $actual->toArray());

		$this->assertSame($entity, $entity->removeContact($contactOne));
		$this->assertFalse($entity->hasContact($contactOne));
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($contactTwo), array_values($actual->toArray()));

		$this->assertSame($entity, $entity->removeContact($contactTwo));
		$this->assertFalse($entity->hasContact($contactTwo));
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$contacts = new ArrayCollection(array($contactOne, $contactTwo));
		$this->assertSame($entity, $entity->setContacts($contacts));
		$this->assertTrue($entity->hasContact($contactOne));
		$this->assertTrue($entity->hasContact($contactTwo));
		$actual = $entity->getContacts();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($contacts, $actual);
	}
}