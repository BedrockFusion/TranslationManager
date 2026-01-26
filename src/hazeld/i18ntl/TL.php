<?php

namespace hazeld\i18ntl;

use hazeld\i18ntl\utils\Logger;
use pocketmine\utils\TextFormat as TF;

/**
 * This class provides functionality for managing and retrieving translations.
 * It supports language fallbacks and dynamic placeholders when translating keys.
 */
final class TL
{
    /** @var TranslationTree */
    private TranslationTree $tree;
    /** @var string */
    private string $defaultLanguage = "en_US";
    /** @var string|null */
    private ?string $fallbackLanguage = null;

    /**
     * Create a new translation manager (TL).
     * Also, it calls the 'init' function.
     */
    public function __construct()
    {
        $this->tree = new TranslationTree();
        $this->init();
    }

    /**
     * Initialize defaults for language fallbacks.
     *
     * @param string|null $defaultLanguage Final fallback language (default: en_US).
     * @param string|null $fallbackLanguage Optional fallback language used before default.
     *
     * @return void
     */
    public function init(?string $defaultLanguage = null, ?string $fallbackLanguage = null): void
    {
        if ($defaultLanguage) {
            $this->defaultLanguage = $defaultLanguage;
        }
        if ($fallbackLanguage) {
            $this->fallbackLanguage = $fallbackLanguage;
        }
    }

    /**
     * Set or clear the fallback language used before the default language.
     *
     * @param string|null $fallbackLanguage
     *
     * @return void
     */
    public function setFallbackLanguage(?string $fallbackLanguage): void
    {
        $this->fallbackLanguage = $fallbackLanguage;
    }

    /**
     * Add a translation project from a directory path.
     *
     * @param string $name Project identifier.
     * @param string $path Directory containing language JSON files.
     *
     * @return void
     */
    public function addProject(string $name, string $path): void
    {
        try {
            $this->tree->addProject($name, $path);
        } catch (\Throwable $e) {
            Logger::error(TF::RED . "[TL] Failed to load project '$name': " . $e->getMessage());
        }
    }

    /**
     * Translate a key using language fallbacks: requested -> fallback -> default -> project.key.
     *
     * @param string $project Project identifier.
     * @param string $lang Requested language.
     * @param string $key Translation key (dot-separated).
     * @param array<string, string|int|float> $args Placeholder values.
     *
     * @return void
     */
    public function translate(string $project, string $lang, string $key, array $args = []): string
    {
        $translation = $this->tree->get($project, $lang, $key);
        if (!$translation) {
            if ($this->fallbackLanguage) {
                $translation = $this->tree->get($project, $this->fallbackLanguage, $key);
            }
            $translation = $translation ?? $this->tree->get($project, $this->defaultLanguage, $key)
                ?? "$project.$key";
        }

        foreach ($args as $param => $value) {
            $translation = str_replace("{{{$param}}}", $value, $translation);
        }

        return $translation;
    }

    /**
     * Get the translation tree as nested arrays.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    public function getTree(): array
    {
        return $this->tree->getTree();
    }
}
