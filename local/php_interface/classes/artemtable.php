<?php
namespace Local\Entity;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class Table
 * 
 * Fields:
 * <ul>
 * <li> name string(222) optional
 * </ul>
 *
 * @package Bitrix\
 **/

class ArtemTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'artemtable';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new StringField(
				'name',
				[
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('_ENTITY_NAME_FIELD')
				]
			),
			new IntegerField(
				'id',
				[
					'primary' => true,
					'title' => Loc::getMessage('_ENTITY_ID_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for name field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return [
			new LengthValidator(null, 222),
		];
	}
}