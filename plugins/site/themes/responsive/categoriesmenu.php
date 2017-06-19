<?php
if(!isset($_SESSION)){session_start();}
Core::showCategoryList(Games::getCategories('ASC'));
