<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property string $title
 * @property string $text
 */
class Post extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{post}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.

        return array(
            array('title, text', 'required'),
            array('image', 'EImageValidator', 'allowEmpty' => 'true',
                'types' => "gif, jpg, png",  'height' => 600,'width'=>800,
                'dimensionError' => 'Image dimension error'),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, text', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'order' => 'comments.create_time DESC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'create_time' => 'Create Time',
            'image' => 'Image',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('text', $this->text, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->create_time = time();
            }


            return true;
        } else
            return false;
    }

    protected function beforeDelete() {
        if (parent::beforeDelete()) {
            if ($this->image)
                @unlink(Yii::app()->basePath . '/../images/post/' . DIRECTORY_SEPARATOR . $this->image);
            return true;
        } else
            return false;
    }

    public function getComment() {
        $comment = new Comment;
        $id = $this->id;
        return $comment->getCommentById($id);
    }

    public function addComment($comment) {

        $comment->post_id = $this->id;
        return $comment->save();
    }

    public function getAllPost() {
        return new CActiveDataProvider($this, array(
            'criteria' => array(
                "order" => "id DESC"),
        ));
    }

    public function getCountCommentbyPost($param) {
        $comment = new Comment;
        return $comment->getCountComment($param);
    }

    public function getLastComment() {
        $comment = new Comment;
        return $comment->getLastFiveComment();
    }

    public function getTitleById($id) {
        return $user = Yii::app()->db->createCommand()
                ->select('title')
                ->from('tbl_post u')
                ->where('id=:id', array(':id' => $id))
                ->queryScalar();
    }

}
