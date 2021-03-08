<?php
require '../library/requires.php';
require '../partials/admin_header.php';


/**
 * SUPPRESSION
 */
if(isset($_GET['delete'])){
    checkCsrf();
    $id = $db->quote($_GET['delete']);
    $db->query("DELETE FROM works WHERE id=$id");
    setFlash('la categorie a bien été supprimée');
    header('Location:work.php');
    die();
}

/**
 * works
 */
$select = $db->query('SELECT id, name, slug FROM works');
$works = $select->fetchAll();

?>

<h1>Mes réalisations</h1>

<p><a href="work_edit.php" class="btn btn-success">Ajouter une nouvelle réalisation</a></p>

<table class="table table-striped">
<thead>
    <tr>
        <th>Id</th>
        <th>Nom</th>
        <th>Action</th>
    </tr>
</thead>
    <tbody>
        <?php foreach($works as $category): ?>
<tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo $category['name']; ?></td>
            <td>
                <a href="work-edit.php?id=<?php echo $category['id']; ?>" class="btn btn-default">Editer</a>
                <a href="?delete=<?php echo $category['id']; ?>&<?php echo csrf() ?> " class="btn btn-error" onclick="return confirm('Etes vous sur-e ?')">Supprimer</a>
            </td>
</tr>


        <?php endforeach; ?>    
    </tbody>


</table>

<?php require '../partials/footer.php'; ?>