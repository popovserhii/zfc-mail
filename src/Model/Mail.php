<?php
namespace Popov\ZfcMail\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Popov\ZfcCore\Model\DomainAwareTrait;
use Popov\ZfcEntity\Model\Entity;
use Popov\ZfcStatus\Model\Status;
use Popov\ZfcRole\Model\Role;

/**
 * Mail
 */
class Mail
{
    use DomainAwareTrait;

    /** types of mail */
    CONST TYPE_MAIL = 'mail';

    CONST TYPE_REMINDER = 'reminder';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $theme;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $hidden;

    /**
     * @var string
     */
    private $type = self::TYPE_MAIL;

    /**
     * @var integer
     */
    private $statusId;

    /**
     * @var string
     */
    private $info;

    /**
     * @var \DateTime
     * @deprecated
     */
    private $lastDateCron;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @deprecated
     */
    private $mailOption;

    private $mailOptionRole;

    private $mailRoles;

    /**
     * @var Entity
     */
    private $entity;

    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->mailOption = new ArrayCollection();
        $this->mailOptionRole = new ArrayCollection();
        $this->mailRoles = new ArrayCollection();
    }

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
     * Set theme
     *
     * @param string $theme
     * @return Mail
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Mail
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Mail
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set hidden
     *
     * @param string $hidden
     * @return Mail
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return string
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Mail
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set statusId
     *
     * @param integer $statusId
     * @return Mail
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Mail
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set lastDateCron
     *
     * @param \DateTime $lastDateCron
     * @return Mail
     */
    public function setLastDateCron($lastDateCron)
    {
        $this->lastDateCron = $lastDateCron;

        return $this;
    }

    /**
     * Get lastDateCron
     *
     * @return \DateTime
     */
    public function getLastDateCron()
    {
        return $this->lastDateCron;
    }

    /**
     * Add mailOption
     *
     * @param \Popov\ZfcMail\Model\MailOption $mailOption
     * @return Mail
     */
    public function addMailOption(\Popov\ZfcMail\Model\MailOption $mailOption)
    {
        $this->mailOption[] = $mailOption;

        return $this;
    }

    /**
     * Remove mailOption
     *
     * @param \Popov\ZfcMail\Model\MailOption $mailOption
     */
    public function removeMailOption(\Popov\ZfcMail\Model\MailOption $mailOption)
    {
        $this->mailOption->removeElement($mailOption);
    }

    /**
     * Get mailOption
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMailOption()
    {
        return $this->mailOption;
    }

    /**
     * Add mailOptionRole
     *
     * @param \Popov\ZfcMail\Model\MailOptionRole $mailOptionRole
     * @return Mail
     */
    public function addMailOptionRole(\Popov\ZfcMail\Model\MailOptionRole $mailOptionRole)
    {
        $this->mailOptionRole[] = $mailOptionRole;

        return $this;
    }

    /**
     * Remove mailOptionRole
     *
     * @param \Popov\ZfcMail\Model\MailOptionRole $mailOptionRole
     */
    public function removeMailOptionRole(\Popov\ZfcMail\Model\MailOptionRole $mailOptionRole)
    {
        $this->mailOptionRole->removeElement($mailOptionRole);
    }

    /**
     * @return mixed
     */
    public function getMailOptionRole()
    {
        return $this->mailOptionRole;
    }

    /**
     * @param mixed $mailOptionRole
     * @return Mail
     */
    public function setMailOptionRole($mailOptionRole)
    {
        $this->mailOptionRole = $mailOptionRole;

        return $this;
    }

    /**
     * @return MailRole[]
     */
    public function getMailRoles()
    {
        return $this->mailRoles;
    }

    /**
     * @param MailRole[] $mailRoles
     * @return Mail
     */
    public function setMailRoles($mailRoles)
    {
        $this->mailRoles = $mailRoles;

        return $this;
    }

    public function getRoles()
    {
        $roles = new ArrayCollection();
        foreach ($this->getMailRoles() as $mailRole) {
            $roles->add($mailRole->getRole());
        }

        return $roles;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return Mail
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
