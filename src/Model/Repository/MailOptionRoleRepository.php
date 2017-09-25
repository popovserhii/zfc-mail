<?php
namespace Popov\ZfcMail\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping,
	Doctrine\ORM\Query\ResultSetMappingBuilder,
	Popov\Agere\ORM\EntityRepository;

class MailOptionRoleRepository extends EntityRepository {

	protected $_table = 'mail_option_role';
	protected $_alias = 'mor';


	public function findItemsByMailId($mailId)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`mailId` = ?",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$mailId]);

		return $query->getResult();
	}

	/**
	 * @param int $mailId
	 * @param int $roleId
	 * @return mixed
	 */
	public function findOneItemByRoleId($mailId, $roleId)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`mailId` = ? AND {$this->_alias}.`roleId` = ?
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$mailId, $roleId]);

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