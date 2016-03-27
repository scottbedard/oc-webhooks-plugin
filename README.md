# oc-webhooks-plugin
<!-- October Marketplace
[![GitHub stars](https://img.shields.io/github/stars/scottbedard/oc-webhooks-plugin.svg?style=social&label=Star)](https://github.com/scottbedard/oc-webhooks-plugin)
-->
[![Build Status](https://travis-ci.org/scottbedard/oc-webhooks-plugin.svg?branch=master)](https://travis-ci.org/scottbedard/oc-webhooks-plugin)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/scottbedard/oc-webhooks-plugin.svg)](https://scrutinizer-ci.com/g/scottbedard/oc-webhooks-plugin)
[![Coverage Status](https://coveralls.io/repos/github/scottbedard/oc-webhooks-plugin/badge.svg?branch=master)](https://coveralls.io/github/scottbedard/oc-webhooks-plugin?branch=master)
[![License](https://img.shields.io/github/license/scottbedard/oc-webhooks-plugin.svg)](https://github.com/scottbedard/oc-webhooks-plugin/blob/master/LICENSE.md)

This plugin allows you to create shell scripts that respond to unique URLs. These scripts can be particularly useful for deploying your plugins and themes.

With that said, _use your head_. Don't expose your site to massive security problems by allowing malicious code into your scripts.

<!-- October Marketplace
### This plugin is free!
Although this plugin is listed as paid in the October marketplace, that doesn't mean it is. If you want to buy me a beer, thanks, you're awesome! Just remember, this plugin is open source and completely free under the MIT license. If you don't want to pay, that's perfectly ok, here is how you install the plugin for free.
```bash
git clone https://github.com/scottbedard/oc-webhooks-plugin.git plugins/bedard/webhooks
php artisan plugin:refresh Bedard.Webhooks
```
-->

### GitHub "push to deploy" example

In this example, we'll walk through a basic "push to deploy" example. It is written for GitHub, but the process is roughly the same everywhere. In this example, we'll assume that you've already installed a plugin via GitHub, and have access to the repository settings.

First things first, navigate to `/backend/bedard/webhooks/hooks` and create a new webhook. The script will be pretty simple for this, just cd into your plugin directory and pull.

```bash
cd plugins/bedard/webhooks
git pull
```

Next copy the webhook's URL by clicking on token value in the `URL` column. Now we can head over to our repository on github and instruct it to let us know about push events. To do this, click `Settings, Webhooks & Services, Add webhook`. Fill out the form, and we're done. Our plugin will now update itself whenever code is pushed.
