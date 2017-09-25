<?php

namespace Popov\ZfcMail\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailOptionRole
 * @deprecated
 */
class MailOptionRole
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $roleId;

    /**
     * @var integer
     */
    private $mailId;

	/**
	 * @var string
	 */
	private $cityCreator;

	/**
	 * @var string
	 */
	private $byBrand;

	/**
	 * @var string
	 */
	private $cityIn;

	/**
     * @var \Popov\ZfcMail\Model\Mail
     */
    private $mail;

    /**
     * @var \Popov\Roles\Model\Roles
     */
    private $roles;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set roleId
     *
     * @param integer $roleId
     * @return MailOptionRole
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return integer 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set mailId
     *
     * @param integer $mailId
     * @return MailOptionRole
     */
    public function setMailId($mailId)
    {
        $this->mailId = $mailId;

        return $this;
    }

    /**
     * Get mailId
     *
     * @return integer 
     */
    public function getMailId()
    {
        return $this->mailId;
    }

	/**
	 * Set cityCreator
	 *
	 * @param string $cityCreator
	 * @return Mail
	 */
	public function setCityCreator($cityCreator)
	{
		$this->cityCreator = $cityCreator;

		return $this;
	}

	/**
	 * Get cityCreator
	 *
	 * @return string
	 */
	public function getCityCreator()
	{
		return $this->cityCreator;
	}

	/**
	 * Set byBrand
	 *
	 * @param string $byBrand
	 * @return Mail
	 */
	public function setByBrand($byBrand)
	{
		$this->byBrand = $byBrand;

		return $this;
	}

	/**
	 * Get byBrand
	 *
	 * @return string
	 */
	public function getByBrand()
	{
		return $this->byBrand;
	}

	/**
	 * Set cityIn
	 *
	 * @param string $cityIn
	 * @return Mail
	 */
	public function setCityIn($cityIn)
	{
		$this->cityIn = $cityIn;

		return $this;
	}

	/**
	 * Get cityIn
	 *
	 * @return string
	 */
	public function getCityIn()
	{
		return $this->cityIn;
	}

	/**
     * Set mail

     *
*@param \Popov\ZfcMail\Model\Mail $mail
     * @return MailOptionRole
     */
    public function setMail(\Popov\ZfcMail\Model\Mail $mail = null)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail

     *
*@return \Popov\ZfcMail\Model\Mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set roles
     *
     * @param \Popov\Roles\Model\Roles $roles
     * @return MailOptionRole
     */
    public function setRoles(\Popov\Roles\Model\Roles $roles = null)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return \Popov\Roles\Model\Roles
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
