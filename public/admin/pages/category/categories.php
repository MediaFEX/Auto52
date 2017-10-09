<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:38
 */
if(!defined('MAIN_PATH')) { 
    header("Location: /"); 
    exit(); 
}
if(!$session->is_logged_in() || !User::checkRights($session->user_id, 'categories.php')) { //Repels users and moderators
    $session->message('<div class="alert alert-danger">You don\'t have the rights to view that page.</div>');//If they don't have access throw them out.
    reDirectTo(ADMIN_URL . '?page=home');
}
//Current variable that I'm using
$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT); 
$next = $pageNr+1; 
$previous = $pageNr-1; 

if(empty($pageNr)) { //Controls which page you're on
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
} 





$categories = Category::findAll($pageNrInDb, MAX_CATEGORIES);   //Finds categories depending on current page and max_categories on a single page

$countCategories = Category::count_all(); //Counts how many categories there are in total

$pagesCount = ceil( $countCategories / MAX_CATEGORIES); //Divides category count with max categories, to determine how many pages there will be.

//Displays 'Lisa' so I could add more categories when needed and also displays the current pages name
?> 
<div class="row">
    <div class="col-sm-8">
        <h3><a href="<?php echo ADMIN_URL . "?page=category"; ?>"><span class="glyphicon glyphicon-plus-sign"></span> Lisa</a></h3>
    </div>
    <div class="col-sm-4">
        <h3 class="text-right"><?php echo $pages[$page]['name'] ?></h3>
    </div>
</div>


<?php if (!empty($categories)) : //Generates the table with the category name, Id when it was added and it's parent, it's also possible to modify and delete them when needed ?>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>ET Nimi</th>
            <th>EN Nimi</th>
            <th>Lisatud</th>
            <th>Vanem</th>
            <th>Muuda</th>
            <th>Kustuta</th
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat) : ?>
            <tr>
                <td><?php echo $cat->ID ?></td>
                <td><?php echo $cat->et_name ?></td>
                <td><?php echo $cat->en_name ?></td>
                <td><?php echo $cat->added ?></td>
                <td>
                    <?php $parent = Category::find_by_ID($cat->parent); ?>
                    <?php echo empty($parent) ? 'Põhikategooria' : $parent->$categoryLang; ?>
                </td>
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=category&ID=" . $cat->ID; ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                    <span
                        data-url="pages/category/delete"
                        data-delete-id="<?php echo $cat->ID; ?>"
                        class="glyphicon glyphicon-trash bg-danger delete-confirm"
                        style="cursor: pointer;">
                    </span>
                </td>
            </tr>
        <?php endforeach; ?> 
        </tbody> 
    </table> 
    <?php pager($pageNr, $pagesCount, $next, $previous, 'categories'); //Calls for next page and previous?>
<?php else : //If there's nothing to show, say it
    echo infoMessage('info', 'Kategooriad puuduvad'); 
endif; ?>

