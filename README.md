
# BitBag SyliusUserComPlugin

----

We want to impact many unique eCommerce projects and build our brand recognition worldwide, so we are heavily involved in creating open-source solutions, especially for Sylius. We have already created over **35 extensions, which have been downloaded almost 2 million times.**

You can find more information about our eCommerce services and technologies on our website: https://bitbag.io/. We have also created a unique service dedicated to creating plugins: https://bitbag.io/services/sylius-plugin-development.

Do you like our work? Would you like to join us? Check out the **“Career” tab:** https://bitbag.io/pl/kariera.

# About Us

---

BitBag is a software house that implements tailor-made eCommerce platforms with the entire infrastructure—from creating eCommerce platforms to implementing PIM and CMS systems to developing custom eCommerce applications, specialist B2B solutions, and migrations from other platforms.

We actively participate in Sylius's development. We have already completed **over 150 projects**, cooperating with clients worldwide, including smaller enterprises and large international companies. We have completed projects for such important brands as **Mytheresa, Foodspring, Planeta Huerto (Carrefour Group), Albeco, Mollie, and ArtNight.**

We have a 70-person team of experts: business analysts and consultants, eCommerce developers, project managers, and QA testers.

**Our services:**
* B2B and B2C eCommerce platform implementations
* Multi-vendor marketplace platform implementations
* eCommerce migrations
* Sylius plugin development
* Sylius consulting
* Project maintenance and long-term support
* PIM and CMS implementations

**Some numbers from BitBag regarding Sylius:**
* 70 experts on board
* +150 projects delivered on top of Sylius
* 30 countries of BitBag’s customers
* 7 years in the Sylius ecosystem
* +35 plugins created for Sylius

---
[![](https://bitbag.io/wp-content/uploads/2024/09/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_productbandle)

---



## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
* [Testing](#testing)
* [Functionalities](#functionalities)
* [Demo](#demo)
* [License](#license)
* [Contact](#contact)
* [Community](#community)

# Overview

----
The **SyliusUserComPlugin** allows you to integrate your Sylius store with the User.com platform. User.com is a platform that allows you to automate your marketing, sales, and support processes. With this plugin, you can synchronize your Sylius store with User.com and use the data collected in your store to create personalized marketing campaigns, automate sales processes, and provide better customer support.


# Installation
----


# Testing
----

```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn build
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d public -e test
$ open http://localhost:8080
$ vendor/bin/behat
```

# Functionalities

All main functionalities of the plugin are described **[here.](https://github.com/BitBagCommerce/SyliusUserComPlugin/blob/master/doc/functionalities.md)**

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_productbundle) or send us an e-mail at hello@bitbag.io!

---
# Demo
---
We created a demo app with some useful use-cases of plugins! Visit http://demo.sylius.com/ to take a look at it.

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_productbundle)


# Additional resources for developers
---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)
* [Sylius Product Bundle Plugin Blog](https://bitbag.io/blog/product-bundling-sylius)

# License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

# Contact
---
This open-source plugin was developed to help the Sylius community. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know! **Contact us** or send us an **e-mail to hello@bitbag.io** with your question(s).

# Community
---- 

For online communication, we invite you to chat with us & other users on **[Sylius Slack](https://sylius-devs.slack.com/).**

[![](https://bitbag.io/wp-content/uploads/2024/09/badges-partners.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_productbundle)
