<?php

namespace Appsco\Dashboard\ApiBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Account implements \Serializable
{
    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $email;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $firstName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $lastName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $locale;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $timezone;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $gender;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $country;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $phone;

    /**
     * @var string
     * @JMS\Type("string")
     */
    protected $pictureUrl;


    /**
     * @param string $country
     * @return $this|Account
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $email
     * @return $this|Account
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $firstName
     * @return $this|Account
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $gender
     * @return $this|Account
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $id
     * @return $this|Account
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $lastName
     * @return $this|Account
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $locale
     * @return $this|Account
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $phone
     * @return $this|Account
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $pictureUrl
     * @return $this|Account
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $timezone
     * @return $this|Account
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }




    public function __toString()
    {
        return $this->getEmail();
    }


    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->locale,
            $this->timezone,
            $this->gender,
            $this->country,
            $this->phone,
            $this->pictureUrl,
        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            $this->locale,
            $this->timezone,
            $this->gender,
            $this->country,
            $this->phone,
            $this->pictureUrl,
        ) = unserialize($serialized);
    }

}