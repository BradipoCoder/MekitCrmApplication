<?php

namespace Mekit\Bundle\PlatformBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MekitPlatformBundle extends Bundle
{
	public function getParent()
	{
		return 'OroPlatformBundle';
	}
}
