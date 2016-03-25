# oc-webhooks-plugin

[![Build Status](https://travis-ci.org/scottbedard/oc-webhooks-plugin.svg?branch=master)](https://travis-ci.org/scottbedard/oc-webhooks-plugin)
[![Coverage Status](https://coveralls.io/repos/github/scottbedard/oc-webhooks-plugin/badge.svg?branch=master)](https://coveralls.io/github/scottbedard/oc-webhooks-plugin?branch=master)

<a name="introduction"></a>
### Introduction
This plugin enables the creation of shell scripts that respond to a given route. Webhooks are particularly useful when it comes to integrating with third party services and triggering deployments.

With that said, _use your head_. Don't expose your site to massive security problems by allowing malicious code into your scripts.

<a name="github-push-to-deploy"></a>
### GitHub "push to deploy" example

In this example, we'll walk through a basic "push to deploy" example. It is written for GitHub, but the process is roughly the same everywhere. In this example, we'll assume that you've already installed a plugin via GitHub, and have access to the repository settings.

First things first, navigate to `/backend/bedard/webhooks/hooks` and create a new webhook. The script will be pretty simple for this, just `cd` into your plugin directory and pull.

```bash
cd plugins/bedard/webhooks
git pull
```

Next copy the webhook's URL by clicking on token value in the `URL` column. Now we can head over to our repository on github and instruct it to let us know about push events. To do this, click `Settings > Webhooks & Services > Add webhook`. Fill out the form, and we're done. Our plugin will now update itself whenever code is pushed.
