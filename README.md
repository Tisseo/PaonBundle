README
======

Description
-----------

PaonBundle for French acronym "Plateforme d'Administration de l'Offre Nominale"
is used in public transport ENDIV project (https://github.com/Tisseo/TID).
This bundle is working with Symfony NMM application and use mapping information
provided by EndivBundle (https://github.com/Tisseo/EndivBundle) in order to
administrate ENDIV database and create new public transport commercial offers.

PaonBundle is aimed to work in NMM application environment.
(https://github.com/CanalTP/NmmPortalBundle)

Requirements
------------

- PHP 5.4.3
- https://github.com/Tisseo/TID
- https://github.com/Tisseo/EndivBundle

Installation
------------

1. composer.json:

'''
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/Tisseo/PaonBundle.git"
        },
        //...
    ],
    "require": {
        "tisseo/paon-bundle": "dev-master",
        // ...
    }
'''

2. AppKernel.php

'''
    $bundles = array(
        new Tisseo\PaonBundle\TisseoPaonBundle(),
        // ...
    );
'''

Configuration
-------------

You don't need to do this if you're working with the main bundle NMM which
provides all this configuration already.

PaonBundle manage some import/export jobs with ENDIV database and other application.
We decided to communicate with Jenkins' API in order to launch these jobs.

- config.paon.yml

'''
tisseo_paon:
    data_exchange:
        jenkins_server: ''
        jenkins_user: ''
        jobs:
            master_prefix: ''
            atomic_prefix: ''
    mailer:
        default_email_dest: ''
        default_email_exp: ''
'''

Contributing
------------

1. Vincent Passama - vincent.passama@gmail.com
2. Rodolphe Duval - rdldvl@gmail.com
3. Pierre-Yves Claitte - pierre.cl@gmail.com

TODO
----

- Clarify DataExchange part (with Jenkins jobs) in the documentation
- Add some information or a link to the whole ENDIV/NMM project documentation
