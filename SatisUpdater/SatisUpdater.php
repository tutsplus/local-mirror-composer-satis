<?php

class SatisUpdater {
	static $DEFAULT_CONFIG = '{
    "name": "NetTuts Composer Mirror",
    "homepage": "http://localhost:4680",

    "repositories": [
        { "type": "vcs", "url": "https://github.com/SynetoNet/monolog" },
        { "type": "composer", "url": "https://packagist.org" }
    ],

    "require": {
    },
    "require-dependencies": true
}';

	function parseComposerConf($jsonConfig) {
		$addedConfig = json_decode($jsonConfig, true);
		$config = json_decode(self::$DEFAULT_CONFIG, true);
		$config = $this->addNewRequires($addedConfig, $config);
		return json_encode($config);
	}

	private function toAllVersions($config) {
		foreach ($config['require'] as $package => $version) {
			$config['require'][$package] = '*';
		}
		return $config;
	}

	private function addNewRequires($addedConfig, $config) {
		$config = $this->addRequire($addedConfig, 'require', $config);
		$config = $this->addRequire($addedConfig, 'require-dev', $config);
		return $config;
	}

	private function addRequire($addedConfig, $string, $config) {
		if (isset($addedConfig[$string])) {
			$config['require'] += $addedConfig[$string];
			$config = $this->toAllVersions($config);
		}
		return $config;
	}
} 