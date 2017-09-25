<?php
/**
 * Mail Role
 *
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 23.04.2016 17:24
 */
namespace Popov\ZfcMail\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Popov\ZfcCore\Model\DomainAwareTrait;
use Popov\Permission\Model\PermissionSettings;
use Popov\Roles\Model\Roles as Role;

class MailRole {

	use DomainAwareTrait;

	/** @var int */
	protected $id;

	/** @var Mail */
	protected $mail;

	/** @var Role */
	protected $role;

	/** @var PermissionSettings[] */
	protected $permissionSettings;

	public function __construct() {
		$this->permissionSettings = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return MailRole
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return Mail
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * @param Mail $mail
	 * @return MailRole
	 */
	public function setMail($mail) {
		$this->mail = $mail;

		return $this;
	}

	/**
	 * @return Role
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param Role $role
	 * @return MailRole
	 */
	public function setRole($role) {
		$this->role = $role;

		return $this;
	}

	/**
	 * @return PermissionSettings[]
	 */
	public function getPermissionSettings() {
		return $this->permissionSettings;
	}

	/**
	 * @param PermissionSettings[] $permissionSettings
	 * @return MailRole
	 */
	public function setPermissionSettings($permissionSettings) {
		$this->permissionSettings = $permissionSettings;

		return $this;
	}

}