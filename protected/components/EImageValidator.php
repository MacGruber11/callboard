<?php

/**
 * EImageValidator validates image with fix width and height
 *
 * @author turi
 * @version 1.1
 */
class EImageValidator extends CFileValidator {

    /**
     * @var boolean the attribute requires a file to be uploaded or not.
     */
    public $allowEmpty = false;

    /**
     * @var mixed a list of file name extensions that are allowed to be uploaded.
     * This can be either an array or a string consisting of file extension names
     * separated by space or comma (e.g. "gif, jpg").
     * Extension names are case-insensitive. Defaults to null, meaning all file name
     * extensions are allowed.
     */
    public $types;

    /**
     * @var string the error message used when the uploaded file has an extension name
     * that is not listed among {@link types}.
     */
    public $typesError;

    /**
     * @var int size of the image in kilo bytes
     */
    public $size;

    /**
     * @var string the size error message
     */
    public $sizeError;

    /**
     * @var int width of the image
     */
    public $width;
    public $minWidth;
	
	/**
	 * Максимальная ширина картинки в пикселях
	 * @var unsigned int
	 */
	public $maxWidth;
	
	/**
	 * Минимальная высота картинки в пикселях
	 * @var unsigned int
	 */
	public $minHeight;
	
	/**
	 * Максимальная высота картинки в пикселях
	 * @var unsigned int
	 */
	public $maxHeight;
	
	/**
	 * Текст ошибки при слишком большой ширине картинки
	 * @var unsigned int
	 */
	public $tooLargWidth;
	
	/**
	 * Текст ошибки при слишком маленькой ширине картинки
	 * @var unsigned int
	 */
	public $tooSmallWidth;
	
	/**
	 * Текст ошибки при слишком большой высоте картинки
	 * @var unsigned int
	 */
	public $tooLargHeight;
	
	/**
	 * Текст ошибки при слишком маленькой высоте картинки
	 * @var unsigned int
	 */
	public $tooSmallHeight;
    /**
     * @var int height of the image
     */
    public $height;

    /**
     * @var string image dimension error message
     */
    public $dimensionError;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {

        $file = $object->$attribute;

        if (!$file instanceof CUploadedFile) {
            $file = CUploadedFile::getInstance($object, $attribute);

            if (null === $file)
                return $this->emptyAttribute($object, $attribute);
        }

        if ($this->types !== null) {
            if (is_string($this->types))
                $types = preg_split('/[\s,]+/', strtolower($this->types), -1, PREG_SPLIT_NO_EMPTY);
            else
                $types = $this->types;
            if (!in_array(strtolower($file->getExtensionName()), $types)) {
                $message = $this->typesError !== null ? $this->typesError : Yii::t('yii', 'The file "{file}" cannot be uploaded. Only files with these extensions are allowed: {extensions}.');
                $this->addError($object, $attribute, $message, array('{file}' => $file->getName(), '{extensions}' => implode(', ', $types)));
                return;
            }
        }

        $image_size = $this->bytesToKilobytes($file->getSize());

        if ($this->size !== null && $image_size != floor($this->size)) {
            $message = $this->sizeError ? $this->sizeError : Yii::t('yii', 'The file "{file}" is either small or large. Its size should be {limit} kilo bytes.');
            $this->addError($object, $attribute, $message, array('{file}' => $file->getName(), '{limit}' => $this->size));
            return;
        }

        $data = file_exists($file->tempName) ? getimagesize($file->tempName) : false;

        if (isset($this->width, $this->height) && $data !== false) {
            if ($data[0] != $this->width && $data[1] != $this->height) {
                $message = $this->dimensionError ? $this->dimensionError : Yii::t('yii', 'Image dimension should be {width}x{height}');
                $this->addError($object, $attribute, $message, array('{width}' => $this->width, '{height}' => $this->height));
                return;
            }
        }
                
    }
    
    /**
     * @param int $bytes the image size in bytes
     * @return int the images size in kilo bytes
     */
    protected function bytesToKilobytes($bytes) {
        return floor(number_format($bytes / 1024, 2));
    }

    /**
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * 
     * @return void
     */
    protected function emptyAttribute($object, $attribute) {
        if (!$this->allowEmpty) {
            $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} cannot be blank.');
            $this->addError($object, $attribute, $message);
            return;
        }
    }

}

?>