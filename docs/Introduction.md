# TranslationManager

## Introduction

**TranslationManager** is a lightweight PocketMine-MP virion for managing multi-project, multilingual translations. It allows PocketMine plugins to provide **localized messages** without duplicating translation logic in each plugin.

It works by loading JSON translation files per project and flattening nested keys for easy access. It also provides **parameter replacement** for dynamic messages (e.g., player names, amounts).

---

## Features

- **Multi-project support** – separate translations for different plugins or modules.
- **Multi-language support** – automatically selects default language and allows per-language translation.
- **Parameter replacement** – supports `{{param}}` placeholders in strings.
- **Safe loading** – prevents PocketMine-MP crashes on invalid JSON, missing files, or malformed data.
- **Automatic fallback** – if a translation is missing, it falls back to default language or a key placeholder.

---

## Installation

1. Place `TranslationManager` virion in your PMMP `virions/` folder.
2. Include the namespace in your plugin:

```php
use wr3p\i18ntl\TL;
```

## Others
