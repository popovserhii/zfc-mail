<?php
/**
 * Mail template render
 *
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 24.04.2016 9:24
 */
namespace Popov\ZfcMail\Service;

use Zend\Stdlib\Exception;

class MailRenderer {

	protected $content;

	protected $variables = [];


	public function setContent($content) {
		#$message_to_client = file_get_contents("client_email.html");
		//$message_to_client = "bla bla {{ EMAIL }} bla bla";
		$this->content = $content;

		return $this;
	}

	public function getContent() {
		return $this->content;
	}

	public function setVariable($name, $value) {
		$this->variables[$name] = $value;

		return $this;
	}

	public function setVariables(array $variables) {
		$this->variables = $variables;

		return $this;
	}

	public function addVariables(array $variables) {
        foreach ($variables as $name => $value) {
            $this->variables[$name] = $value;
        }

		return $this;
	}

	/**
	 * Render content
	 *
	 * @see http://stackoverflow.com/a/25171199
	 * @return string
	 */
	public function render() {
		//$renderedContent = preg_replace_callback('/{{([a-zA-Z0-9\_\-]*?)}}/i', function($match) use ($variables) {
		$renderedContent = preg_replace_callback('/{{([a-zA-Z0-9\_\-.]*?)}}/i', function($match) {
			//\Zend\Debug\Debug::dump($this->getPreparedValue($match[1])); die(__METHOD__);
			return $this->getPreparedValue($match[1]);
		}, $this->getContent());

		return $renderedContent;
	}


	protected function getPreparedValue($quantifier) {
		$parts = explode('.', $quantifier);

		//$object = null;
		$value = null;
		foreach ($parts as $part) {
			// if scalar string value as 'statusName'
			if (in_array($part[0], ['"', "'"])) {
				//\Zend\Debug\Debug::dump([$part, __METHOD__ . __LINE__]);
				$value = trim($part, $part[0]);
				break;
			} elseif (in_array($part, ['true', 'false'])) {
				$value = ($part == 'true') ? true : false;
				break;
			}
			//\Zend\Debug\Debug::dump([$quantifier, get_class($this->variables[$part]), $part, gettype($value)]); die(__METHOD__);
			if (!$value) {
				//$property = '__' . $part;
				if (!isset($this->variables[$part])) {
					throw new Exception\RuntimeException(
						sprintf(
							'First element in quantifier %s must have relative variables key property in class %s',
							$quantifier,
							__CLASS__
						)
					);
				}
				$value = $this->variables[$part];
				continue;
			}
			$method = 'get' . ucfirst($part);
			if (!method_exists($value, $method)) {
				$method = $part;
			}
			$value = $value->{$method}();
		}

		return $value;

		//\Zend\Debug\Debug::dump($value); die(__METHOD__ . __LINE__);
	}
}