# do-while/contao-timetracker-bundle

## Installation
Ergänzen Sie folgende Zeilen in der composer.json der Contao-Installation (im Stammverzeichnis des Contao), evtl. müssen bestehende Einträge erweitert werden:
Die Einträge sind notwendig, da die Erweiterung nicht in Packagist verfügbar ist.
```
    "repositories": [
        {
            "type": "vcs",
            "name": "do-while/contao-timetracker-bundle",
            "url": "https://github.com/do-while/contao-timetracker-bundle.git"
        }
    ],
    "config": {
        "github-oauth": {
            "github.com": "#Ihren GitHub Token#"
        }
    },
    "require": {
            :
            :
        "do-while/contao-timetracker-bundle": "^3.0"
    },
```

Installieren Sie die Erweiterung einfach mit dem Contao Manager oder über die Kommandozeile mit dem Composer:
```
composer require do-while/contao-timetracker-bundle
```


Dann rufen Sie den Composer auf `composer update` oder Sie rufen den **Contao Manager** auf.


## Version
* 1.0.0<br>Erstversion: 2020-10-14<br>Version für Contao ab Version 4.9 LTS
* 2.0.0<br>Freigabedatum: 2024-08-07<br>Version für Contao ab Version 5.3 LTS
* 3.0.0<br>Freigabedatum: 2026-05-06<br>Version für Contao ab Version 5.7 LTS


**Problem melden | *Report Problem*:**<br>
https://github.com/do-while/contao-timetracker-bundle/issues

___
Softleister - 2026-05-06
