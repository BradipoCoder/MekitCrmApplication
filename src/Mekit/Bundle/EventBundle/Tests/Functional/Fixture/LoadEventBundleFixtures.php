<?php
namespace Mekit\Bundle\EventBundle\Tests\Functional\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Mekit\Bundle\EventBundle\Entity\Event;
use Mekit\Bundle\ListBundle\Entity\Repository\ListItemRepository;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Mekit\Bundle\TaskBundle\Entity\Task;
use Mekit\Bundle\CallBundle\Entity\Call;
use Mekit\Bundle\MeetingBundle\Entity\Meeting;

class LoadEventBundleFixtures extends AbstractFixture implements ContainerAwareInterface
{
	/** @var ObjectManager */
	protected $em;

	/** @var User */
	protected $user;

	/** @var Organization */
	protected $organization;

	/** @var ListItemRepository */
	protected $listItemRepo;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		//
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$this->em = $manager;
		$this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

		/** @var ListItemRepository $listItemRepo */
		$this->listItemRepo = $manager->getRepository('MekitListBundle:ListItem');

		$this->createTask();
		$this->createCall();
		$this->createMeeting();
	}

	protected function createTask()
	{
		$task = new Task();
		$task->setName("Do something");
		$task->setOrganization($this->organization);
		$task->setOwner($this->getUser());

		$event = new Event();
		$event->setType("Task");
		$event->setStartDate(new \DateTime());
		$event->setState($this->listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
		$event->setPriority($this->listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));

		$task->setEvent($event);
		$event->setTask($task);

		$this->em->persist($task);
		$this->em->flush();

		$this->setReference('default_task', $task);

		return $this;
	}

	protected function createCall()
	{
		$call = new Call();
		$call->setName("Call someone");
		$call->setOrganization($this->organization);
		$call->setOwner($this->getUser());
		$call->setDirection('out');
		$call->setOutcome($this->listItemRepo->getDefaultItemForGroup("CALL_OUTCOME"));

		$event = new Event();
		$event->setType("Call");
		$event->setStartDate(new \DateTime());
		$event->setState($this->listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
		$event->setPriority($this->listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));

		$call->setEvent($event);
		$event->setCall($call);

		$this->em->persist($call);
		$this->em->flush();

		$this->setReference('default_call', $call);

		return $this;
	}

	protected function createMeeting()
	{
		$meeting = new Meeting();
		$meeting->setName("Meet someone");
		$meeting->setOrganization($this->organization);
		$meeting->setOwner($this->getUser());

		$event = new Event();
		$event->setType("Meeting");
		$event->setStartDate(new \DateTime());
		$event->setState($this->listItemRepo->getDefaultItemForGroup("EVENT_STATE"));
		$event->setPriority($this->listItemRepo->getDefaultItemForGroup("EVENT_PRIORITY"));

		$meeting->setEvent($event);
		$event->setMeeting($meeting);

		$this->em->persist($meeting);
		$this->em->flush();

		$this->setReference('default_meeting', $meeting);

		return $this;
	}

	/**
	 * @return User
	 */
	protected function getUser()
	{
		if (empty($this->user)) {
			$this->user = $this->em->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
		}

		return $this->user;
	}
}



