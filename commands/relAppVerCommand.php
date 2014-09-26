<?php
/**
 * Created by PhpStorm.
 * User: chrystian
 * Date: 8/6/14
 * Time: 11:02 AM
 */
require dirname(__FILE__) . '/../components/Colors.php';
require dirname(__FILE__) . '/../models/Versions.php';

class relAppVerCommand extends CConsoleCommand
{
    private $platforms = array("ios", "android");

    public function actionIndex() {
        echo $this->getHelp();
    }

    public function actionAdd($args) {
        $colors = new Colors();
        if(count($args) == 0) {
            $colors->info("We need version number (ex. 1.0.1)");
            echo "\n";
            return;
        }

        $version = Versions::encode($args[0]);

        if($version == null) {
            $colors->error("Bad version format!");
            $colors->info("Version should be formatted like X.X.X");
            $colors->info("EXAMPLE: 1.0.1");
            echo "\n";
            return;
        }

        $existing = Versions::model()->findByAttributes(array("version" => $version));
        if ($existing != null) {
            $colors->error("Version ".$args[0]. " exists.");
            $colors->info("ID: ".$existing->id);
            $colors->info("Platforms: ".$existing->platforms);
            echo "\n";
            return;
        }
        $newVersion = new Versions();
        $newVersion->version = $version;
        if($newVersion->save()) {
            $colors->success("Version ".$args[0]." successfuly added.");

        } else {
            $colors->error("Couldn't save the provided version");
            var_dump($newVersion->getErrors());
        }
        echo "\n";
    }

    public function actionRelease($args) {
        $colors = new Colors();
        if (count($args) < 2) {
            $colors->error("Not enough arguments!");
            echo "\n";
            return;
        }

        $v = Versions::encode($args[0]);
        if ($v != null) {
            $version = Versions::model()->findByAttributes(array("version" => $v));

        } else if (is_numeric($args[0]) && intval($args[0]) > 0) {
            $version = Versions::model()->findByPk($args[0]);

        } else {
            $colors->error("Bad version!");
            $colors->info("You can either use ID or version number in X.X.X format");
            echo "\n";
            return;
        }

        if (count($args) == 2 && !in_array($args[1], $this->platforms)) {
            $platforms = explode(",", $args[1]);
            foreach($platforms as $platform) {
                if (!in_array($platform, $this->platforms)) {
                    $colors->error("Bad platform!");
                    echo "\n";
                    return;
                }
            }
        } else if (count($args) >= 3) {
            if (!in_array(str_replace(",", "", $args[1]), $this->platforms) || !in_array($args[2], $this->platforms)) {
                $colors->error("Bad platform!");
                echo "\n";
                return;
            }
            $platforms = array(str_replace(",", "", $args[1]), $args[2]);

        } else {
            $platforms = array($args[1]);
        }

        if ($version == null) {
            $colors->warning("Can't find any matching release");
            echo "\n";
            return;
        }

        if ($version->platforms != null) {
            $colors->error("Version ".Versions::decode($version->version)." is already released for platform(s): ".$version->platforms);
            $colors->warning("If you want to change that version, please put it on hold first and then release again");
            echo "\n";
            return;
        }

        $version->platforms = implode(",", $platforms);
        if($version->save()) {
            $colors->success("Version ".$args[0]." successfuly released for platform(s): ". implode(", ", $platforms));

        } else {
            $colors->error("Couldn't save the provided version");
            var_dump($version->getErrors());
        }
        echo "\n";
    }

    public function actionHold($args) {
        $colors = new Colors();
        if (count($args) == 0) {
            $colors->error("Not enough arguments!");
            echo "\n";
            return;
        }

        $v = Versions::encode($args[0]);
        if ($v != null) {
            $version = Versions::model()->findByAttributes(array("version" => $v));

        } else if (is_numeric($args[0]) && intval($args[0]) > 0) {
            $version = Versions::model()->findByPk($args[0]);

        } else {
            $colors->error("Bad version!");
            $colors->info("You can either use ID or version number in X.X.X format");
            echo "\n";
            return;
        }

        if ($version == null) {
            $colors->warning("Can't find any matching release");
            echo "\n";
            return;
        }

        $version->platforms = null;
        if($version->save()) {
            $colors->success("Version ".$args[0]." successfuly put on hold");

        } else {
            $colors->error("Couldn't save the provided version");
            var_dump($version->getErrors());
        }
        echo "\n";

    }

    public function actionList($args) {
        $versions = Versions::getVersionsList();
        echo "ID\t\tVERSION\t\tPLATFORMS\n";
        foreach($versions as $id => $info) {
            echo $id."\t\t".$info['version']."\t\t".$info['platforms']."\n";
        }
        echo "\n";

    }

    public function getHelp()
    {
        return <<<EOD
USAGE
  yiic relappver [action] [parameter] [parameter]

DESCRIPTION
  Release App Version - tool for trivia versions manipulation

ACTIONS
  add       {version}
  list
  release   {version}   {platform}
  hold      {version}

VERSION
    Should be in format X.X.X eg. 1.0.2

PLATFORMS
  ios
  android
  ios,android - to add two at once

EXAMPLES
 * yiic relappver add 1.0.1
   Adds release version 1.0.1

 * yiic relappver list
   Lists all releases


EOD;
    }

} 