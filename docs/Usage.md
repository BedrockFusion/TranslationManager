# TranslationManager

## Usage
### Initializing TL

```php
$tl = new TL(); // automatically loads 'common'
$tl->init(defaultLanguage: "blabla_BLA"); // en_US is default
```

### Translating a Key

```php
echo $tl->translate("common", "en_US", "messages.welcome", ["player" => "Alex"]); // sorry Steve :D
```

### Adding Another Project
'Skyblock' for example:
```php
$tl->addProject("skyblock", "...PATH...");
echo $tl->translate("skyblock", "en_US", "menu.create-island");
```

### Getting the Translation Tree:
print_r($tl->getTree());