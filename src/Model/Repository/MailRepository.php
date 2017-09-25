<?php
namespace Popov\ZfcMail\Model\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use	Doctrine\ORM\Query\ResultSetMappingBuilder;
use Popov\ZfcCore\Model\Repository\EntityRepository;

class MailRepository extends EntityRepository
{

	protected $_table = 'mail';
	protected $_alias = 'm';


	/**
	 * @param string $hidden
	 * @param string $type
	 * @param array $fields
	 * @return array
	 */
	public function findAll($hidden = '0', $type = 'mail', array $fields = [])
	{
		$rsm = new ResultSetMapping();

		$rsm->addEntityResult($this->getEntityName(), $this->_alias);
		$rsm->addFieldResult($this->_alias, 'id', 'id');
		$rsm->addFieldResult($this->_alias, 'type', 'type');
		$rsm->addFieldResult($this->_alias, 'theme', 'theme');
		$rsm->addFieldResult($this->_alias, 'statusId', 'statusId');
		$rsm->addFieldResult($this->_alias, 'lastDateCron', 'lastDateCron');
		$rsm->addScalarResult('status', 'status');
		$rsm->addScalarResult('mnemo', 'mnemo');

		$select = '';

		foreach ($fields as $field)
		{
			$rsm->addFieldResult($this->_alias, $field, $field);
			$select .= ", {$this->_alias}.`{$field}`";
		}

		$where = '';
		$args = [$hidden];

		if ($type != '')
		{
			$where .= "AND {$this->_alias}.`type` = ?";
			$args[] = $type;
		}

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.`id`, {$this->_alias}.`type`, {$this->_alias}.`theme`, {$this->_alias}.`statusId`,
			{$this->_alias}.`lastDateCron`, (s.`name`) AS status, e.`mnemo` {$select}
			FROM {$this->_table} {$this->_alias}
			INNER JOIN `status` s ON {$this->_alias}.`statusId` = s.`id`
			INNER JOIN `entity` e ON s.`entityId` = e.`id`
			WHERE {$this->_alias}.`hidden` = ? {$where}
			ORDER BY id",
			$rsm
		);

		$query = $this->setParametersByArray($query, $args);

		return $query->getResult();
	}

	/**
	 * @param int $id
	 * @param string $field
	 * @param string $type
	 * @return mixed
	 */
	public function findOneItem($id, $field = 'id', $type = 'mail')
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$where = '';
		$args = [$id];

		if ($type != '')
		{
			$where .= "AND {$this->_alias}.`type` = ?";
			$args[] = $type;
		}

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			WHERE {$this->_alias}.`{$field}` = ? {$where}
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, $args);

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
	 * @param string $mnemo
	 * @param string $mnemoEntity
	 * @return mixed
	 */
	public function findOneItemByMnemo($mnemo, $mnemoEntity)
	{
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata($this->getEntityName(), $this->_alias);

		$query = $this->_em->createNativeQuery(
			"SELECT {$this->_alias}.*
			FROM {$this->_table} {$this->_alias}
			INNER JOIN `status` s ON {$this->_alias}.`statusId` = s.`id` AND s.`mnemo` = ?
			INNER JOIN `entity` e ON s.`entityId` = e.`id` AND e.`mnemo` = ?
			LIMIT 1",
			$rsm
		);

		$query = $this->setParametersByArray($query, [$mnemo, $mnemoEntity]);

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