<?php
namespace Popov\ZfcMail\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping,
	Doctrine\ORM\Query\ResultSetMappingBuilder,
	Popov\Agere\ORM\EntityRepository;

class MailOptionRepository extends EntityRepository {

	protected $_table = 'mail_option';
	protected $_alias = 'mo';


	/**
	 * @param int $mailId
	 * @param string $emailTo
	 * @return mixed
	 */
	public function findOneItemByEmailTo($mailId, $emailTo)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`mailId` = ? AND {$this->_alias}.`emailTo` = ?
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$mailId, $emailTo]);

		$result = $query->getResult();

		if (count($result) == 0)
		{
			$result = $this->createOneItem();
		}
		else
		{
			$result = $result[0];
		}

		return $result;
	}

	/**
	 * @param int $mailId
	 * @return mixed
	 */
	public function findItemsByMailId($mailId)
	{
		$rsm = new ResultSetMapping;

		$rsm->addEntityResult($this->getEntityName(), $this->_alias);
		$rsm->addFieldResult($this->_alias, 'id', 'id');
		$rsm->addFieldResult($this->_alias, 'emailTo', 'emailTo');

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.`id`, {$this->_alias}.`emailTo`
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`mailId` = ?",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$mailId]);

		return $query->getResult();
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @return mixed
	 */
	public function findOneItem($id, $field = 'id')
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`{$field}` = ?
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$id]);

		$result = $query->getResult();

		if (count($result) == 0)
		{
			$result = $this->createOneItem();
		}
		else
		{
			$result = $result[0];
		}

		return $result;
	}

}