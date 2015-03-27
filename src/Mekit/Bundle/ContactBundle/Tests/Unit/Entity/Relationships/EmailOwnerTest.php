<?php
namespace Mekit\Bundle\ContactBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Mekit\Bundle\ContactBundle\Entity\Contact;
use Mekit\Bundle\ContactInfoBundle\Entity\Email;


class EmailOwnerTest extends MekitUnitEntityTest {
	/** @var string */
	protected $entityName = 'Mekit\Bundle\ContactBundle\Entity\Contact';

	public function testHasEmail() {
		$email = new Email();
		$entity = new Contact();
		$this->assertFalse($entity->hasEmail($email));
		$entity->addEmail($email);
		$this->assertTrue($entity->hasEmail($email));
	}

	public function testEmailFields() {
		$entity = new Contact();
		$this->assertNull($entity->getEmailFields());
	}

	public function testGetClass() {
		$entity = new Contact();
		$this->assertEquals($this->entityName, $entity->getClass());
	}

	public function testEmails() {
		$emailOne = new Email('one@test.com');
		$emailTwo = new Email('two@test.com');
		$emailThree = new Email('three@test.com');
		$emails = array($emailOne, $emailTwo);

		$entity = new Contact();
		$this->assertSame($entity, $entity->resetEmails($emails));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($emails, $actual->toArray());

		$this->assertSame($entity, $entity->addEmail($emailTwo));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($emails, $actual->toArray());

		$this->assertSame($entity, $entity->addEmail($emailThree));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($emailOne, $emailTwo, $emailThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeEmail($emailOne));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $emailTwo, 2 => $emailThree), $actual->toArray());

		$this->assertSame($entity, $entity->removeEmail($emailOne));
		$actual = $entity->getEmails();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array(1 => $emailTwo, 2 => $emailThree), $actual->toArray());
	}

	public function testGetPrimaryEmail() {
		$entity = new Contact();
		$this->assertNull($entity->getEmail());
		$this->assertNull($entity->getPrimaryEmail());

		$email = new Email('email@example.com');
		$entity->addEmail($email);
		$this->assertNull($entity->getPrimaryEmail());

		$entity->setPrimaryEmail($email);
		$this->assertSame($email, $entity->getPrimaryEmail());
		//$this->assertSame($email->getEmail(), $entity->getEmail());

		$email2 = new Email('new@example.com');
		$entity->addEmail($email2);
		$entity->setPrimaryEmail($email2);

		$this->assertSame($email2, $entity->getPrimaryEmail());
		//$this->assertSame($email2->getEmail(), $entity->getEmail());
		$this->assertFalse($email->isPrimary());
	}
}