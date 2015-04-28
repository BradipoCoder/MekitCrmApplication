<?php
namespace Mekit\Bundle\MeetingBundle\Provider;

use Doctrine\ORM\AbstractQuery;
use Oro\Bundle\ReminderBundle\Entity\Manager\ReminderManager;

class MeetingCalendarNormalizer
{
	/** @var ReminderManager */
	protected $reminderManager;
	/**
	 * @param ReminderManager $reminderManager
	 */
	public function __construct(ReminderManager $reminderManager)
	{
		$this->reminderManager = $reminderManager;
	}
	/**
	 * @param int           $calendarId
	 * @param AbstractQuery $query
	 *
	 * @return array
	 */
	public function getMeetings($calendarId, AbstractQuery $query)
	{
		$result = [];
		$items  = $query->getArrayResult();
		foreach ($items as $item) {
			/** @var \DateTime $startDate */
			$startDate = $item['startDate'];
			/** @var \DateTime $endDate */
			$endDate = $item['endDate'];
			//
			$allDay = false;

			$result[] = [
				'calendar'    => $calendarId,
				'id'          => $item['id'],
				'title'       => $item['name'],
				'description' => $item['description'],
				'start'       => $startDate->format('c'),
				'end'         => $endDate->format('c'),
				'allDay'      => $allDay,
				'createdAt'   => $item['createdAt']->format('c'),
				'updatedAt'   => $item['updatedAt']->format('c'),
				'editable'    => true,
				'removable'   => true
			];
		}
		$this->reminderManager->applyReminders($result, 'Mekit\Bundle\MeetingBundle\Entity\Meeting');
		return $result;
	}
}