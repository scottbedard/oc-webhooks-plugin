# oc-webhooks-plugin

[![Build Status](https://travis-ci.org/scottbedard/oc-webhooks-plugin.svg?branch=master)](https://travis-ci.org/scottbedard/oc-webhooks-plugin)
[![Coverage Status](https://coveralls.io/repos/github/scottbedard/oc-webhooks-plugin/badge.svg?branch=master)](https://coveralls.io/github/scottbedard/oc-webhooks-plugin?branch=master)

This plugin executes shell scripts when certain URLs are hit. Webhooks can be created at `/backend/bedard/webhooks/hooks`.

### GitHub "push to deploy" example

One common use for this plugin is to set your plugins up to pull and update whenever you push to GitHub. To do this, create a simple webhook with the following script. You'll need to also provide a name and directory for your plugin.

```bash
git pull
composer update
```

To gain your webhook URL, click the `URL` column in the webhooks list. Finally, just instruct GitHub to [hit your webhook](https://developer.github.com/webhooks/creating) URL whenever a git push occurs.
