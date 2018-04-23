<?php
if (!isset($_SESSION)) {
    session_start();
}
PHPArcade\Core::showCategoryList(PHPArcade\Games::getCategories('ASC'));
