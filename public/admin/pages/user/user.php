<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 13.09.2017
 * Time: 13:25
 */
if(!defined('MAIN_PATH')) {
    header("Location: /");
    exit();
}

$pageNr = filter_input(INPUT_GET, 'pageNr', FILTER_VALIDATE_INT);
$next = $pageNr+1;
$previous = $pageNr-1;

if(empty($pageNr)) {
    $pageNrInDb = 0;
} else {
    $pageNrInDb = $pageNr * MAX_CATEGORIES;
}

$categories = User::findAll($pageNrInDb, MAX_CATEGORIES);

$countCategories = User::count_all();

$pagesCount = ceil( $countCategories / MAX_CATEGORIES);


?>
<<<<<<< HEAD
    <div class="row">
        <div class="col-sm-4">
            <h3><a href="<?php echo ADMIN_URL . "?page=users"; ?>"><span class="glyphicon glyphicon-plus-sign"></span> Lisa</a></h3>
        </div>
    </div>
=======

>>>>>>> master
<?php // echo isset($session->message) ? $session->message : '' ?>

<?php if (!empty($categories)) : ?>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nimi</th>
            <th>Lisatud</th>
            <th>Status</th>
<<<<<<< HEAD
            <th>Rights</th>
=======
>>>>>>> master
            <th>Muuda</th>
            <th>Kustuta</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat) : ?>
            <tr>
                <td><?php echo $cat->ID ?></td>
                <td><?php echo $cat->username ?></td>
                <td><?php echo $cat->added ?></td>
                <td><?php echo $cat->status ?></td>
<<<<<<< HEAD
                <td><?php echo $cat->rights ?></td>
=======
>>>>>>> master
                <td>
                    <a href="<?php echo ADMIN_URL . "?page=users&ID=" . $cat->ID; ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
<<<<<<< HEAD
                    <a href="<?php echo ADMIN_URL . "?page=delete-user&ID=" . $cat->ID; ?>">
=======
                    <a href="<?php echo ADMIN_URL . "?page=deleteUsers&ID=" . $cat->ID; ?>">
>>>>>>> master
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <ul class="pager">
        <?php if(!empty($pageNr)) : ?>
            <?php if($pageNr == 1) : ?>
                <li><a href="<?php echo ADMIN_URL . "?page=user"; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php else: ?>
                <li><a href="<?php echo ADMIN_URL . "?page=user&pageNr=" . $previous; ?>"><?php echo translate("previous_btn") ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($pagesCount-1 > $pageNr ) : ?>
            <li><a href="<?php echo ADMIN_URL . "?page=user&pageNr=" . $next; ?>"><?php echo translate("next_btn") ?></a></li>
        <?php endif; ?>
    </ul>
<?php else :
    echo infoMessage('info', 'Kategooriad puuduvad');
endif; ?>