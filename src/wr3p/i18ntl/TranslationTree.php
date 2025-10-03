<?php

namespace wr3p\i18ntl;

use pocketmine\utils\TextFormat as TF;
use wr3p\i18ntl\utils\Logger;

final class TranslationTree{

	/** @var array<string, array<string, array<string, string>>> project -> lang -> key -> value */
	private array $tree = [];

	public function addProject(string $name, string $path): void{
		if(!is_dir($path)){
			Logger::error(TF::YELLOW . "[TranslationTree] Path does not exist: $path");
			return;
		}

		$files = scandir($path);
		if($files === false){
			Logger::error(TF::YELLOW . "[TranslationTree] Failed to scan directory: $path");
			return;
		}

		foreach($files as $file){
			if(pathinfo($file, PATHINFO_EXTENSION) !== "json") continue;

			$filePath = $path . "/" . $file;
			try{
				$contents = file_get_contents($filePath);
				if($contents === false){
					Logger::error(TF::YELLOW . "[TranslationTree] Failed to read file: $filePath");
					continue;
				}

				$data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
				if(!is_array($data)){
					Logger::error(TF::YELLOW . "[TranslationTree] JSON does not decode to array: $filePath");
					continue;
				}

				$lang = pathinfo($file, PATHINFO_FILENAME);
				$this->addTranslations($name, $lang, $data);

			}catch(\JsonException $e){
				Logger::error(TF::RED . "[TranslationTree] JSON parse error in '$filePath': " . $e->getMessage());
			}
		}
	}

	public function addTranslations(string $project, string $lang, array $translations, string $prefix = ""): void{
		foreach($translations as $key => $value){
			$fullKey = $prefix . $key;
			if(is_array($value)){
				$this->addTranslations($project, $lang, $value, $fullKey . ".");
			}elseif(is_string($value)){
				$this->tree[$project][$lang][$fullKey] = $value;
			}else{
				Logger::error(TF::YELLOW . "[TranslationTree] Ignored non-string value for key '$fullKey' in project '$project', lang '$lang'");
			}
		}
	}

	public function get(string $project, string $lang, string $key): ?string{
		return $this->tree[$project][$lang][$key] ?? null;
	}

	public function getTree(): array{
		return $this->tree;
	}
}
