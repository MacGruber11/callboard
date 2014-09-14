<?php

/**
 * This is the model class for table "{{item}}".
 *
 * The followings are the available columns in table '{{item}}':
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property integer $user_id
 * @property integer $date
 * @property integer $price
 */
class Item extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{item}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, date, price', 'numerical', 'integerOnly' => true),
            array('name, image', 'length', 'max' => 255),
            array('name, price, image', 'required'),
            array('image', 'file', 'types' => 'jpg, png, jpeg', 'allowEmpty' => true,
                'maxSize' => 1024 * 1024 * 5, 'tooLarge' => 'File has to be smaller than 5MB'),
            array('item', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, image, user_id, date, price', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'image' => 'Image',
            'user_id' => 'User',
            'date' => 'Date',
            'price' => 'Price',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('date', $this->date);
        $criteria->compare('price', $this->price);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getItemById($id) {
        return new CActiveDataProvider($this, array(
            'criteria' => array(
                'condition' => "user_id =  $id", "order" => "date DESC"),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Item the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getAuthor($id) {
        $author = new User;
        return $author->getAuthorById($id);
    }

    public function getAllItems() {
        return new CActiveDataProvider($this, array(
            'criteria' => array(
                "order" => "id DESC"),
        ));
    }

    public function resizeImage($model) {
        $file = './images/orig/' . $model->id . '.jpg';
        $model->image->saveAs($file);
        $ih = new CImageHandler();
        Yii::app()->ih
                ->load($file)
                ->thumb('200', '200')
                ->save('./images/small/' .
                        $model->id . '.jpg')
                ->reload()
                ->thumb('600', '800')
                ->save('./images/main/' . $model->id . '.jpg');
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->date = time();
                $this->user_id = Yii::app()->user->id;
            }
            return true;
        } else
            return false;
    }

    protected function beforeDelete() {
        if (parent::beforeDelete()) {
            if ($this->image) {
                @unlink(Yii::app()->basePath . '/../images/orig/' . DIRECTORY_SEPARATOR . $this->id . '.jpg');
                @unlink(Yii::app()->basePath . '/../images/small/' . DIRECTORY_SEPARATOR . $this->id . '.jpg');
                @unlink(Yii::app()->basePath . '/../images/main/' . DIRECTORY_SEPARATOR . $this->id . '.jpg');
                return true;
            }
        } else
            return false;
    }

}
