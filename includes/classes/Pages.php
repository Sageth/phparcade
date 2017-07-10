<?php
declare(strict_types = 1);
Core::stopDirectAccess();

class Pages {
    protected $page;
    private function __construct() {
    }
	public static function getPage($id) {
        /* Used to display on the front-end website */
        /* TODO: Convert to _sp */
        $sql = 'SELECT * FROM pages WHERE `id` = :pageid LIMIT 1';
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->bindParam(':pageid', $id);
        $stmt->execute();
        $page = $stmt->fetch();
        $stmt->closeCursor();
        return $page;
    }
	public static function getPages() {
        /* Used to display all pages in the admin */
        /* TODO: Convert to _sp */
        $sql = "SELECT * FROM `pages` WHERE `id` != '' ORDER BY `id` ASC;";
        $stmt = mySQL::getConnection()->prepare($sql);
        $stmt->execute();
        $page = $stmt->fetchAll();
        $stmt->closeCursor();
        return $page;
    }
	public static function getSubmitButton($active = 'active') { ?>
        <button type="submit" class="btn btn-primary <?php echo $active; ?>" value="<?php echo gettext('submit'); ?>">
            <?php echo gettext('submit'); ?>
        </button><?php
    }
	public static function getGetCodeButton($id, $act, $mthd) { ?>
        <div class="btn-group">
            <a class="btn btn-info"
               href="<?php echo SITE_URL_ADMIN; ?>index.php?act=<?php echo $act; ?>&amp;mthd=<?php echo $mthd; ?>&amp;id=<?php echo $id; ?>">
                    <?php echo gettext('edit'); ?>
            </a>
        </div><?php
    }
	public static function getEditButton($id, $act, $mthd, $text, $style = 'primary') { ?>
        <div class="btn-group">
            <a class="btn btn-<?php echo $style; ?>"
                href="<?php echo SITE_URL_ADMIN; ?>index.php?act=<?php echo $act; ?>&amp;mthd=<?php echo $mthd; ?>&amp;id=<?php echo $id; ?>">
                    <?php echo $text; ?>
            </a>
        </div><?php
    }
	public static function getFeatureGameButton($gameid) { ?>
        <div class="btn-group">
            <a class="btn btn-info disabled"
                href="<?php echo SITE_URL_ADMIN; ?>index.php?act=media&amp;mthd=feature&amp;id=<?php echo $gameid; ?>">
                    <?php echo gettext('feature'); ?>
            </a>
        </div><?php
    }
	public static function getDeleteButton($id, $act, $mthd = 'delete-do') { ?>
        <div class="btn-group">
            <a class="btn btn-danger"
                href="<?php echo SITE_URL_ADMIN; ?>index.php?act=<?php echo $act; ?>&amp;mthd=<?php echo $mthd; ?>&amp;id=<?php echo $id; ?>">
                    <?php echo gettext('delete'); ?>
            </a>
        </div><?php
    }
	public static function pageDelete($id) {
        /* TODO: Convert to _sp */
        $sql = 'DELETE FROM `pages` WHERE `id` = :pageid;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':pageid', $id);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('deletesuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function pageAdd($id = null, $title, $content, $keywords, $description) {
        /* TODO: Make this use index */
        /* TODO: Convert to _sp */
        $sql = 'INSERT INTO `pages` ( `id` , `title` , `content` , `keywords` , `description` )
				VALUES (:pageid, :pagetitle, :pagecontent, :pagekeywords, :pagedescription)';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':pageid', $id);
            $stmt->bindParam(':pagetitle', $title);
            $stmt->bindParam(':pagecontent', $content);
            $stmt->bindParam(':pagekeywords', $keywords);
            $stmt->bindParam(':pagedescription', $description);
            $stmt->execute();
            $stmt->closeCursor();
            Core::showSuccess(gettext('addsuccess'));
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
	public static function pageUpdate($id, $title, $content, $description, $keywords) {
        /* TODO: Convert to _sp */
        $sql =
            'UPDATE `pages` SET `title` = :title, `content` = :content, `description` = :description, `keywords` = :keywords WHERE `id` = :id;';
        try {
            $stmt = mySQL::getConnection()->prepare($sql);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':keywords', $keywords);
            $stmt->bindParam(':title', $title);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            Core::showError($e->getMessage());
        }
    }
    private function __clone() {
    }
}