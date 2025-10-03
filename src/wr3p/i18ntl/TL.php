<?php

namespace wr3p\i18ntl;

use pocketmine\utils\TextFormat as TF;
use wr3p\i18ntl\utils\Logger;

final class TL{

	private TranslationTree $tree;
	private string $defaultProject = "common";
	private string $defaultLanguage = "en_US";

	public function __construct(){
		$this->tree = new TranslationTree();

		$commonPath = __DIR__ . "/resources/common";
		if(is_dir($commonPath)){
			try{
				$this->addProject($this->defaultProject, $commonPath);
			}catch(\Throwable $e){
				Logger::error(TF::RED . "[TL] Failed to load default project 'common': " . $e->getMessage());
			}
		}
	}

	public function init(?string $defaultLanguage = null): void{
		#if($defaultProject) $this->defaultProject = $defaultProject;
		if($defaultLanguage) $this->defaultLanguage = $defaultLanguage;
	}

	public function addProject(string $name, string $path): void{
		try{
			$this->tree->addProject($name, $path);
		}catch(\Throwable $e){
			Logger::error(TF::RED . "[TL] Failed to load project '$name': " . $e->getMessage());
		}
	}

	public function translate(string $project, string $lang, string $key, array $args = []): string{
		$translation = $this->tree->get($project, $lang, $key);
		if(!$translation){
			$translation = $this->tree->get($project, $this->defaultLanguage, $key)
				?? "$project.$key";
		}

		foreach($args as $param => $value){
			$translation = str_replace("{{{$param}}}", $value, $translation);
		}

		return $translation;
	}

	public function getTree(): array{
		return $this->tree->getTree();
	}
}
