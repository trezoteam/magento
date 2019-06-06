## For correct install process, follow the install section DON'T download this repository

## Para executar corretamente o processo de instalação, siga para a seção Install. NÃO baixe esse repositório


[![CircleCI](https://circleci.com/gh/mundipagg/magento.svg?style=shield)](https://circleci.com/gh/mundipagg/magento)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/eedd85c187d14bc6b79958a3e39c5e8a)](https://www.codacy.com/app/mundipagg/magento?utm_source=github.com&utm_medium=referral&utm_content=mundipagg/magento&utm_campaign=badger)
[![Maintainability](https://api.codeclimate.com/v1/badges/f79be193872380945e80/maintainability)](https://codeclimate.com/github/mundipagg/magento/maintainability)

# Mundipagg/Magento Integration module (development)

This is the official Magento module for new Mundipagg API integration.



# Documentation
Refer to [module documentation](https://github.com/mundipagg/magento/wiki)

# Compatibility
This module supports Magento version 1.9.3+

# Dependencies
* ```PHP``` Version 5.6+

# Install
There are two different ways to install our module

## Using Magento Connect Manager (recommended)

Download the Mundipagg_Payment_Module-X.X.X.tgz file of our [last module relase](https://github.com/mundipagg/magento/releases/latest)

Access your Mgento admin panel and go to System > Magento Connect > Magento Connect Manager 

Upload the downloaded file


## Using modman
Modman is a project which helps developers to centralize extension code when
the environment forces you to mix your code with the core files. For more
information, refer to [modman](https://github.com/colinmollenhour/modman).

```bash
modman init
modman clone https://github.com/mundipagg/magento
```

## Updating module version at develop environment 
Use `composer robo version` with the following options

`get` get current module version    	
`update version-tag` set version manually	    
`bump major/minor/patch` increase module version based on the previous one	

Example: If the current module version is 1.0.0 

`composer robo version:bump major` from 1.0.0 to 2.0.0

`composer robo version:bump minor` from 1.0.0 to 1.1.0

`composer robo version:bump patch` from 1.0.0 to 1.0.1   

Before module version updating, pay attention to  [Semantic Versioning](https://semver.org) 



## Magento Marketplace

Coming soon

## API Reference

http://docs.mundipagg.com

# How can I contribute?
Please, refer to [CONTRIBUTING](CONTRIBUTING.md)

# Found something strange or need a new feature?
Open a new Issue following our issue template [ISSUE-TEMPLATE](ISSUE-TEMPLATE.md)

# Changelog
See in [releases](https://github.com/mundipagg/magento/releases)

