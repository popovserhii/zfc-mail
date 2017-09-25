<?php
namespace Popov\ZfcMail\Service;

use Popov\Agere\Service\AbstractEntityService;

class MailOptionRoleService extends AbstractEntityService {

	protected $_repositoryName = 'mailOptionRole';


	/**
	 * @param int $mailId
	 * @return mixed
	 */
	public function getItemsByMailId($mailId)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRoleRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findItemsByMailId($mailId);
	}

	/**
	 * @param int $mailId
	 * @param int $roleId
	 * @return mixed
	 */
	public function getOneItemByRoleId($mailId, $roleId)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRoleRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findOneItemByRoleId($mailId, $roleId);
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @return mixed
	 */
	public function getOneItem($id, $field = 'id')
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRoleRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findOneItem($id, $field);
	}

	/**
	 * @param int $mailId
	 * @param null|int $cityId
	 * @return array
	 */
	/*public function getEmailsByMailId($mailId, $cityId = null)
	{
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findEmailsByMailId($mailId, $cityId);
	}*/

	/**
	 * @param \Popov\ZfcMail\Model\Mail $oneItem
	 * @param array $roles
	 */
	public function saveData(\Popov\ZfcMail\Model\Mail $oneItem, $roles)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRoleRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		foreach ($roles as $roleId => $data)
		{
			if (! $data['id'])
			{
				$oneOption = $this->getOneItemByRoleId($oneItem->getId(), $roleId);
				$oneRole = $this->getService('roles')->getOneItem($roleId);

				$oneOption->setRoles($oneRole);
				$oneOption->setMail($oneItem);
			}
			else
			{
				$oneOption = $this->getOneItem($data['id']);
			}

			$oneOption->setCityCreator($data['cityCreator']);
			$oneOption->setByBrand($data['byBrand']);
			$oneOption->setCityIn($data['cityIn']);

			$repository->addItem($oneOption);
		}

		if (isset($oneOption))
		{
			$repository->saveData();
		}
	}

	/**
	 * @param array $data
	 */
	public function deleteData($data)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRoleRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		foreach ($data as $roleId => $id)
		{
			$item = $this->getOneItem($id);
			$repository->addRemove($item);
		}

		if (isset($item))
		{
			$repository->saveData();
		}
	}

}