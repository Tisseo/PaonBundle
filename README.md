PAON
====

Description
-----------

PaonBundle for French acronym "Plateforme d'Administration de l'Offre Nominale"
is a back office application used in
[TID project](https://github.com/Tisseo/TID) and is providing
multiple functionalities in order to manage a
[TID database](https://raw.githubusercontent.com/Tisseo/TID/master/Diagramme.jpg).
It's particular role is to provide commercial offers creation and management.

This bundle is only working with [CanalTP](https://github.com/CanalTP)
[NMM](https://github.com/CanalTP/NmmPortalBundle) portal.

The PaonBundle is linked to [PaonBridgeBundle](https://github.com/Tisseo/PaonBridgeBundle)
in order to integrate the application in NMM portal.

Requirements
------------

- PHP 5.3+
- Symfony 2.6.x
- https://github.com/CanalTP/NmmPortalBundle
- https://github.com/Tisseo/TID
- https://github.com/Tisseo/EndivBundle
- https://github.com/Tisseo/CoreBundle

Installation
------------

1. composer.json:

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/Tisseo/EndivBundle.git"
    },
    {
        "type": "git",
        "url": "https://github.com/Tisseo/CoreBundle.git"
    },
    {
        "type": "git",
        "url": "https://github.com/Tisseo/PaonBundle.git"
    },
    {
        "type": "git",
        "url": "https://github.com/Tisseo/PaonBridgeBundle.git"
    },
    //...
],
"require": {
    "tisseo/endiv-bundle": "dev-master",
    "tisseo/core-bundle": "dev-master",
    "tisseo/boa-bundle": "dev-master",
    "tisseo/boa-bridge-bundle": "dev-master"
    // ...
}
```

2. AppKernel.php

```
$bundles = array(
    new Tisseo\EndivBundle\TisseoEndivBundle(),
    new Tisseo\CoreBundle\TisseoCoreBundle(),
    new Tisseo\PaonBridgeBundle\TisseoPaonBridgeBundle(),
    new Tisseo\PaonBundle\TisseoPaonBundle(),
    // ...
);
```

Configuration
-------------

Check [EndivBundle](https://github.com/Tisseo/EndivBundle) configuration to provide a correct mapping
with TID database and allow PaonBundle to manage it.

PaonBundle can manage some import/export jobs with TID database and Navitia.
We decided to use Jenkins' API in order to launch and consult these jobs.

You have to add a specific configuration file in your main Symfony application.

- config.paon.yml

```
tisseo_paon:
    data_exchange:
        jenkins_server:     localhost
        jenkins_user:       user
        jobs:
            master_prefix:  master
            atomic_prefix:  atomic
    mailer:
        default_email_dest: user@foo.bar
        default_email_exp:  paon@foo.bar
```

Contributing
------------

- Vincent Passama - vincent.passama@gmail.com
- Rodolphe Duval - rdldvl@gmail.com
- Pierre-Yves Claitte - pierre.cl@gmail.com

Todo
----

- Clean DataExchange part and configuration + add documentation
