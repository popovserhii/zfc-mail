<?php
namespace Popov\ZfcMail\Form;

use Zend\Form\Form,
	Zend\InputFilter\Factory as InputFactory,
	Zend\InputFilter\InputFilter;

class Mail extends Form {

	public function __construct($id, $dbAdapter)
	{
		parent::__construct('mail');

		$this->setAttribute('method', 'post');


		$this->add(['name' => 'type']);
		$this->add(['name' => 'emailTo']);
		$this->add(['name' => 'theme']);
		$this->add([
			'name' => 'body',
			'attributes' => [
				'required' => 'required'
			],
		]);
		$this->add([
			'name' => 'statusId',
			'attributes' => [
				'required' => 'required'
			],
		]);
		$this->add([
			'name' => 'accessDocument',
			'type' => 'checkbox',
		]);


		// filters
		$inputFilter = new InputFilter();
		$factory = new InputFactory();


		$inputFilter->add($factory->createInput(array(
			'name'	=> 'type',
			'required' => true,
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'emailTo',
			'required' => false,
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 255
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'theme',
			'required' => false,
			'filters' => array(
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'max' => 255
					)
				),
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'body',
			'required' => true,
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'statusId',
			'required' => true,
			'validators' => array(
				['name' => 'Digits'],
				array(
					'name' => '\Popov\Agere\Validator\Db\NoRecordExists',
					'options' => array(
						'table' => 'mail',
						'field' => 'statusId',
						'fields' => ['type' => '?'],
						'adapter' => $dbAdapter,
						'exclude' => array(
							'field' => 'id',
							'value' => (int) $id,
						),
					)
				)
			)
		)));

		$inputFilter->add($factory->createInput(array(
			'name'	=> 'accessDocument',
			'required' => false,
		)));


		$this->setInputFilter($inputFilter);
	}

}