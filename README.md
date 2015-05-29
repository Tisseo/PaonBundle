# PAON Bundle

## Description
PAON application developped by Tisséo.

## Requirements
1. Language
-
     php (version 5.4.x)

2. Php5's Extensions
-
     php5-intl
     php5-curl (curl package should be installed first)
     php5-pgsql

3. Database
-
     We use postgresql ( >=9.1.0)

4. Bundles
-
    https://github.com/craue/CraueFormFlowBundle

    In the Symfony's AppKernel file please add this line :

    $bundles = array(
        ...
        new Craue\FormFlowBundle\CraueFormFlowBundle(),
    );
