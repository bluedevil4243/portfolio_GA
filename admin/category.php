<?php
require '../library/requires.php';
require '../partials/admin_header.php';


/**
 * SUPPRESSION
 */
if(isset($_GET['delete'])){
    checkCsrf();
    $id = $db->quote($_GET['delete']);
    $db->query("DELETE FROM categories WHERE id=$id");
    setFlash('la categorie a bien été supprimée');
    header('Location:category.php');
    die();
}

/**
 * Categories
 */
$select = $db->query('SELECT id, name, slug FROM categories');
$categories = $select->fetchAll();

?>

<h1>LES CATEGORIES</h1>

<p><a href="category_edit.php" class="btn btn-success">Ajouter une nouvelle categorie</a></p>

<table class="table table-striped">
<thead>
    <tr>
        <th>Id</th>
        <th>Nom</th>
        <th>Action</th>
    </tr>
</thead>
    <tbody>
        <?php foreach($categories as $category): ?>
<tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo $category['name']; ?></td>
            <td>
                <a href="category-edit.php?id=<?php echo $category['id']; ?>" class="btn btn-default">Editer</a>
                <a href="?delete=<?php echo $category['id']; ?>&<?php echo csrf() ?> " class="btn btn-error" onclick="return confirm('Etes vous sur-e ?')">Supprimer</a>
            </td>
</tr>


        <?php endforeach; ?>    
    </tbody>


</table>

<?php require '../partials/footer.php'; ?>