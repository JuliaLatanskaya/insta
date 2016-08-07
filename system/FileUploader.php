<?php
namespace Insta\system;

use Insta\system\BaseException;

class FileUploader
{
    /**
     * @var string
     */
    private $storageDir = 'uploads';
    
    /**
     * @var string
     */
    public $error = null;
    
    /**
     * @var string storedFileName
     */
    public $uploadedFile = null;
    
    public function __construct()
    {
        if (!is_dir(FrontController::$config['basePath'] .'/'. $this->storageDir)) {
            throw new BaseException("filestorage directory {$this->storageDir} doesn't exist");            
        } elseif (!is_writable(FrontController::$config['basePath'] .'/'. $this->storageDir)) {
            throw new BaseException("filestorage directory {$this->storageDir} is not writable");  
        } else {
            $this->storage = FrontController::$config['basePath'] .'/'. $this->storageDir;
        }
    }
    
    /**
     * This method checks uploaded file for size (2MB and 1920 x 1080 is allowed),
     * type JPG, PNG, GIF only. This method needs refactoring.
     * @return string | null fileName of saved file
     */
    
    public function uploadedFile()
    {
        if (!empty($_FILES['photo']['name'])) {
            if (!$_FILES['photo']['error']) {
                if ($_FILES['photo']['size'] > (2097152)) {//can't be larger than 2 MB
                    $this->error = 'Oops! Your file\'s size is too large.';
                }
                
                if (isset($_FILES['photo']['tmp_name']) && $info = getimagesize($_FILES['photo']['tmp_name'])) {
                    $imageWidth = $info[0];
                    $imageHeight = $info[1];
                    $imageType = $info[2];
                    
                    if (!in_array($imageType, array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF))) {
                        $this->error = 'Oops! This image type is not supported';
                    } elseif ($imageWidth > 1920 || $imageHeight > 1080) {
                        $this->error = 'Oops! This image\'s size is not supported';
                    }
                } else {
                    $this->error = 'This image was not loaded';
                }
                
                if(!$this->error) {
                    if ($uniqueTempFile = tempnam($this->storage, 'insta')) {
                        $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uniqueTempFile. '.' . $fileExtension)) {
                            $this->uploadedFile = pathinfo($uniqueTempFile, PATHINFO_FILENAME) . '.' . $fileExtension;
                        } else {
                            $this->error = 'Can\'t save file';
                            FrontController::$logger->error('file '.$_FILES['photo']['tmp_name']. " couldn\'t be
                                                           moved to $uniqueTempFile.$fileExtension");
                        }
                        
                        unlink($uniqueTempFile);
                    } else {
                        $this->error = 'Can\'t create unique file';
                        FrontController::$logger->error("cann\'t create unique file inside {$this->storag}");
                    }
                }
            } else {
                if (in_array($_FILES['photo']['error'], array(UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE))) {
                    $this->error = 'Oops! This image size in not suppoerted. Maximum allowed size: 2Mb.';
                } else {
                    $this->error = 'This image couldn\'t be loaded';
                    FrontController::$logger->error("this upload triggered the following error: "
                                                    .$_FILES['photo']['error']);
                }
            }
        } else {
            $this->error = 'No file was loaded';
        }
        
        return $this->uploadedFile;
    }
    
    public function getError()
    {
        return $this->error;
    }
}
