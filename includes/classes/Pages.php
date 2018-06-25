<?php
declare(strict_types=1);
namespace PHPArcade;

class Pages
{
    protected $page;
    private function __construct()
    {
    }
    public static function getPage($id)
    {
        /* Used to display on the front-end website */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Pages_GetPagesbyPageID(:pageid);');
        $stmt->bindParam(':pageid', $id);
        $stmt->execute();
        if ($stmt->rowCount() !== 1) {
            die(Core::returnStatusCode(404));
        } else {
            return $stmt->fetch();
        }
    }
    public static function getPages()
    {
        /* Used to display all pages in the admin */
        $stmt = mySQL::getConnection()->prepare('CALL sp_Pages_GetPagesbyID_ASC();');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public static function getSubmitButton($active = 'active')
    {
        ?>
        <button class="btn btn-primary <?php echo $active; ?>" value="<?php echo gettext('submit'); ?>">
            <?php echo gettext('submit'); ?>
        </button><?php
    }
    public static function getEditButton($id, $act, $mthd, $text, $style = 'primary')
    {
        ?>
        <div class="btn-group">
            <a class="btn btn-<?php echo $style; ?>"
                href="<?php echo SITE_URL_ADMIN; ?>index.php?act=<?php echo $act; ?>&amp;mthd=<?php echo $mthd; ?>&amp;id=<?php echo $id; ?>">
                <?php echo $text; ?>
            </a>
        </div><?php
    }
    public static function getDeleteButton($id, $act, $mthd = 'delete-do')
    {
        ?>
        <div class="btn-group">
            <a class="btn btn-danger"
                href="<?php echo SITE_URL_ADMIN; ?>index.php?act=<?php echo $act; ?>&amp;mthd=<?php echo $mthd; ?>&amp;id=<?php echo $id; ?>">
                <?php echo gettext('delete'); ?>
            </a>
        </div><?php
    }
    public static function pageDelete($id)
    {
        $stmt = mySQL::getConnection()->prepare('CALL sp_Pages_DeletePagebyID(:pageid);');
        $stmt->bindParam(':pageid', $id);
        $stmt->execute();
        Core::showSuccess(gettext('deletesuccess'));
    }
    public static function pageAdd($id = null, $title, $content, $keywords, $description)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Pages_InsertPage(:pageid, :pagetitle, :pagecontent, :pagekeywords, :pagedescription);');
        $stmt->bindParam(':pageid', $id);
        $stmt->bindParam(':pagetitle', $title);
        $stmt->bindParam(':pagecontent', $content);
        $stmt->bindParam(':pagekeywords', $keywords);
        $stmt->bindParam(':pagedescription', $description);
        $stmt->execute();
        Core::showSuccess(gettext('addsuccess'));
    }
    public static function pageUpdate($id, $title, $content, $description, $keywords)
    {
        $stmt =
            mySQL::getConnection()->prepare('CALL sp_Pages_UpdatePage(:id, :title, :content, :keywords, :description);');
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':keywords', $keywords);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
    }
    private function __clone()
    {
    }
}
