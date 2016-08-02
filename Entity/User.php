<?php
namespace Aescarcha\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Aescarcha\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebookAccessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=255, nullable=true)
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    protected $language;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    protected $bio = null;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_picture", type="string", length=250, nullable=true)
     */
    protected $profilePicture;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="bigint", nullable=true, options={"default" = 0})
     */
    protected $score = 0;

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set id
     *
     * @return integer 
     */
    public function setId( $id )
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Return base class name
     * @return string
     */
    public function getClassName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday( $birthday)
    {
        if(gettype($birthday) === 'string'){
            $birthday = new \Datetime( $birthday );
        }
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        $lang = explode('_', $locale);
        return $this->setLanguage( array_shift($lang) );
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return User
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return User
     */
    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string 
     */
    public function getProfilePicture( $size = null, $height = null )
    {
        return $this->profilePicture ? $this->profilePicture : $this->getFacebookPicture( $size, $height );
    }

    protected function getFacebookPicture( $size = null, $height = null )
    {
        if($this->getFacebookId()){
            $img = "//graph.facebook.com/{$this->getFacebookId()}/picture";
            if($size && $height){
                return $img . "?width=$size&height=$height";
            } else{
                return $size ? $img . '?type=' . $size : $img;
            }
        }
    }

    /**
     * Set location from facebook
     *
     * @param array $location
     * @return User
     */
    public function setLocation( $location )
    {
        $location = explode(',', $location);
        $this->setCity( $location[0] );
        return $this->setCountry( $location[1] );
    }

    /**
     * Canonical to get username
     * @return string
     */
    public function getName()
    {
        return $this->getUsername();
    }

    /**
     * get Score
     * @return integer $score
     */
    public function getScore()
    {
        return $this->score ? $this->score : 0;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /* Helper method to centralize all the getting parameters for routes */
    public function getRouteParameters()
    {
        return [
            'id' => $this->getId(),
            'slug' => self::urlEncodeWithMinus($this->getName()),
        ];
    }

    protected static function urlEncodeWithMinus( $text ) {
        $text = self::textForUrl($text);
        return urlencode(preg_replace('/[^a-z0-9\-\_]/i', '', str_replace(' ', '-', $text)));
    }

    protected static function textForUrl( $text )
    {
        return str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ñ'), array( 'a', 'e', 'i', 'o', 'u', 'n' ), strtolower($text));
    }

}
