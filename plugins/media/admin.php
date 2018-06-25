<?php
function media_admin($mthd)
{
    switch ($mthd) {
        case 'addcat-do':
            $order = PHPArcade\Games::getCategoryIDMax();
            if ($_POST['name'] == "") {
                PHPArcade\Core::showWarning(gettext('allfieldserror'));
            } else {
                PHPArcade\Games::insertCategory(null, $_POST['name'], $_POST['desc'], $_POST['keywords'], $order, $_POST['type']);
            }
            break;
        case 'addcat-form': ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('category'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title='name' name='name'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="card-body">
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
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='addcat-do'/>
                <?php PHPArcade\Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'addgame-do':
            // TODO: Break this up into smaller functions
            $dbconfig = PHPArcade\Core::getDBConfig();

            //Check that the game isn't already added
            $gameid =
                (!empty(strtolower(pathinfo($_FILES['swffile']['name'], PATHINFO_FILENAME)))) ? strtolower(pathinfo($_FILES['swffile']['name'], PATHINFO_FILENAME)) : strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_FILENAME));
            $rowcount1 = PHPArcade\Games::getGameCountByNameID($gameid);
            $rowcount2 = PHPArcade\Games::getGameCountByNameID(strtolower($_POST['name']));
            if ($rowcount1 == 0 && $rowcount2 == 0) { // If the game SWF hasn't already been added...
                //if release date wasn't supplied, insert in today's datetime
                if ($_POST['release_date'] == "") {
                    $release_date = PHPArcade\Core::getCurrentDate();
                } else {
                    list($year, $month, $day) = explode('-', $_POST['release_date']);
                    $release_date = mktime(0, 0, 0, $month, $day, $year);
                }
                // SWF file processing
                if (!empty($_FILES['swffile']['name'])) {
                    $swffile = $_FILES['swffile']['name'];
                    $realswf = SWF_DIR . $swffile;
                    // Set type and nameID
                    /** @noinspection PhpUnusedLocalVariableInspection */
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
                            PHPArcade\Core::showSuccess(gettext('uploadsuccess') . ': ' . $swffile); ?>
                        </div>
                        <div class="clearfix invisible"></div><?php
                    } else {
                        ?>
                        <div class="col-md-6 text-left"><?php
                            PHPArcade\Core::showError(gettext('uploadfailed') . ': ' . $swffile); ?>
                        </div>
                        <div class="clearfix invisible"></div>
                        <div class="col-md-6 text-left"><?php
                            PHPArcade\Core::showError(gettext('errorcode') . $_FILES['swffile']['error']); ?>
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
                        PHPArcade\Core::showWarning(gettext('noswffile')); ?>
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
                    $nameid = empty($_FILES['swffile']['name']) ? strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_FILENAME)) : strtolower(pathinfo($_FILES['imgfile']['name'], PATHINFO_FILENAME));

                    try {
                        PHPArcade\Games::convertImage($realimage, $nameid);
                        PHPArcade\Games::addGame(null, $nameid, $gameorder = -1, $gwidth, $gheight, $type, $playcount = 0, $release_date);
                        PHPArcade\Games::updateGameOrder();
                        return;
                    } catch (Exception $e) {
                        PHPArcade\Core::showError(gettext('error') . ' ' . $e->getMessage());
                    }
                    return;
                } else {
                    //Images are required
                    PHPArcade\Core::showError(gettext('selectafileerror'));
                    return;
                }
            } else {
                PHPArcade\Core::showError(gettext('nameiderror'));
            }
            break;
        case "":
        case 'addgame-form': ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="card-deck mt-4">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control is-invalid" title="name" name="name"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('category'); ?></label>
                                <?php echo PHPArcade\Games::getCategorySelect('cat'); ?>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('release_date'); ?></label>
                                <input class="form-control" title="release date" name='release_date'/>
                                <small class="card-text"><?php echo gettext('dateformat'); ?></small>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('uploadswf'); ?></label>
                                <input class="form-control is-invalid" type='file' name='swffile'/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('thumbnail'); ?></label>
                                <input class="form-control" type='file' name='imgfile'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('description'); ?></label>
                                <textarea class="form-control" title="description" name='desc' rows='6'></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('instructions'); ?></label>
                                <textarea class="form-control" title="instructions" name='instructions' rows='6'></textarea>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('information'); ?>
                        </div>
                        <div class="card-body">
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
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='addgame-do'/>
                <?php PHPArcade\Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'delete-cat-do':
            /* Delete the category and then reorder them */
            PHPArcade\Games::deleteCategory($_REQUEST['id']);
            PHPArcade\Games::updateCategoryOrder(PHPArcade\Games::getCategories('ASC'));
            PHPArcade\Core::showSuccess(gettext('deletesuccess'));
            break;
        case 'delete-do':
            $game = PHPArcade\Games::getGame($_REQUEST['id']);
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
                    PHPArcade\Core::showWarning(gettext('unabledeleteswferror'));
                }
                if (!$result2) {
                    PHPArcade\Core::showWarning(gettext('unabledeleteimgerror'));
                }
                PHPArcade\Games::deleteGame($_REQUEST['id']);
                PHPArcade\Games::updateGameOrder();
                PHPArcade\Core::showSuccess(gettext('deletesuccess'));
            } else {
                PHPArcade\Core::showWarning(gettext('nogameselected'));
            }
            break;
        case 'editcat-do':
            PHPArcade\Games::updateCategory(PHPArcade\Core::encodeHTMLEntity($_POST['id']), PHPArcade\Core::encodeHTMLEntity($_POST['name']), PHPArcade\Core::encodeHTMLEntity($_POST['type']), PHPArcade\Core::encodeHTMLEntity($_POST['desc']), PHPArcade\Core::encodeHTMLEntity($_POST['keywords']));
            break;
        case 'editcat-form':
            $category = PHPArcade\Games::getCategoryID($_REQUEST['id']); ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('general'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control" title="Name" name='name'
                                       value='<?php echo $category['name']; ?>'/>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="card-body">
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
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='editcat-do'/>
                <input type='hidden' name='id' value='<?php echo $category['id']; ?>'/>
                <?php PHPArcade\Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'editgame-do':
            if ($_POST['release_date'] == "") {
                $_POST['release_date'] = PHPArcade\Core::getCurrentDate();
            } else {
                list($year, $month, $day) = explode('-', $_POST['release_date']);
                $_POST['release_date'] = mktime(0, 0, 0, $month, $day, $year);
            }
            PHPArcade\Games::updateGame($_POST['id']);
            break;
        case 'editgame-form':
            $game = PHPArcade\Games::getGame($_REQUEST['id']);
            $activechecked = ($game['active'] === 'Yes' || $game['active'] === 'on') ? 'checked' : ""; ?>
            <form action='<?php echo SITE_URL_ADMIN; ?>index.php' method='POST' enctype='multipart/form-data'>
                <div class="card-deck">
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('mediainfo'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('nameoffiles'); ?></label>
                                <input class="form-control" title="Name of Files" name="nameid"
                                       value="<?php echo $game['nameid']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('name'); ?></label>
                                <input class="form-control"
                                       title="Game Name"
                                       name="name"
                                       value="<?php echo $game['name']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('category'); ?></label>
                                <?php echo PHPArcade\Games::getCategorySelect('cat', $game['cat']); ?>
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
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('otherinfo'); ?>
                        </div>
                        <div class="card-body">
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
                        <div class="card-footer">&nbsp;</div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <?php echo gettext('status'); ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label><?php echo gettext('active'); ?></label>
                                <input class="form-control"
                                       title="Active"
                                       type="checkbox"
                                       name="active"
                                       id="active" <?php echo $activechecked; ?>
                                       data-toggle="toggle"
                                       data-onstyle="success"
                                       data-offstyle="danger"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('customcode'); ?></label>
                                <textarea class="form-control" title="Custom Code" name='customcode'
                                          rows='6'><?php echo $game['customcode']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('width'); ?></label>
                                <input class="form-control"
                                       title="Width"
                                       name="width"
                                       value="<?php echo $game['width']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('height'); ?></label>
                                <input class="form-control" title="Height" name="height"
                                       value="<?php echo $game['height']; ?>"/>
                            </div>
                            <div class="form-group">
                                <label><?php echo gettext('release_date'); ?></label>
                                <input class="form-control" title="Release Date" name='release_date'
                                       value='<?php echo date('Y-m-d', $game['release_date']); ?>'/>
                                <small class="card-block"><?php echo gettext('dateformat'); ?></small>
                            </div>
                        </div>
                        <div class="card-footer">&nbsp;</div>
                    </div>
                </div>
                <input type='hidden' name='id' value='<?php echo $game['id']; ?>'/>
                <input type='hidden' name='act' value='media'/>
                <input type='hidden' name='mthd' value='editgame-do'/>
                <?php PHPArcade\Pages::getSubmitButton(); ?>
            </form><?php
            break;
        case 'inactive':
            $games = PHPArcade\Games::getGamesInactive(); ?>
                <div class="container">
                    <div class="mt-4">
                        <?php echo gettext('inactive-m'); ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead class="thead-light">
                                <th><?php echo gettext('name'); ?></th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody><?php
                                foreach ($games as $game) {
                                    ?>
                                    <tr>
                                        <td><?php echo $game['name']; ?></td>
                                        <td>
                                            <?php PHPArcade\Pages::getEditButton($game['id'], 'media', 'editgame-form', gettext('edit')); ?>
                                            &nbsp;
                                            <?php PHPArcade\Pages::getDeleteButton($game['id'], 'media'); ?>
                                        </td>
                                    </tr><?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            break;
        case 'manage':
            $games = PHPArcade\Games::getGames($cat = 'all', 0, 10, 1, 5000); ?>
            <div class="container">
                <div class="input-group col-lg-10">
                    <span class="input-group-addon info col-lg-4" id="user-addon">
                        <div class="align-middle mt-2">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <?php echo gettext('search');?>
                        </div>
                    </span>
                    <input type="text" class="form-control" id="userList" onkeyup="filterTable()" placeholder="<?php echo gettext('gamefilter');?>" aria-describedby="user-addon">
                </div>
                <div class="row">&nbsp;</div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col"><?php echo gettext('name'); ?></th>
                                <th scope="col"><?php echo gettext('category'); ?></th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody><?php
                            foreach ($games as $game) {
                                ?>
                                <tr><?php
                                    switch ($game['active']) {
                                        case 'Yes': ?>
                                            <td>
                                                <?php echo $game['name']; ?>
                                            </td><?php
                                            break;
                                        case 'No': ?>
                                            <td class="warning">
                                                <?php echo $game['name']; ?>
                                            </td><?php
                                            break;
                                        default: ?>
                                            <td class="danger">
                                                <?php echo $game['name']; ?>
                                            </td><?php
                                            break;
                                    } ?>
                                    <td>
                                        <?php echo $game['cat']; ?>
                                    </td>
                                    <td class="col-md-4">
                                        <?php PHPArcade\Pages::getEditButton($game['id'], 'media', 'editgame-form', gettext('edit')); ?>
                                        &nbsp;
                                        <?php PHPArcade\Pages::getDeleteButton($game['id'], 'media'); ?>
                                    </td>
                                </tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script async src="<?php echo JS_TABLEFILTER;?>"></script><?php
            break;
        case 'manage-cat':
            $categories = PHPArcade\Games::getCategories('ASC'); ?>
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover grid">
                        <thead>
                            <tr>
                                <th scope="col"><?php echo gettext('order'); ?></th>
                                <th scope="col"><?php echo gettext('name'); ?></th>
                                <th scope="col"><?php echo gettext('description'); ?></th>
                                <th scope="col"><?php echo gettext('type'); ?></th>
                                <th scope="col">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="sortable"><?php
                            foreach ($categories as $category) {
                                ?>
                                <tr id="rowsort-<?php echo $category['id']; ?>">
                                    <th scope="row" class="index">
                                        <?php echo $category['order']; ?>
                                    </th>
                                    <td>
                                        <a href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&amp;mthd=manage&amp;order=name&amp;cat=<?php echo $category['name']; ?>">
                                            <?php echo $category['name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $category['desc']; ?></td>
                                    <td><?php echo $category['type']; ?></td>
                                    <td class="col">
                                        <?php PHPArcade\Pages::getEditButton($category['id'], 'media', 'editcat-form', gettext('edit')); ?>&nbsp;
                                        &nbsp;
                                        <?php PHPArcade\Pages::getDeleteButton($category['id'], 'media', 'delete-cat-do'); ?>
                                    </td>
                                </tr><?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div><?php
            break;
        default:
    }
} ?>