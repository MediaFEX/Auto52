<?php 
/** 
 * Created by PhpStorm. 
 * User: andrus.jakobson
 * Date: 10.05.2016 
 * Time: 9:10 
 */
require_once "../include/start.php";

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);

if(empty($page)) {
    $page = 'home';
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_VALIDATE_INT);

$categories = Category::find_all();
$categories = createCategoryArray($categories);

$args = array(
    'categories'   => [
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_REQUIRE_ARRAY,
    ],
    'action'     => FILTER_SANITIZE_STRING,
);

get_template('head'); ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-center">AS Katused</h1>
            </div>
            <div class="col-lg-12 text-right">
                <?php if(LANG != 'et') : ?><span class="flag-icon flag-icon-ee btn-lg make-default-lang" data-lang="et"></span><?php endif; ?>
                <?php if(LANG != 'en') : ?><span class="flag-icon flag-icon-gb btn-lg make-default-lang" data-lang="en"></span><?php endif; ?>
                <?php if(LANG != 'de') : ?><span class="flag-icon flag-icon-de btn-lg make-default-lang" data-lang="de"></span><?php endif; ?>
            </div>
        </div>
        <div class="row">
            <!------------------------------------------------------------------------------------------------------------------------------------------------------------------->
            <nav class="navbar navbar-inverse sidebar" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Brand</a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
                            <li ><a href="#">Profile<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>
                            <li ><a href="#">Messages<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-envelope"></span></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
                                <ul class="dropdown-menu forAnimate" role="menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">One more separated link</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>
                            <li ><a href="#">Profile<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a></li>
                            <li ><a href="#">Messages<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-envelope"></span></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
                                <ul class="dropdown-menu forAnimate" role="menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">One more separated link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!--<div class="main">
            </div>  -->
            <!------------------------------------------------------------------------------------------------------------------------------------------------------------------->
            <div class="col-sm-3">
                <form method="post" action="<?php echo MAIN_URL; ?>">
                    <?php if(!empty($categories[0])) : foreach ($categories[0] as $category) : ?>
                        <input type="hidden" value="<?php echo $category->ID; ?>" name="categories[]">
                        <h3><?php echo $category->name; ?></h3>
                        <?php if(!empty($categories[$category->ID])) : ?>
                            <select name="categories[]">`
                                <option value="0"><?php echo translate("select") ?></option>
                                <?php foreach ($categories[$category->ID] as $subCategory) : ?>
                                    <option value="<?php echo $subCategory->ID; ?>"><?php echo $subCategory->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endforeach; endif; ?>
                    <hr>
                    <button class="btn btn-primary" type="submit" name="action" value="search">Search</button>
                </form>
            </div>
            <div class="col-sm-9">
                <?php if(!empty($page) && isset($pages[$page])) :
                    require_once MAIN_PAGES_PATH . $pages[$page]['path'];
                else :
                    require_once ADMIN_404;
                endif; ?>
            </div>
        </div>
    </div>

<?php get_template('footer');