<?php
namespace Mekit\Bundle\MeetingBundle\Provider;

use Oro\Bundle\CalendarBundle\Entity\Repository\CalendarPropertyRepository;
use Oro\Bundle\CalendarBundle\Entity\Repository\CalendarRepository;
use Symfony\Component\Translation\TranslatorInterface;
use Oro\Bundle\CalendarBundle\Provider\CalendarProviderInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Mekit\Bundle\MeetingBundle\Entity\Repository\MeetingRepository;

class MeetingCalendarProvider implements CalendarProviderInterface
{
	const ALIAS = 'meetings';
	const MY_MEETING_CALENDAR_ID = 1003;

	/** @var DoctrineHelper */
	protected $doctrineHelper;

	/** @var AclHelper */
	protected $aclHelper;

	/** @var MeetingCalendarNormalizer */
	protected $meetingCalendarNormalizer;

	/** @var TranslatorInterface */
	protected $translator;

	/** @var bool */
	protected $myMeetingsEnabled;

	/** @var bool */
	protected $calendarLabels = [
		self::MY_MEETING_CALENDAR_ID => 'mekit.meeting.menu.my_meetings'
	];

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
	 * @param DoctrineHelper         $doctrineHelper
	 * @param AclHelper              $aclHelper
	 * @param MeetingCalendarNormalizer $meetingCalendarNormalizer
	 * @param TranslatorInterface    $translator
	 */
	public function __construct(
		DoctrineHelper $doctrineHelper,
		AclHelper $aclHelper,
		MeetingCalendarNormalizer $meetingCalendarNormalizer,
		TranslatorInterface $translator
	) {
		$this->doctrineHelper               = $doctrineHelper;
		$this->aclHelper                    = $aclHelper;
		$this->meetingCalendarNormalizer    = $meetingCalendarNormalizer;
		$this->translator                   = $translator;
		$this->myMeetingsEnabled            = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCalendarDefaultValues($organizationId, $userId, $calendarId, array $calendarIds)
	{
		$result = [];

		if ($this->myMeetingsEnabled) {
			$result[self::MY_MEETING_CALENDAR_ID] = [
				'calendarName'    => $this->translator->trans($this->calendarLabels[self::MY_MEETING_CALENDAR_ID]),
				'removable'       => false,
				'position'        => -100,
				'backgroundColor' => '#99CCFF',
				'options'         => [
					'widgetRoute'   => 'mekit_meeting_widget_info',
					'widgetOptions' => [
						'title'         => $this->translator->trans('mekit.meeting.widgets.meeting_information'),
						'dialogOptions' => [
							'width' => 600
						]
					]
				]
			];
		} elseif (in_array(self::MY_MEETING_CALENDAR_ID, $calendarIds)) {
			$result[self::MY_MEETING_CALENDAR_ID] = null;
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCalendarEvents(
		$organizationId,
		$userId,
		$calendarId,
		$start,
		$end,
		$connections,
		$extraFields = []
	) {
		if (!$this->myMeetingsEnabled) {
			return [];
		}

		if ($this->isCalendarVisible($connections, self::MY_MEETING_CALENDAR_ID)) {

			$this->userCalendarProps = $this->getListOfVisibleUserCalendars($calendarId);
			$uid = $this->getUserIdListFromUserCalendarProps();

			/** @var MeetingRepository $repo */
			$repo  = $this->doctrineHelper->getEntityRepository('MekitMeetingBundle:Meeting');
			$qb    = $repo->getMeetingListByTimeIntervalQueryBuilder($uid, $start, $end, $extraFields);
			$query = $this->aclHelper->apply($qb);

			return $this->meetingCalendarNormalizer->getMeetings(self::MY_MEETING_CALENDAR_ID, $query);
		}

		return [];
	}

	/**
	 * @return array
	 */
	protected function getUserIdListFromUserCalendarProps() {
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