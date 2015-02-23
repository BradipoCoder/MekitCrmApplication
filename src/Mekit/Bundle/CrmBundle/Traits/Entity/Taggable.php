<?php
namespace Mekit\Bundle\CrmBundle\Traits\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;


trait Taggable {
	/**
	 * @var ArrayCollection $tags
	 * @ConfigField(
	 *      defaultValues={
	 *          "merge"={
	 *              "display"=true
	 *          }
	 *      }
	 * )
	 */
	protected $tags;

	/**
	 * {@inheritdoc}
	 */
	public function getTags() {
		$this->tags = $this->tags ?: new ArrayCollection();
		return $this->tags;
	}

	/**
	 * @param $tags
	 * @return $this
	 */
	public function setTags($tags) {
		$this->tags = $tags;
		return $this;
	}
}
