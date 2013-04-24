Installation
================

1. place fuel-wrench into `fuel/packages/wrench`
2. add wrench to your config.php under `always_load.packages`
3. install sass gem `gem install sass` (requrires ruby)
4. install coffescript package `sudo npm install -g coffee-script` (requires node)

Using Wrench
===============

Right now wrench is really just a convinience method to compile sass and coffescript on the fly.

There are two commands:

`php oil r wrench:watch` and `php oil r wrench`

Adding `:watch` will continuously compile any assets while it's running, while the plain wrench command will issue a one time compile.


Directories
=============
Wrench compiles sass assets into `public/assets/sass` into `public/assets/css`.
Wrench compiles coffee assets into `public/assets/coffee` into `public/assets/js`.