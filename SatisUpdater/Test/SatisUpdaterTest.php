<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../SatisUpdater.php';

class SatisUpdaterTest extends PHPUnit_Framework_TestCase {
	private $satisUpdater;

	function setUP() {
		$this->satisUpdater = new SatisUpdater();
	}

	function testDefaultConfigFile() {
		$actual = $this->satisUpdater->parseComposerConf('');
		$this->assertJsonStringEqualsJsonString($this->jsonRecode(SatisUpdater::$DEFAULT_CONFIG), $actual);
	}

	function testEmptyRequiredPackagesInComposerJsonWillProduceDefaultConfiguration() {
		$actual = $this->satisUpdater->parseComposerConf('{"require": {}}');
		$this->assertJsonStringEqualsJsonString($this->jsonRecode(SatisUpdater::$DEFAULT_CONFIG), $actual);
	}

	function testARequiredPackageInComposerWillBeInSatisAlso() {
		$actual = $this->satisUpdater->parseComposerConf(
			'{"require": {
				"Mockery/Mockery": ">=0.7.2",
				"phpunit/phpunit": "3.7.28"
			}}');
		$this->assertEquals('*', json_decode($actual, true)['require']['Mockery/Mockery']);
		$this->assertEquals('*', json_decode($actual, true)['require']['phpunit/phpunit']);
	}

	function testARquiredDevPackageInComposerWillBeInSatisAlso() {
		$actual = $this->satisUpdater->parseComposerConf(
			'{"require-dev": {
				"Mockery/Mockery": ">=0.7.2",
				"phpunit/phpunit": "3.7.28"
			}}');
		$this->assertEquals('*', json_decode($actual, true)['require']['Mockery/Mockery']);
		$this->assertEquals('*', json_decode($actual, true)['require']['phpunit/phpunit']);
	}

	function testItCanParseComposerJsonWithBothSections() {
		$actual = $this->satisUpdater->parseComposerConf(
			'{"require": {
				"Mockery/Mockery": ">=0.7.2"
				},
			"require-dev": {
				"phpunit/phpunit": "3.7.28"
			}}');
		$this->assertEquals('*', json_decode($actual, true)['require']['Mockery/Mockery']);
		$this->assertEquals('*', json_decode($actual, true)['require']['phpunit/phpunit']);
	}

	private function jsonRecode($json) {
		return json_encode(json_decode($json, true));
	}

}
 