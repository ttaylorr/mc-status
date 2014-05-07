mc [![Build Status](https://travis-ci.org/ttaylorr/mc-status.svg)](https://travis-ci.org/ttaylorr/mc-status)
==

Status tracking for popular minecraft servers.

## developing

To start, run `npm install` inside the `mc` directory to install Grunt, and all related tasks.

First, run `grunt composer:update` to install all PHP-related dependencies.  (The PHP dependency manager is literally a dependency.  :unamused:)

Once completed, run `grunt make` to minify all `*.js` files into `app.min.js`.

If you're writing code, be sure to run `grunt watch` to watch all `*.js` files for changes.  It will minify those files on save so you don't have to.

## server owners

If you would like your server to be displayed [here](http://mc.ttaylorr.com), you can let me know by tweeting me [@ttaylorr_b](https://twitter.com/ttaylorr_b), or by [emailing me](mailto:me@ttaylorr.com).
