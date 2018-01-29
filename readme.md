Commerce Module Skeleton
---

This repository contains a skeleton for a Commerce module project. It is meant to be installed with composer.

```` bash 
mkdir my-project
composer create-project modmore/moduleskeleton my-project
````

The skeleton will automatically rename the various bits and pieces with the name of the directory you install it in. This will apply a few transforms. 

- For the name of directories, lexicons, etc, the directory name will be lowercased and in some places prefixed with `commerce_`.
- For the name of the namespace, module, and other "friendly" places the project name is used, it breaks up the project name by `-`, uppercase the first letter of each part, and stick those together. (E.g. a directory named `foo-bar` will be turned into `FooBar`)

## Included in the skeleton

- A build to create a transport package (`_build`) with settings, the core folder, a requirements validator, and the package information from `docs` in the component directory. This build also includes proprietary code that enables it to be built by the modmore.com package provider.
- A bootstrap (`_bootstrap/index.php`) that can be used to swiftly set up namespaces, settings and other critical parts to get going.
- Inside the core/components directory:
  - Package information files (in `docs`) with the license (MIT), changelog, and readme.
  - English lexicon stub with your project name.
  - Under `model/schema`, a sample xPDO package schema. If you use this, you'll find a script in `/_build/build.schema.php` to turn it into a model and (commented out) code in `src/Modules/Projectname.php` to load the package.
  - In `src/Modules/Projectname.php` a module, complete with basic methods filled out.
  - A `composer.json` file defining a PSR-4 autoloader for your namespace and project name. If you change this, run `composer dump-autoload` in the `core/components/projectname/` directory to update the autoloader.

Happy building!