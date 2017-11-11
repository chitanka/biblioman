<?php namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserSetRolesCommand extends Command {

	const ARG_USERNAME = 'username';
	const ARG_ROLES = 'roles';

	public function getName() {
		return 'user:set-roles';
	}

	public function getDescription() {
		return 'Change user roles';
	}

	public function getHelp() {
		return <<<EOT
The <info>%command.name%</info> allows changing of user roles.

Example calls:

	<info>%command.name%</info> UserNameX editor +manager /wiki_editor

		Add the roles ROLE_EDITOR and ROLE_MANAGER to user UserNameX, and remove the role ROLE_WIKI_EDITOR

EOT;
	}

	protected function getArrayArguments() {
		return [
			self::ARG_ROLES => 'A list of roles, prepended with “+” or “/”',
		];
	}

	protected function getRequiredArguments() {
		return [
			self::ARG_USERNAME => 'A username',
		];
	}

	/** {@inheritdoc} */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$username = $input->getArgument(self::ARG_USERNAME);
		$roleDefinition = $this->normalizeRoles($input->getArgument(self::ARG_ROLES));

		$em = $this->getEntityManager();
		$user = $em->getRepository(User::class)->findByUsername($username);
		$finalRoles = $this->setRoles($user, $roleDefinition);

		$em->persist($user);
		$em->flush();
		$output->writeln("The user <info>{$user}</info> has now following roles:\n    - ".implode("\n    - ", $finalRoles));
	}

	protected function setRoles(User $user, $roleDefinition) {
		foreach ($roleDefinition['add'] as $role) {
			$user->addRole($role);
		}
		foreach ($roleDefinition['remove'] as $role) {
			$user->removeRole($role);
		}
		return $user->getRoles();
	}

	protected function normalizeRoles($rolesInput) {
		$roles = ['add' => [], 'remove' => []];
		foreach ($rolesInput as $roleInput) {
			if ($roleInput[0] === '/') {
				$roles['remove'][] = $this->normalizeRole($roleInput);
			} else {
				$roles['add'][] = $this->normalizeRole($roleInput);
			}
		}
		return $roles;
	}

	protected function normalizeRole($roleInput) {
		$role = strtoupper(ltrim($roleInput, '/+'));
		if (strpos($role, User::ROLE_PREFIX) === false) {
			$role = User::ROLE_PREFIX.$role;
		}
		return $role;
	}
}
