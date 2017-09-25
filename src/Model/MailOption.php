<?php

namespace Popov\ZfcMail\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailOption
 */
class MailOption
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var integer
	 */
	private $period;

	/**
	 * @var string
	 */
	private $emailTo;

	/**
	 * @var integer
	 */
	private $mailId;

	/**
	 * @var integer
	 */
	private $step;

	/**
	 * @var \Popov\ZfcMail\Model\Mail
	 */
	private $mail;


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
	 * Set period
	 *
	 * @param integer $period
	 * @return MailOption
	 */
	public function setPeriod($period)
	{
		$this->period = $period;

		return $this;
	}

	/**
	 * Get period
	 *
	 * @return integer
	 */
	public function getPeriod()
	{
		return $this->period;
	}

	/**
	 * Set emailTo
	 *
	 * @param string $emailTo
	 * @return MailOption
	 */
	public function setEmailTo($emailTo)
	{
		$this->emailTo = $emailTo;

		return $this;
	}

	/**
	 * Get emailTo
	 *
	 * @return string
	 */
	public function getEmailTo()
	{
		return $this->emailTo;
	}

	/**
	 * Set mailId
	 *
	 * @param integer $mailId
	 * @return MailOption
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
	 * Set step
	 *
	 * @param integer $step
	 * @return MailOption
	 */
	public function setStep($step)
	{
		$this->step = $step;

		return $this;
	}

	/**
	 * Get step
	 *
	 * @return integer
	 */
	public function getStep()
	{
		return $this->step;
	}

	/**
	 * Set mail

	 *
*@param \Popov\ZfcMail\Model\Mail $mail
	 * @return MailOption
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
}
