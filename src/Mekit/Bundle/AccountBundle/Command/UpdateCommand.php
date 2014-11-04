<?php
namespace Mekit\Bundle\AccountBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Oro\Bundle\InstallerBundle\CommandExecutor;

/**
 * Class UpdateCommand
 * @package Mekit\Bundle\AccountBundle\Command
 */
class UpdateCommand extends ContainerAwareCommand {

	/**
	 * @inheritdoc
	 */
	protected function configure() {
		$this->setName('mekit:update:platform')
			->setDescription('Update entire platform.')
			->addOption(
				'force',
				null,
				InputOption::VALUE_NONE,
				'Forces operation to be executed.'
			)
			->addOption(
				'timeout',
				null,
				InputOption::VALUE_OPTIONAL,
				'Timeout for child command execution',
				CommandExecutor::DEFAULT_TIMEOUT
			)
			->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the assets instead of copying it');
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Not yet implemented :-(');
	}
}