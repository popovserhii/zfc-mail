<?php
namespace Popov\ZfcMail\Service;

use Popov\Agere\Service\AbstractEntityService;

class MailOptionService extends AbstractEntityService {

	protected $_repositoryName = 'mailOption';


	public function getItemsByFieldToString($items, $field)
	{
		$emails = '';
		$method = 'get'.ucfirst($field);

		foreach ($items as $item)
		{
			if ($emails != '')
			{
				$emails .= ', ';
			}

			$emails .= $item->$method();
		}

		return $emails;
	}

	/**
	 * @param int $mailId
	 * @param string $emailTo
	 * @return mixed
	 */
	public function getOneItemByEmailTo($mailId, $emailTo)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findOneItemByEmailTo($mailId, $emailTo);
	}

	/**
	 * @param int $mailId
	 * @return mixed
	 */
	public function getItemsByMailId($mailId)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findItemsByMailId($mailId);
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @return mixed
	 */
	public function getOneItem($id, $field = 'id')
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);

		return $repository->findOneItem($id, $field);
	}

	/**
	 * @param \Popov\ZfcMail\Model\Mail $oneItem
	 * @param array $data
	 * @param string $emails, separator ,
	 */
	public function saveData(\Popov\ZfcMail\Model\Mail $oneItem, $data, $emails)
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);
		$explodeData = array_diff(explode(',', $emails), [null]);
		$optionIds = [];

		if ($explodeData)
		{
			foreach ($explodeData as $val)
			{
				$emailTo = trim($val);
				$oneOption = $this->getOneItemByEmailTo($oneItem->getId(), $emailTo);

				$oneOption->setPeriod($data['period']);
				$oneOption->setEmailTo($emailTo);
				$oneOption->setMail($oneItem);
				$oneOption->setStep($data['step']);

				$repository->addItem($oneOption);

				if ($oneOption->getId())
				{
					$optionIds[$oneOption->getId()] = '';
				}
			}

			if ($optionIds)
			{
				$this->deleteData($oneItem->getId(), $optionIds);
			}

			if (isset($oneOption))
			{
				$repository->saveData();
			}
		}
		else
		{
			$this->deleteData($oneItem->getId());
		}
	}

	/**
	 * @param int $mailId
	 * @param array $notDelete
	 */
	public function deleteData($mailId, array $notDelete = [])
	{
		/** @var \Popov\ZfcMail\Model\Repository\MailOptionRepository $repository */
		$repository = $this->getRepository($this->_repositoryName);
		$options = $this->getItemsByMailId($mailId);

		foreach ($options as $item)
		{
			if (! isset($notDelete[$item->getId()]))
			{
				$repository->addRemove($item);
			}
		}

		if (isset($item))
		{
			$repository->saveData();
		}
	}

}