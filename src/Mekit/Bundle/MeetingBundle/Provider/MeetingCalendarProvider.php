<?php
namespace Mekit\Bundle\MeetingBundle\Provider;

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

	/** @var  bool */
	protected $calendarLabels = [
		self::MY_MEETING_CALENDAR_ID => 'mekit.meeting.menu.my_meetings'
	];

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
			/** @var MeetingRepository $repo */
			$repo  = $this->doctrineHelper->getEntityRepository('MekitMeetingBundle:Meeting');
			$qb    = $repo->getMeetingListByTimeIntervalQueryBuilder($userId, $start, $end, $extraFields);
			$query = $this->aclHelper->apply($qb);

			return $this->meetingCalendarNormalizer->getMeetings(self::MY_MEETING_CALENDAR_ID, $query);
		}

		return [];
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