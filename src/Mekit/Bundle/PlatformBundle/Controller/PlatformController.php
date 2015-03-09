<?php
namespace Mekit\Bundle\PlatformBundle\Controller;

use Oro\Bundle\PlatformBundle\Controller\PlatformController as OroPlatformController;

use Composer\Package\PackageInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\PlatformBundle\Composer\LocalRepositoryFactory;
use Oro\Bundle\SecurityBundle\Annotation\Acl;

class PlatformController extends OroPlatformController {
	const MEKIT_NAMESPACE       = 'mekit';
	const SYMFONY_NAMESPACE     = 'symfony';
	const DOCTRINE_NAMESPACE    = 'doctrine';

	/**
	 * @Route("/information", name="oro_platform_system_info")
	 * @Template()
	 *
	 * @Acl(
	 *     id="oro_platform_system_info",
	 *     label="oro.platform.system_info",
	 *     type="action"
	 * )
	 */
	public function systemInfoAction()
	{
		$packages = $this->getLocalRepositoryFactory()->getLocalRepository()->getCanonicalPackages();
		$symfonyPackages
			= $doctrinePackages
			= $oroPackages
			= $mekitPackages
			= $thirdPartyPackages
			= [];

		usort($packages, function(PackageInterface $a, PackageInterface $b) {
				return strcmp($a->getPrettyName(), $b->getPrettyName());
			}
		);

		foreach ($packages as $package) {
			/** @var PackageInterface $package */
			if (0 === strpos($package->getName(), self::SYMFONY_NAMESPACE . self::NAMESPACE_DELIMITER)) {
				$symfonyPackages[] = $package;
			} elseif (0 === strpos($package->getName(), self::DOCTRINE_NAMESPACE . self::NAMESPACE_DELIMITER)) {
				$doctrinePackages[] = $package;
			} elseif (0 === strpos($package->getName(), self::ORO_NAMESPACE . self::NAMESPACE_DELIMITER)) {
				$oroPackages[] = $package;
			} elseif (0 === strpos($package->getName(), self::MEKIT_NAMESPACE . self::NAMESPACE_DELIMITER)) {
				$mekitPackages[] = $package;
			} else {
				$thirdPartyPackages[] = $package;
			}
		}

		return [
			'thirdPartyPackages' => $thirdPartyPackages,
			'symfonyPackages'    => $symfonyPackages,
			'doctrinePackages'    => $doctrinePackages,
			'oroPackages'        => $oroPackages,
			'mekitPackages'      => $mekitPackages
		];
	}
}