<?php
namespace Mekit\Bundle\TaskBundle\Provider;

use Symfony\Component\Translation\TranslatorInterface;
use Oro\Bundle\CalendarBundle\Provider\CalendarProviderInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Mekit\Bundle\CrmBundle\Provider\CalendarProvider;
use Mekit\Bundle\TaskBundle\Entity\Repository\TaskRepository;

class TaskCalendarProvider extends CalendarProvider implements CalendarProviderInterface
{
	const ALIAS = 'tasks';
	const MY_TASK_CALENDAR_ID = 1001;

	/** @var TaskCalendarNormalizer */
	protected $taskCalendarNormalizer;

	/** @var bool */
	protected $myTasksEnabled;

	/** @var  bool */
	protected $calendarLabels = [
		self::MY_TASK_CALENDAR_ID => 'mekit.task.menu.my_tasks'
	];

	/**
	 * @param DoctrineHelper         $doctrineHelper
	 * @param AclHelper              $aclHelper
	 * @param TaskCalendarNormalizer $taskCalendarNormalizer
	 * @param TranslatorInterface    $translator
	 */
	public function __construct(
		DoctrineHelper $doctrineHelper,
		AclHelper $aclHelper,
		TaskCalendarNormalizer $taskCalendarNormalizer,
		TranslatorInterface $translator
	) {
		$this->doctrineHelper               = $doctrineHelper;
		$this->aclHelper                    = $aclHelper;
		$this->taskCalendarNormalizer       = $taskCalendarNormalizer;
		$this->translator                   = $translator;
		$this->myTasksEnabled               = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCalendarDefaultValues($organizationId, $userId, $calendarId, array $calendarIds)
	{
		$result = [];

		if ($this->myTasksEnabled) {
			$result[self::MY_TASK_CALENDAR_ID] = [
				'calendarName'    => $this->translator->trans($this->calendarLabels[self::MY_TASK_CALENDAR_ID]),
				'removable'       => false,
				'position'        => -300,
				'backgroundColor' => '#FFB266',
				'options'         => [
					'widgetRoute'   => 'mekit_task_widget_info',
					'widgetOptions' => [
						'title'         => $this->translator->trans('mekit.task.widgets.task_information'),
						'dialogOptions' => [
							'width' => 600
						]
					]
				]
			];
		} elseif (in_array(self::MY_TASK_CALENDAR_ID, $calendarIds)) {
			$result[self::MY_TASK_CALENDAR_ID] = null;
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
		if (!$this->myTasksEnabled) {
			return [];
		}

		if ($this->isCalendarVisible($connections, self::MY_TASK_CALENDAR_ID)) {

			$this->userCalendarProps = $this->getListOfVisibleUserCalendars($calendarId);
			$uid = $this->getUserIdListFromUserCalendarProps();

			/** @var TaskRepository $repo */
			$repo  = $this->doctrineHelper->getEntityRepository('MekitTaskBundle:Task');
			$qb    = $repo->getTaskListByTimeIntervalQueryBuilder($uid, $start, $end, $extraFields);
			$query = $this->aclHelper->apply($qb);

			return $this->taskCalendarNormalizer->getTasks(self::MY_TASK_CALENDAR_ID, $query);
		}

		return [];
	}
}