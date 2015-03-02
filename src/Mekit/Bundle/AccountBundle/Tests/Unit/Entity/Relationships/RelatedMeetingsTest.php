<?php
namespace Mekit\Bundle\AccountBundle\Tests\Unit\Entity\Relationships;

use Mekit\Bundle\TestBundle\Helpers\MekitUnitEntityTest;
use Doctrine\Common\Collections\ArrayCollection;
use Mekit\Bundle\AccountBundle\Entity\Account;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;

class RelatedMeetingsTest extends MekitUnitEntityTest {

	public function testRelatedMeetings() {
		$meetingOne = new Meeting();
		$meetingOne->setName("meeting-one");
		$meetingTwo = new Meeting();
		$meetingTwo->setName("meeting-two");

		$entity = new Account();
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$this->assertSame($entity, $entity->addMeeting($meetingOne));
		$this->assertTrue($entity->hasMeeting($meetingOne));
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($meetingOne), $actual->toArray());

		$this->assertSame($entity, $entity->addMeeting($meetingTwo));
		$this->assertTrue($entity->hasMeeting($meetingTwo));
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($meetingOne, $meetingTwo), $actual->toArray());

		$this->assertSame($entity, $entity->removeMeeting($meetingOne));
		$this->assertFalse($entity->hasMeeting($meetingOne));
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals(array($meetingTwo), array_values($actual->toArray()));

		$this->assertSame($entity, $entity->removeMeeting($meetingTwo));
		$this->assertFalse($entity->hasMeeting($meetingTwo));
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertTrue($actual->isEmpty());

		$meetings = new ArrayCollection(array($meetingOne, $meetingTwo));
		$this->assertSame($entity, $entity->setMeetings($meetings));
		$this->assertTrue($entity->hasMeeting($meetingOne));
		$this->assertTrue($entity->hasMeeting($meetingTwo));
		$actual = $entity->getMeetings();
		$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $actual);
		$this->assertEquals($meetings, $actual);
	}
}