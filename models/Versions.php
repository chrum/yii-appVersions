<?php

/**
 * This is the model class for table "{{versions}}".
 *
 * The followings are the available columns in table '{{versions}}':
 * @property integer $id
 * @property integer $version
 * @property string $message
 * @property string $platforms
 */
class Versions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{versions}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('version', 'required'),
			array('version', 'numerical', 'integerOnly'=>true),
			array('platforms', 'length', 'max'=>64),
			array('message', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, version, message, platforms', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'version' => 'Version',
			'message' => 'Message',
			'platforms' => 'Platforms',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('version',$this->version);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('platforms',$this->platforms,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Versions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getVersionsList() {
        $q = new CDbCriteria();
        $q->order = "version ASC";
        $versions = Versions::model()->findAll($q);
        $list = array();
        foreach($versions as $v) {
            $list[$v->id] = array(
                "version" => Versions::decode($v->version),
                "platforms" => $v->platforms
            );
        }

        return $list;
    }

    public static function getCurrentVersion($platform = null) {
        $q = new CDbCriteria();
        $q->order = "version DESC";
        $q->limit = 1;
        if ($platform == "ios" || $platform == "android") {
            $q->addCondition("platforms LIKE '%$platform%'");

        } else {
            $q->addCondition("platforms LIKE '%,%'");
        }
        $current = Versions::model()->find($q);
        return $current;
    }

    public static function encode($string) {
        $v = explode(".",$string);
        if(!is_array($v) || count($v) != 3) {
            return null;
        }
        $version = intval($v[0]) * 1000000;
        $version += isset($v[1]) ? intval($v[1]) * 1000 : 0;
        $version += isset($v[2]) ? intval($v[2]) : 0;

        return $version;
    }

    public static function decode($version) {
        $v = array();
        $v[0] = intval($version / 1000000);
        $v[1] = intval(($version - $v[0]*1000000) / 1000);
        $v[2] = $version - $v[0]*1000000 - $v[1]*1000;
        return implode(".", $v);
    }
}
