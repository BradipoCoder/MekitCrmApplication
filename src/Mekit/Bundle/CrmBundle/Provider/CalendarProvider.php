<?php
namespace Mekit\Bundle\CrmBundle\Provider;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Oro\Bundle\CalendarBundle\Entity\Repository\CalendarPropertyRepository;
use Oro\Bundle\CalendarBundle\Entity\Repository\CalendarRepository;

class CalendarProvider {
	/** @var DoctrineHelper */
	protected $doctrineHelper;

	/** @var TranslatorInterface */
	protected $translator;

	/** @var AclHelper */
	protected $aclHelper;

	/**
	 *
		...
		[0] => Array
		(
			[id] => 1
			[calendarAlias] => user
			[calendar] => 1
			[position] => 0
			[visible] => 1
			[backgroundColor] => #4986E7
			[owner] => 1
		)
		...
	 *
	 * @var Array
	 */
	protected $userCalendarProps;



	/**
	 * @return array
	 */
	protected function getUserIdListFromUserCalendarProps()
	{
		$uid = [];
		if(count($this->userCalendarProps)) {
			foreach($this->userCalendarProps as $userCalendarsProperty) {
				$uid[] = $userCalendarsProperty["owner"];
			}
		}
		return $uid;
	}

	/**
	 * Builds and returns an array of user calendar properties + user ids
	 *
	 * @param $calendarId
	 * @return Array
	 */
	protected function getListOfVisibleUserCalendars($calendarId)
	{
		/** @var CalendarPropertyRepository $calendarPropertyRepo */
		$calendarPropertyRepo = $this->doctrineHelper->getEntityRepository('OroCalendarBundle:CalendarProperty');
		/** @var CalendarRepository $calendarRepo */
		$calendarRepo = $this->doctrineHelper->getEntityRepository('OroCalendarBundle:Calendar');

		/* get list of visible user calendars connected to this calendar*/
		$qb = $calendarPropertyRepo->createQueryBuilder("cp")
			->where('cp.targetCalendar = :targetCalendarId')
			->andWhere('cp.calendarAlias = :alias')
			->andWhere('cp.visible = true')
			->setParameter('targetCalendarId', $calendarId)
			->setParameter('alias', "user");
		$userCalendarsProperties = $qb->getQuery()->getArrayResult();

		if(!$userCalendarsProperties) {
			return [];
		}

		$cid = [];
		if ($userCalendarsProperties) {
			foreach($userCalendarsProperties as $userCalendarsProperty) {
				$cid[] = $userCalendarsProperty["calendar"];
			}
		}

		/* get user ids from calendars */
		$qb = $calendarRepo->createQueryBuilder("c")
			->select("c.id AS cid", "o.id as uid")
			->innerJoin("c.owner", "o")
			->where($qb->expr()->in('c.id', $cid));
		$calendarUserIds = $qb->getQuery()->getArrayResult();

		if ($calendarUserIds) {
			foreach($calendarUserIds as $calendarUserId) {
				foreach($userCalendarsProperties as &$userCalendarsProperty) {
					if($userCalendarsProperty["calendar"] == $calendarUserId["cid"]) {
						$userCalendarsProperty["owner"] = $calendarUserId["uid"];
						break;
					}
				}
			}
		}

		return $userCalendarsProperties;
	}

	/**
	 * @param array $connections
	 * @param int   $calendarId
	 * @param bool  $default
	 *
	 * @return bool
	 */
	protected function isCalendarVisible($connections, $calendarId, $default = true)
	{
		return isset($connections[$calendarId])
			? $connections[$calendarId]
			: $default;
	}
}