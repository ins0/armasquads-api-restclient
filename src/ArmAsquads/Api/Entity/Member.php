<?php
namespace ArmAsquads\Api\Entity;

class Member
{
    protected $uuid;
    protected $username;
    protected $name;
    protected $email;
    protected $icq;
    protected $remark;

    /**
     * Exchange API response to Squad Object
     *
     * @param $array
     * @return $this
     */
    public function exchangeArray($array)
    {
        $self = $this;
        $vars = get_class_vars(get_class($this));
        array_map(function($v, $k) use ($vars, $self) {
            if( method_exists($self, 'set' . ucwords(strtolower($k)) ) )
            {
                call_user_func(array($self, 'set' . ucwords(strtolower($k))), $v);
            }
        }, $array, array_keys($array));

        return $this;
    }

    /**
     * Get ArrayCopy of Object
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $icq
     */
    public function setIcq($icq)
    {
        $this->icq = $icq;
    }

    /**
     * @return mixed
     */
    public function getIcq()
    {
        return $this->icq;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}