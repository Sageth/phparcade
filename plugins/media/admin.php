<?php
function media_links()
{
    Administrations::addLink(gettext('gamesmedia'), 'index.php?act=media');
}

Administrations::addSubLink(gettext('addcategory'), 'index.php?act=media&amp;mthd=addcat-form', 'media');
Administrations::addSubLink(gettext('addmedia'), 'index.php?act=media&amp;mthd=addgame-form', 'media');
Administrations::addSubLink(gettext('configuration'), 'index.php?act=media&amp;mthd=config-form', 'media');
Administrations::addSubLink(gettext('inactivegames'), 'index.php?act=media&amp;mthd=inactive', 'media');
Administrations::addSubLink(gettext('managecat'), 'index.php?act=media&amp;mthd=manage-cat', 'media');
Administrations::addSubLink(gettext('managemedia'), 'index.php?act=media&amp;mthd=manage', 'media');
/**
 * @param $mthd
 */
function media_admin($mthd)
{
    $dbconfig = Core::getInstance()->getDBConfig();
    switch ($mthd) {
        case 'addcat-do':
            $order = Games::getCategoryIDMax();
            if ($_POST['name'] == "") {
                Core::showWarning(gettext('allfieldserror'));
            } else {
                Games::insertCategory(null, $_POST['name'], $_POST['desc'], $_POST['keywords'], $order, $_POST['type']);
            }
            break;
        case 'addcat-form': ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('category'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title='name' name='name'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('type'); ?></label>
                                <input class="form-control" title="type" name='type' value='Games'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('description'); ?></label>
                                <textarea class="form-control" title="description" name='desc' rows='6'></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('keywords'); ?></label>
                                <textarea class="form-control"
                                          title="keywords"
                                          name="keywords"
                                          cols="42"
                                          rows="6">
                                </textarea>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='addcat-do'/>
                <?php Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'addgame-do':
            // TODO: Break this up into smaller functions
            $dbconfig = Core::getInstance()->getDBConfig();
            //Check that the game isn't already added
            $gameid =
                (!empty(strtolower(pathinfo($_FILES['swffile']['name'], PATHINFO_FILENAME)))) ? strtolower(pathinfo($_FILES['swffile']['name'], PATHINFO_FILENAME)) : strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_FILENAME));
            $rowcount1 = Games::getGameCountByNameID($gameid);
            $rowcount2 = Games::getGameCountByNameID(strtolower($_POST['name']));
            if ($rowcount1 == 0 && $rowcount2 == 0) { // If the game SWF hasn't already been added...
                //if release date wasn't supplied, insert in today's datetime
                if ($_POST['release_date'] == "") {
                    $release_date = Core::getCurrentDate();
                } else {
                    list($year, $month, $day) = explode('-', $_POST['release_date']);
                    $release_date = mktime(0, 0, 0, $month, $day, $year);
                }
                // SWF file processing
                if (!empty($_FILES['swffile']['name'])) {
                    $swffile = $_FILES['swffile']['name'];
                    $realswf = SWF_DIR . $swffile;
                    // Set type and nameID
                    $nameid = strtolower(pathinfo($swffile, PATHINFO_FILENAME)); //Get base name
                    $type = strtoupper(pathinfo($swffile, PATHINFO_EXTENSION)); //Get file extension
                    $validator = new FileUpload\Validator\Simple(1024 * 1024 *
                                                                 100, ['application/x-shockwave-flash']);  // File upload falications
                    $pathresolver = new FileUpload\PathResolver\Simple(SWF_DIR);     // Upload path
                    $filesystem = new FileUpload\FileSystem\Simple();               // The machine's filesystem
                    $fileupload = new FileUpload\FileUpload($_FILES['swffile'], $_SERVER);   // FileUploader itself
                    //Final prep
                    $fileupload->setPathResolver($pathresolver);
                    $fileupload->setFileSystem($filesystem);
                    $fileupload->addValidator($validator);
                    // Doing the actual upload
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    list($files, $headers) = $fileupload->processAll();
                    // TODO: Set up so that json output goes to screen for accurate error message
                    //$json = json_encode(['files' => $files]);
                    //var_dump(json_decode($json,true));
                    if ($type == 'SWF' && $_FILES['swffile']['error'] == 0) { // 0 Means uploaded without error?>
                        <div class="col-md-6 text-left"><?php
                            Core::showSuccess(gettext('uploadsuccess') . ': ' . $swffile); ?>
                        </div>
                        <div class="clearfix invisible"></div><?php
                    } else {
                        ?>
                        <div class="col-md-6 text-left"><?php
                            Core::showError(gettext('uploadfailed') . ': ' . $swffile); ?>
                        </div>
                        <div class="clearfix invisible"></div>
                        <div class="col-md-6 text-left"><?php
                            Core::showError(gettext('errorcode') . $_FILES['swffile']['error']); ?>
                        </div>
                        <div class="clearfix invisible"></div><?php
                        return;
                    }
                    //Now that it's uploaded, get Flash dimensions
                    $dimensions = getimagesize($realswf) ?? 0;
                    $gwidth = isset($gwidth) ? $dimensions[0] : $dbconfig['defgwidth'];
                    $gheight = isset($gheight) ? $dimensions[1] : $dbconfig['defgheight'];
                } else { // If no file was uploaded?>
                    <div class="col-md-6 text-left"><?php
                        Core::showWarning(gettext('noswffile')); ?>
                    </div>
                    <div class="clearfix invisible"></div><?php //Throw a warning
                    $type = 'CustomCode'; // Set Custom Code type
                    $gwidth = 0;
                    $gheight = 0; //Set default game width and height to 0
                }

                // Image file processing
                if (!empty($_FILES['imgfile']['name'])) {
                    $_FILES['imgfile']['name'] = strtolower($_FILES['imgfile']['name']);
                    $realimage = IMG_DIR . $_FILES['imgfile']['name'];
                    /** @noinspection PhpMethodParametersCountMismatchInspection */
                    $validator = new FileUpload\Validator\Simple(
                        1024 * 1024 * 10,
                        ['image/png'],
                        ['image/jpg'],
                        ['image/gif']
                    );  // File upload validations
                    $pathresolver = new FileUpload\PathResolver\Simple(IMG_DIR_NOSLASH);     // Upload path
                    $filesystem = new FileUpload\FileSystem\Simple();               // The machine's filesystem
                    $fileupload = new FileUpload\FileUpload($_FILES['imgfile'], $_SERVER);   // FileUploader itself
                    //Final prep
                    $fileupload->setPathResolver($pathresolver);
                    $fileupload->setFileSystem($filesystem);
                    $fileupload->addValidator($validator);

                    // Doing the actual upload
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    list($files, $headers) = $fileupload->processAll();

                    /* If there is no swf file (e.g. custom game code), then use the image name as the nameid for
                       the database.  Otherwise, the image should be saved as a .png to the IMG_DIR folder.
                       Files are saved in lowercase. */
                    $nameid = empty($_FILES['swffile']['name']) ? strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_FILENAME)) : IMG_DIR . strtolower(pathinfo($_FILES['imgfile']['name'] . EXT_IMG, PATHINFO_FILENAME));

                    try {
                        Games::convertImage($realimage, $nameid);
                        Games::addGame(null, $nameid, $gameorder = -1, $gwidth, $gheight, $type, $playcount = 0, $release_date);
                        Games::updateGameOrder();
                        return;
                    } catch (Exception $e) {
                        Core::showError(gettext('error') . ' ' . $e->getMessage());
                    }
                    return;
                } else {
                    //Images are required
                    Core::showError(gettext('selectafileerror'));
                    return;
                }
            } else {
                Core::showError(gettext('nameiderror'));
            }
            break;
        case "":
        case 'addgame-form': ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title="name" name="name"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('category'); ?></label>
                                <?php echo Games::getCategorySelect('cat'); ?>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('release_date'); ?></label>
                                <input class="form-control" title="release date" name='release_date'/>
                                <p class="help-block"><?php echo gettext('dateformat'); ?></p>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('uploadswf'); ?></label>
                                <input class="form-control" type='file' name='swffile'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('thumbnail'); ?></label>
                                <input class="form-control" type='file' name='imgfile'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('description'); ?></label>
                                <textarea class="form-control" title="description" name='desc' rows='6'></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('instructions'); ?></label>
                                <textarea class="form-control" title="instructions" name='instructions' rows='6'></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('customcode'); ?></label>
                                <textarea class="form-control" title="Custom Code" name='customcode' rows='6'></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('flags'); ?></label>
                                <select class="form-control" title="Flags" name="flags">
                                    <option value="highscore"><?php echo gettext('highscore'); ?></option>
                                    <option value="lowhighscore"><?php echo gettext('lowhighscore'); ?></option>
                                    <option value="" SELECTED><?php echo gettext('none'); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('keywords'); ?></label>
                                <textarea class="form-control"
                                          title="Keywords"
                                          name='keywords'
                                          cols='42'
                                          rows='6'></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='addgame-do'/>
                <?php Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'config-do':
            Administrations::updateConfig('twidth', $_POST['twidth']);
            Administrations::updateConfig('theight', $_POST['theight']);
            Administrations::updateConfig('defgwidth', $_POST['defgwidth']);
            Administrations::updateConfig('defgheight', $_POST['defgheight']);
            Administrations::updateConfig('order', $_POST['order']);
            Administrations::updateConfig('displaycattype', array_key_exists('displaycattype', $_POST) ? 'on' : 'off');
            Core::showSuccess(gettext('updatesuccess'));
            break;
        case 'config-form':
            $cattypechecked = ($dbconfig['displaycattype'] === 'on') ? 'checked' : ""; ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('thumbnails'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('width'); ?></label>
                                <input class="form-control" title="Thumbnail Width" name='twidth'
                                       value='<?php echo $dbconfig['twidth']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('height'); ?></label>
                                <input class="form-control" title="Thumbnail Height" name='theight'
                                       value='<?php echo $dbconfig['theight']; ?>'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('mediafiles'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('defaultwidth'); ?></label>
                                <input class="form-control" title="Default Game Width" name='defgwidth'
                                       value='<?php echo $dbconfig['defgwidth']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('defaultheight'); ?></label>
                                <input class="form-control" title="Default Game Height" name='defgheight'
                                       value='<?php echo $dbconfig['defgheight']; ?>'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('other'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('orderby'); ?></label>
                                <input class="form-control" title="Order" name='order'
                                       value='<?php echo $dbconfig['order']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label for="displaycattype"><?php echo gettext('displaycattype'); ?></label>
                                <input type="checkbox"
                                       name="displaycattype"
                                       id="displaycattype" <?php echo $cattypechecked; ?>
                                       data-toggle="toggle"/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='config-do'/>
                <?php Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'delete-cat-do':
            /* Delete the category and then reorder them */
            Games::deleteCategory($_REQUEST['id']);
            Games::updateCategoryOrder(Games::getCategories('ASC'));
            Core::showSuccess(gettext('deletesuccess'));
            break;
        case 'delete-do':
            $game = Games::getGame($_REQUEST['id']);
            if (isset($game['nameid'])) {
                // Delete files
                switch ($game['type']) {
                    case 'SWF':
                        $result = unlink(SWF_DIR . $game['nameid'] . '.swf');
                        break;
                    case 'PNG':
                        $result = unlink(IMG_DIR . $game['nameid'] . EXT_IMG);
                        break;
                    default:
                        $result = "";
                        break;
                }
                $result2 = unlink(IMG_DIR . $game['nameid'] . EXT_IMG);
                if (!$result) {
                    Core::showWarning(gettext('unabledeleteswferror'));
                }
                if (!$result2) {
                    Core::showWarning(gettext('unabledeleteimgerror'));
                }
                Games::deleteGame($_REQUEST['id']);
                Games::updateGameOrder();
                Core::showSuccess(gettext('deletesuccess'));
            } else {
                Core::showWarning(gettext('nogameselected'));
            }
            break;
        case 'editcat-do':
            Games::updateCategory(Core::encodeHTMLEntity($_POST['id']), Core::encodeHTMLEntity($_POST['name']), Core::encodeHTMLEntity($_POST['type']), Core::encodeHTMLEntity($_POST['desc']), Core::encodeHTMLEntity($_POST['keywords']));
            break;
        case 'editcat-form':
            $category = Games::getCategoryID($_REQUEST['id']); ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('general'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title="Name" name='name'
                                       value='<?php echo $category['name']; ?>'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('type'); ?></label>
                                <input class="form-control" title="Type" name='type'
                                       value='<?php echo $category['type']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('description'); ?></label>
                                <textarea class="form-control" title="Description" name='desc'
                                          rows='6'><?php echo $category['desc']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('keywords'); ?></label>
                                <textarea class="form-control" title="Keywords" name='keywords'
                                          rows='6'><?php echo $category['keywords']; ?></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='editcat-do'/>
                <input type='hidden' name='id' value='<?php echo $category['id']; ?>'/>
                <?php Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'editgame-do':
            if ($_POST['release_date'] == "") {
                $_POST['release_date'] = Core::getCurrentDate();
            } else {
                list($year, $month, $day) = explode('-', $_POST['release_date']);
                $_POST['release_date'] = mktime(0, 0, 0, $month, $day, $year);
            }
            Games::updateGame($_POST['id']);
            break;
        case 'editgame-form':
            $game = Games::getGame($_REQUEST['id']);
            $activechecked = ($game['active'] === 'Yes' || $game['active'] === 'on') ? 'checked' : ""; ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('mediainfo'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('nameoffiles'); ?></label>
                                <input class="form-control" title="Name of Files" name='nameid'
                                       value='<?php echo $game['nameid']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control"
                                       title="Game Name"
                                       name='name'
                                       value='<?php echo $game['name']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('release_date'); ?></label>
                                <input class="form-control" title="Release Date" name='release_date'
                                       value='<?php echo date('Y-m-d', $game['release_date']); ?>'/>
                                <p class="help-block"><?php echo gettext('dateformat'); ?></p>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('category'); ?></label>
                                <?php echo Games::getCategorySelect('cat', $game['cat']); ?>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('description'); ?></label>
                                <textarea class="form-control"
                                          title="Description"
                                          name='desc'
                                          rows='6'><?php echo $game['desc']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('instructions'); ?></label>
                                <textarea class="form-control" title="Instructions" name='instructions'
                                          rows='6'><?php echo $game['instructions']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('customcode'); ?></label>
                                <textarea class="form-control" title="Custom Code" name='customcode'
                                          rows='6'><?php echo $game['customcode']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('active'); ?></label>
                                    <input class="form-control"
                                       title="Active"
                                       type="checkbox"
                                       name="active"
                                       id="active" <?php echo $activechecked; ?>
                                       data-toggle="toggle"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('width'); ?></label>
                                <input class="form-control"
                                       title="Width"
                                       name='width'
                                       value='<?php echo $game['width']; ?>'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('height'); ?></label>
                                <input class="form-control" title="Height" name='height'
                                       value='<?php echo $game['height']; ?>'/>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label><?php echo gettext('flags'); ?></label>
                                <select class="form-control" title="Flags" name="flags"><?php
                                    switch ($game['flags']) {
                                        case 'highscore': ?>
                                            <option value='highscore' SELECTED><?php echo gettext('highscore'); ?></option>
                                            <option value='lowhighscore'><?php echo gettext('lowhighscore'); ?></option>
                                            <option value=''><?php echo gettext('none'); ?></option><?php
                                            break;
                                        case 'lowhighscore': ?>
                                            <option value='lowhighscore'
                                                    SELECTED><?php echo gettext('lowhighscore'); ?></option>
                                            <option value='highscore'><?php echo gettext('highscore'); ?></option>
                                            <option value=''><?php echo gettext('none'); ?></option><?php
                                            break;
                                        case '': ?>
                                            <option value='' SELECTED><?php echo gettext('none'); ?></option>
                                            <option value='highscore'><?php echo gettext('highscore'); ?></option>
                                            <option value='lowhighscore'><?php echo gettext('lowhighscore'); ?></option><?php
                                            break;
                                        default: ?>
                                            <option value='ERROR' SELECTED><?php echo gettext('error'); ?></option>
                                            <option value='highscore'><?php echo gettext('highscore'); ?></option>
                                            <option value='lowhighscore'><?php echo gettext('lowhighscore'); ?></option>
                                            <option value=''><?php echo gettext('none'); ?></option><?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('keywords'); ?></label>
                                <textarea class="form-control" title="Keywords" name='keywords'
                                          rows='6'><?php echo $game['keywords']; ?></textarea>
                            </div>
                        </div>
                        <div class="panel-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='id' value='<?php echo $game['id']; ?>'/>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='editgame-do'/>
                <?php Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'inactive':
            $games = Games::getGamesInactive(); ?>
            <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo gettext('inactive-m'); ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr><?php echo gettext('name'); ?></tr>
                                <tr>&nbsp;</tr>
                            </thead>
                            <tbody><?php
                                foreach ($games as $game) {
                                    ?>
                                    <tr class="odd gradeA">
                                    <td><?php echo $game['name']; ?></td>
                                    <td>
                                        <?php Pages::getEditButton($game['id'], 'media', 'editgame-form', gettext('edit')); ?>
                                        &nbsp;
                                        <?php Pages::getDeleteButton($game['id'], 'media'); ?>
                                    </td>
                                    </tr><?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">&nbsp;</div>
            </div>
            </div><?php
            break;
        case 'manage':
            $cat = 'all';
            /* If a page number isn't explicitly passed, assume page 1 */
            $_GET['page'] = $_GET['page'] ?? 1;
            $games = Games::getGames($cat, 0, 10, $_GET['page'], 50);
            $pages = Core::getAdminGamePageCount(); ?>

            <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo gettext('manage'); ?>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th><?php echo gettext('name'); ?></th>
                                    <th><?php echo gettext('category'); ?></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody><?php
                                foreach ($games as $game) {
                                    ?>
                                    <tr class="odd gradeA"><?php
                                    if ($game['active'] == 'No') {
                                        ?>
                                        <td class="warning"><?php echo $game['name']; ?></td><?php
                                    } elseif ($game['active'] == 'Yes') {
                                        ?>
                                        <td><?php echo $game['name']; ?></td><?php
                                    } else {
                                        ?>
                                        <td class="danger"><?php echo $game['name']; ?></td><?php
                                    } ?>
                                    <td><?php echo $game['cat']; ?></td>
                                    <td>
                                        <?php Pages::getFeatureGameButton($game['id']); ?>
                                        &nbsp;
                                        <?php Pages::getEditButton($game['id'], 'media', 'editgame-form', gettext('edit')); ?>
                                        &nbsp;
                                        <?php Pages::getDeleteButton($game['id'], 'media'); ?>
                                    </td>
                                    </tr><?php
                                } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_info" id="dataTables-example_info" role="alert"
                                     aria-live="polite">
                                    <p>Showing <?php echo $_GET['page']; ?> of <?php echo $pages; ?> pages</p>
                                </div>
                            </div>
                            <div class="col-sm-11">
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example-paginate">
                                    <p><?php echo gettext('pages'); ?></p>
                                    <ul class="pagination"><?php
                                        for ($i = 0; $i < $pages; ++$i) {
                                            ?>
                                            <li>
                                            <a href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&amp;mthd=manage&amp;order=name&amp;cat=<?php echo $cat; ?>&amp;page=<?php echo $i +
                                                                                                                                                                                     1; ?>"
                                               class="paginate_button" aria-controls="dataTables-example" tabindex="0">
                                                <?php echo $i + 1; ?>
                                            </a>
                                            </li><?php
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div><?php
            break;
        case 'manage-cat':
            $categories = Games::getCategories('ASC'); ?>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php echo gettext('manage'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover grid">
                                <thead>
                                    <tr>
                                        <th><?php echo gettext('order'); ?></th>
                                        <th><?php echo gettext('name'); ?></th>
                                        <th><?php echo gettext('description'); ?></th>
                                        <th><?php echo gettext('type'); ?></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable"><?php
                                    foreach ($categories as $category) {
                                        ?>
                                        <tr class="odd gradeA" id="rowsort-<?php echo $category['id']; ?>">
                                            <td class="index">
                                                <?php echo $category['order']; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&amp;mthd=manage&amp;order=name&amp;cat=<?php echo $category['name']; ?>">
                                                    <?php echo $category['name']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $category['desc']; ?></td>
                                            <td><?php echo $category['type']; ?></td>
                                            <td>
                                                <?php Pages::getEditButton($category['id'], 'media', 'editcat-form', gettext('edit')); ?>&nbsp;
                                                <?php Pages::getDeleteButton($category['id'], 'media', 'delete-cat-do'); ?>
                                            </td>
                                        </tr><?php
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><?php
            break;
        default:
    }
} ?>