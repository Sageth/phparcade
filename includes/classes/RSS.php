<?php
declare(strict_types=1);
namespace PHPArcade;
use DateTime;

class RSS
{
    public static function GetAtomFeed($array)
    {
        $dbconfig = Core::getDBConfig();
        $dt = new DateTime();
        $now = $dt->format(DATE_ATOM);
        ?>
        <?xml version="1.0" encoding="<?php echo CHARSET;?>"?>
        <feed xmlns:atom="http://www.w3.org/2005/Atom">
            <title type="text"><?php echo $dbconfig['sitetitle']; ?></title>
            <subtitle>Free online flash games and html5 games and mobile games to play with your friends.</subtitle>
            <link href="<?php echo SITE_URL; ?>" hreflang="en"/>
            <link href="<?php echo $dbconfig['rssfeed']; ?>" rel="self" type="application/atom+xml" />
            <id><?php echo SITE_URL; ?></id>
            <description><?php echo $dbconfig['metadesc'];?></description>
            <updated><?php echo $now; ?></updated>
            <author>
                <name>Sage C. Russell IV</name>
                <uri>https://github.com/Sageth/phparcade</uri>
            </author>
            <?php
            for ($i = 0; $i < $dbconfig['rssnumlatest']; $i++) {
                $image = IMG_URL . $array[$i]['nameid'] . EXT_IMG;
                $title = $array[$i]['name'];
                $desc = $array[$i]['desc'];
                $link = Core::getLinkGame($array[$i]['id']);
                $timestamp_rfc3339 = DateTime::createFromFormat(DATE_ATOM, strval($array[$i]['release_date']));?>
                <entry>
                    <id><?php echo $link; ?></id>
                    <title><?php echo $title; ?></title>
                    <link href="<?php echo $link; ?>"/>
                    <summary><![CDATA[<?php echo $desc; ?>]]></summary>
                    <content type="xhtml">
                        <div xmlns="http://www.w3.org/1999/xhtml">
                            <a href="<?php echo $link;?>">
                                <img src="<?php echo $image;?>" alt="<?php echo $title;?>" width="200" height="200" />
                            </a>
                            <p><![CDATA[<?php echo $desc; ?>]]></p>
                        </div>
                    </content>
                    <updated><?php echo $timestamp_rfc3339; ?></updated>
                    <published><?php echo $timestamp_rfc3339; ?></published>
                    <logo><?php echo $image;?></logo>
                    <icon><?php echo $image;?></icon>
                    <guid><?php echo $link; ?></guid>
                </entry><?php echo PHP_EOL;
            } ?>
        </feed>
        <?php
        return;
    }
}
