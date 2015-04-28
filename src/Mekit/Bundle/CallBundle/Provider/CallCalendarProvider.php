<?php
namespace Mekit\Bundle\CallBundle\Provider;

use Symfony\Component\Translation\TranslatorInterface;
use Oro\Bundle\CalendarBundle\Provider\CalendarProviderInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Mekit\Bundle\CallBundle\Entity\Repository\CallRepository;

class CallCalendarProvider implements CalendarProviderInterface
{
	const ALIAS = 'calls';
	const MY_CALL_CALENDAR_ID = 1002;

	/** @var DoctrineHelper */
	protected $doctrineHelper;

	/** @var AclHelper */
	protected $aclHelper;

	/** @var CallCalendarNormalizer */
	protected $callCalendarNormalizer;

	/** @var TranslatorInterface */
	protected $translator;

	/** @var bool */
	protected $myCallsEnabled;

	/** @var  bool */
	protected $calendarLabels = [
		self::MY_CALL_CALENDAR_ID => 'mekit.call.menu.my_calls'
	];

	/**
	 * @param DoctrineHelper         $doctrineHelper
	 * @param AclHelper              $aclHelper
	 * @param CallCalendarNormalizer $callCalendarNormalizer
	 * @param TranslatorInterface    $translator
	 */
	public function __construct(
		DoctrineHelper $doctrineHelper,
		AclHelper $aclHelper,
		CallCalendarNormalizer $callCalendarNormalizer,
		TranslatorInterface $translator
	) {
		$this->doctrineHelper               = $doctrineHelper;
		$this->aclHelper                    = $aclHelper;
		$this->callCalendarNormalizer       = $callCalendarNormalizer;
		$this->translator                   = $translator;
		$this->myCallsEnabled               = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCalendarDefaultValues($organizationId, $userId, $calendarId, array $calendarIds)
	{
		$result = [];

		if ($this->myCallsEnabled) {
			$result[self::MY_CALL_CALENDAR_ID] = [
				'calendarName'    => $this->translator->trans($this->calendarLabels[self::MY_CALL_CALENDAR_ID]),
				'removable'       => false,
				'position'        => -200,
				'backgroundColor' => '#B2FF66',
				'options'         => [
					'widgetRoute'   => 'mekit_call_widget_info',
					'widgetOptions' => [
						'title'         => $this->translator->trans('mekit.call.widgets.call_information'),
						'dialogOptions' => [
							'width' => 600
						]
					]
				]
			];
		} elseif (in_array(self::MY_CALL_CALENDAR_ID, $calendarIds)) {
			$result[self::MY_CALL_CALENDAR_ID] = null;
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
		if (!$this->myCallsEnabled) {
			return [];
		}

		if ($this->isCalendarVisible($connections, self::MY_CALL_CALENDAR_ID)) {
			/** @var CallRepository $repo */
			$repo  = $this->doctrineHelper->getEntityRepository('MekitCallBundle:Call');
			$qb    = $repo->getCallListByTimeIntervalQueryBuilder($userId, $start, $end, $extraFields);
			$query = $this->aclHelper->apply($qb);

			return $this->callCalendarNormalizer->getCalls(self::MY_CALL_CALENDAR_ID, $query);
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