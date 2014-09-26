<?php
/**
 * Created by PhpStorm.
 * User: chrystian
 * Date: 9/16/14
 * Time: 11:47 AM
 */
Yii::app()->clientScript->registerCoreScript('jquery');

$res = Yii::getPathOfAlias("appversions.assets");
$resUrl = Yii::app()->getAssetManager()->publish($res, true);
Yii::app()->clientScript->registerScriptFile($resUrl."/js/appVersions.js");
Yii::app()->clientScript->registerCssFile($resUrl."/css/appVersions.css");
?>
<script>
    var siteUrl = "<?php echo Yii::app()->getBaseUrl(true); ?>";
</script>

<?php if ($currentVersion == null && count($list) == 0) : ?>
    <h4>Nothing here, yet...</h4>

<?php else:
    $langs = Yii::app()->params['langs'];
    if (empty($currentVersion) || empty($currentVersion->message)) {
        $messages = array();
        foreach($langs as $code => $name) {
            $messages[$code] = "";
        }

    } else {
        $messages = json_decode($currentVersion->message, true);
    }
    $currentLang = Yii::app()->params['defaultLang'];
    ?>
    <h4>Editing versions messages<small>(displayed when new version of the app is available)</small></h4>
    <h5>To add new version please use RelAppVer command</h5>
    <div>
        <div class="col-md-6">
            <form id="messagesForm" role="form">
                <div class="row">
                    <h4>Version</h4>
                    <select id="version" name="version" class="form-control">
                        <?php foreach($list as $id => $data):?>
                            <option value="<?php echo $id ?>" <?php echo ($currentVersion != null && $currentVersion->id == $id) ? "selected='selected'" : ""?>>
                                <?php echo $data['version']?>
                                <?php if ($data['platforms'] != null) :?>
                                    (<?php echo $data['platforms']?>)
                                <?php endif;?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="row">
                    <h4>Message. html allowed</h4>
                    <ul id="langTabs" class="nav nav-tabs" role="tablist">
                        <?php foreach($langs as $code => $name):?>
                            <li class=""><a href="#<?php echo $code?>" data-lang="<?php echo $code?>" role="tab" data-toggle="tab"><?php echo $name?></a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="row tab-content">
                    <?php foreach($langs as $code => $name):?>
                        <div class="tab-pane" id="<?php echo $code?>">
                            <div class="form-group">
                                <textarea type="message" class="form-control" name="message[<?php echo $code ?>]" id="message-<?php echo $code ?>" rows="5" placeholder="Maintenance message"><?php echo $messages[$code] ?></textarea>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="form-group">
                    <button id="showDefault" type="button" class="btn btn-default">Show default message</button>
                    <button id="showPrevious" type="button" class="btn btn-default" style="display: none">Back</button>
                    <button id="updatePreview" type="button" class="btn btn-default pull-right">Update preview</button>
                </div>
                <button id="save" type="submit" class="btn btn-danger">Save</button>
            </form>

        </div>

        <div id="preview" class="col-md-6">
            <h4>Preview</h4>
            <div id="maintenanceView">
                <?php echo $messages[$currentLang] ?>
            </div>
        </div>
    </div>
<?php endif;?>
