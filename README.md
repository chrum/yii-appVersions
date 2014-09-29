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

Adding some languages in your config/main.php, params section
~~~php
return array(
    .......
    'params' => array(
        "defaultLang" => "dk",
        "langs" => array(
            "dk" => "Danish",
            "se" => "Swedish",
            "no" => "Norwegian",
            "fi" => "Finnish"
        )
    ),
)
~~~

Update/add to config/console.php info about command
~~~php
return array(
    .......
	'commandMap' => array(
		'migrate' => array(
			'class' => 'system.cli.commands.MigrateCommand',
			'migrationPath' => 'application.migrations'
		),
        'relAppVer' => array(
            'class' => 'common.lib.yii-appVersions.commands.relAppVerCommand'
        )
	)
)
~~~


* Copy migrations files from the module to your project and.
* Apply migrations.
