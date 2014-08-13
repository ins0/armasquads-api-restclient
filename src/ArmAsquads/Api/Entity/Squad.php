<?php
namespace ArmAsquads\Api\Entity;

class Squad
{
    protected $id;
    protected $privateID;
    protected $tag;
    protected $name;
    protected $email;
    protected $logo;
    protected $homepage;
    protected $title;

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
     * Get the Squad XML Url
     * @return string
     */
    public function getSquadXmlUrl()
    {
        return 'http://armasquads.com/user/squads/xml/'.$this->getPrivateID().'/squad.xml';
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
     * @param mixed $homepage
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    }

    /**
     * @return mixed
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
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
     * @param mixed $privateID
     */
    public function setPrivateID($privateID)
    {
        $this->privateID = $privateID;
    }

    /**
     * @return mixed
     */
    public function getPrivateID()
    {
        return $this->privateID;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}