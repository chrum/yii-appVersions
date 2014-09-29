App version module
==========

some description, to be written...

# Requirements

* chrum/yii-translations - (included in composer.json)

# Installation

Copy the module files to location of your choice

Enable the module in the config/main.php file adjusting 'class' to your needs:
~~~php
return array(
    ......
    'modules'=>array(
        'AppVersions' => array(
            'class' => 'common.lib.yii-appVersions.AppVersionsModule',
        ),
    ),
)
~~~

Update/add to config/console.php info about command
~~~php
return array(
    .......
	'commandMap' => array(
	........
		'relAppVer' => array(
		    'class' => 'common.lib.yii-appVersions.commands.relAppVerCommand'
		)
	)
)
~~~


* Copy migrations files from the module to your project and.
* Apply migrations.
