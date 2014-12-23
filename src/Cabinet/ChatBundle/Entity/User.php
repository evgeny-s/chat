<?php

namespace Cabinet\ChatBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string $image
     * @Assert\File( maxSize = "5000k", mimeTypesMessage = "Please upload a valid Image")
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

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
     * Set image
     *
     * @param string $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getFullImagePath() {
        return null === $this->getImage() ? null : $this->getUploadRootDir(). $this->getImage();
    }

    public function getRelativePath()
    {
        return $this->getInsideWebDirPath() . $this->getImage();
    }

    protected function getUploadRootDir() {
        return $this->getTmpUploadRootDir();
    }

    protected function getTmpUploadRootDir() {
        return __DIR__ . '/../../../../web' . $this->getInsideWebDirPath();
    }

    public function getInsideWebDirPath()
    {
        return '/assets/cabinet/userpic/';
    }

    public function uploadImage() {
        // the file property can be empty if the field is not required
        if (null === $this->getImage()) {
            return;
        }
        if (!$this->id) {
            $this->image->move($this->getTmpUploadRootDir(), $this->image->getClientOriginalName());
        } else {
            $this->image->move($this->getUploadRootDir(), $this->image->getClientOriginalName());
        }
        $this->setImage($this->image->getClientOriginalName());
    }

    public function moveImage()
    {
        if (null === $this->image) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->getImage(), $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir().$this->getImage());
    }

    public function removeImage()
    {
        unlink($this->getFullImagePath());
        rmdir($this->getUploadRootDir());
    }
}
