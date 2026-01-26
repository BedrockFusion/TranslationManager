# TranslationManager

## Usage
### Initializing TL

```php
$tl = new TL();
$tl->init(defaultLanguage: "blabla_BLA", fallbackLanguage: "blabla");
```

### Translating a Key

```php
echo $tl->translate("my_project", "en_US", "messages.welcome", ["player" => "Alex"]); // sorry Steve :D
```

### Adding Another Project
'Skyblock' for example:
```php
$tl->addProject("skyblock", "...PATH...");
echo $tl->translate("skyblock", "en_US", "menu.create-island");
```

### Getting the Translation Tree:
print_r($tl->getTree());
