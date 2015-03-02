<?php
namespace Mekit\Bundle\EventBundle\Entity;

interface EventInterface {
	/**
	 * @return Event
	 */
	public function getEvent();

	/**
	 * @param Event $event
	 * @return EventInterface
	 */
	public function setEvent(Event $event);

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $name
	 * @return EventInterface
	 */
	public function setName($name);
}