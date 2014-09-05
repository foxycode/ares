<?php

namespace Edge\Ares\Container;

use Nette;


/**
 * Class Address
 *
 * @author Marek Makovec <marek.makovec@edgedesign.cz>
 */
class Address extends Nette\Object
{
    /** @var int */
    private $ico;

    /** @var string */
    private $dic;

    /** @var string */
    private $firma;

    /** @var string */
    private $ulice;

    /** @var int */
    private $cisloOrientacni;

    /** @var int */
    private $cisloPopisne;

    /** @var string */
    private $mesto;

    /** @var string */
    private $castObce;

    /** @var string */
    private $mestskaCast;

    /** @var int */
    private $psc;


    /**
     * @return string
     */
    public function getCastObce()
    {
        return $this->castObce;
    }

    /**
     * @return string
     */
    public function getMestskaCast()
    {
        return $this->mestskaCast;
    }

    /**
     * @return int
     */
    public function getCisloOrientacni()
    {
        return $this->cisloOrientacni;
    }

    /**
     * @return int
     */
    public function getCisloPopisne()
    {
        return $this->cisloPopisne;
    }

    /**
     * @return string
     */
    public function getDic()
    {
        return $this->dic;
    }

    /**
     * @return string
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * @return int
     */
    public function getIco()
    {
        return $this->ico;
    }

    /**
     * @return string
     */
    public function getMesto()
    {
        return $this->mesto;
    }

    /**
     * @return int
     */
    public function getPsc()
    {
        return $this->psc;
    }

    /**
     * @return string
     */
    public function getUlice()
    {
        return $this->ulice;
    }

    /**
     * @param  array to wrap
     * @param  bool
     * @return Address
     */
    public static function from($arr, $recursive = TRUE)
    {
        $obj = new static;
        foreach ($arr as $key => $value) {
            if ($recursive && is_array($value)) {
                $obj->$key = static::from($value, TRUE);
            } else {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

}
