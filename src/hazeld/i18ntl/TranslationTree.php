<?php

namespace hazeld\i18ntl;

use hazeld\i18ntl\utils\Logger;
use pocketmine\utils\TextFormat as TF;

final class TranslationTree
{
    /** @var array<string, array<string, array<string, string>>> project -> lang -> key -> value */
    private array $tree = [];

    /**
     * Adds a new translation project by scanning a specified directory for JSON files.
     * Each JSON file is parsed, validated and its translations are added to the project.
     *
     * @param string $name The name of the project to add translations to.
     * @param string $path The directory path where JSON translation files are located.
     *
     * @return void
     */
    public function addProject(string $name, string $path): void
    {
        if (!is_dir($path)) {
            Logger::error(TF::YELLOW . "[TranslationTree] Path does not exist: $path");
            return;
        }

        $files = scandir($path);
        if ($files === false) {
            Logger::error(TF::YELLOW . "[TranslationTree] Failed to scan directory: $path");
            return;
        }

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== "json") {
                continue;
            }

            $filePath = $path . "/" . $file;
            try {
                $contents = file_get_contents($filePath);
                if ($contents === false) {
                    Logger::error(TF::YELLOW . "[TranslationTree] Failed to read file: $filePath");
                    continue;
                }

                $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($data)) {
                    Logger::error(TF::YELLOW . "[TranslationTree] JSON does not decode to array: $filePath");
                    continue;
                }

                $lang = pathinfo($file, PATHINFO_FILENAME);
                $this->addTranslations($name, $lang, $data);

            } catch (\JsonException $e) {
                Logger::error(TF::RED . "[TranslationTree] JSON parse error in '$filePath': " . $e->getMessage());
            }
        }
    }

    /**
     * Adds translations to the translation tree for a specified project and language.
     *
     * @param string $project The name of the project to add translations to.
     * @param string $lang The language code for the translations.
     * @param array $translations An associative array of translation keys and values.
     * @param string $prefix Optional prefix to prepend to each translation key.
     *
     * @return void
     */
    public function addTranslations(string $project, string $lang, array $translations, string $prefix = ""): void
    {
        foreach ($translations as $key => $value) {
            $fullKey = $prefix . $key;
            if (is_array($value)) {
                $this->addTranslations($project, $lang, $value, $fullKey . ".");
            } elseif (is_string($value)) {
                $this->tree[$project][$lang][$fullKey] = $value;
            } else {
                Logger::error(TF::YELLOW . "[TranslationTree] Ignored non-string value for key '$fullKey' in project '$project', lang '$lang'");
            }
        }
    }

    /**
     * Retrieves a value from the tree structure based on the provided project, language and key.
     *
     * @param string $project The project identifier to locate the data.
     * @param string $lang The language code to specify the target language.
     * @param string $key The specific key to retrieve the value.
     *
     * @return string|null The value associated with the given project, language, and key, or null if not found.
     */
    public function get(string $project, string $lang, string $key): ?string
    {
        return $this->tree[$project][$lang][$key] ?? null;
    }

    public function getTree(): array
    {
        return $this->tree;
    }
}
