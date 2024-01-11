# Wave Theme

## General Information

*Wave* is a responsive theme for all O3-Shop editions. Theme is built on the top of [Flow theme](https://gitlab.o3-shop.com/o3/flow-theme).

## Installation
If you want to extend the theme you need to clone the repository (see section development) as some soures are ignored on composer installation. 

### Step 1: Get the source code

This step is only required if the theme is not delivered with the O3-Shop distribution.
```
composer require o3-shop/wave-theme
```

### Step 2: Prepare the database 

In order to install the theme options, import the ``setup.sql`` (to be found in ``DOCUMENT_ROOT/source/Application/views/wave``) into your database.

#### Option 1: Console

``mysql -u MYSQL_USER -p SHOP_DATABASE < wave/setup.sql``

#### Option 2: Shop Admin: Service -> Tools

If you have a local copy of wave files, go to Service -> Tools in Shop Admin, upload wave/setup.sql and click "start update" button

### Step 3: Activate theme 

Log into the admin panel, go to *Extensions → Themes → Wave* and press the *Activate* button. Clean the cache and off you go!

## Development

All *Wave* theme related CSS/Javascript files can be found in theme's ``build`` directory. To regenerate the theme's assets, the ``grunt`` tasks should be used. Please install ``grunt`` and run ``grunt's`` default task to regenerate all minimized ``css`` and ``js`` files:

To get the development files you need to clone the repository

    cd DOCUMENT_ROOT/Application/views/
    git clone -b BRANCH_NAME https://gitlab.o3-shop.com/o3/wave-theme.git wave
    
1. To use ``grunt``, ``npm`` is required. Check ``nodejs`` website for installation
instructions (https://nodejs.org/en/download/package-manager/). Example of
Installation on ubuntu system:

    ```
    curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
    sudo apt-get install -y nodejs
    ```

2. Install ``grunt`` globally. Example:

    ```
    sudo npm install -g grunt-cli
    ```

3. Go to "wave" theme's directory and install all related ``grunt`` plugins:

    ```
    cd DOCUMENT_ROOT/Application/views/wave/
    npm install
    ```

4. Now its possible to regenerate "wave" theme assets by running ``grunt`` default
task while being in "wave" directory:

    ```
    grunt
    ```

## Contributing

If you are interested in contributing of some changes, Please read [CONTRIBUTING.md](CONTRIBUTING.md) file for more information.

## Compatibility with modules

1. PayPal module
- The PayPal module contains in a tpl file a check for the theme id ```[{if $oViewConf->getActiveTheme()=='flow'}]``` which should be adjusted to wave

## Issues

Please forward all issues to https://issues.o3-shop.com/ for the project **Wave Theme**.
