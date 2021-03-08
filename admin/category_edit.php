<?php
require '../library/requires.php';

if ( isset($_POST['name']) && ($_POST['slug']) ){
    checkCsrf();
    $slug = $_POST['slug'];
    if ( preg_match('/^[a-z\-0-9]+$/', $slug) ){
        $name = $db->quote($_POST['name']);
        $slug = $db->quote($_POST['slug']);
        if(isset($_GET['id'])){
            $id = $db->quote($_GET['id']);
            $db->query("UPDATE INTO categories SET name=$name, slug=$slug WHERE id=$id");
        }else{
            $db->query("INSERT INTO categories SET name=$name, slug=$slug");
        }
        setFlash('La catégorie a bien été ajoutée');
        header('Location:category.php');
        die();
    }
    else{
        setFlash('Le slug n\'est pas valide', 'danger');
    }
    
}

if(isset($_GET['id'])){
    $id = $db->quote($_GET['id']);
    $select = $db->query("SELECT * FROM categories WHERE id=$id");
    if($select->rowCount() == 0 ){
        setFlash("Il n'y a pas de categorie avec cet ID", 'danger');
        header('Location:category.php');
        die();
    }
    $_POST = $select->fetch();
}
require '../partials/admin_header.php';
?>

<h1>Editer une categorie</h1>

<form action="#" method="post">
    <div class="form-group">
    <label for="name">Nom de la categorie</label>
    <?php echo input('name'); ?>
    </div>

    <div class="form-group">
    <label for="slug">URL de la categorie</label>
    <?php echo input('slug'); ?>
    </div>
    <?php echo csrfInput(); ?>


    <button type="submit" class="btn btn-default"> Enregistrer </button>
</form>

<?php require '../partials/footer.php'; ?>