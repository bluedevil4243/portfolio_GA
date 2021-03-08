<?php
require '../library/requires.php';

/**
 * La sauvegarde
 */

if(isset($_POST['name']) && ($_POST['slug'])){    
    checkCsrf();
    $slug = $_POST['slug'];
    if(preg_match('/^[a-z\-0-9]+$/', $slug)){
        $name = db->quote($_POST['name']);
        $slug = db->quote($_POST['slug']);
        $category_id = db->quote($_POST['category_id']);
        $content = db->quote($_POST['content']);
        /**
         * SAUVEGARDE de la réalisation
         */

        if(isset($_GET['id'])){
            $id = $db->quote($_GET['id']);
            $db->query("UPDATE INTO works SET name=$name, slug=$slug, content=$content, category_id=$category_id WHERE id=$id");
        }else{
            $db->query("INSERT INTO works SET name=$name, slug=$slug", content=$content, category_id=$category_id );
            $_GET['id'] = $db->lastInsertId();
        }
        setFlash('La réalisation a bien été ajoutée');
        /**
         * ENVOI DES IMAGES
         */

        $work_id = $db->quote($_GET['id']);
        $files = $_FILES['images'];
        $images = array();
        foreach($files['tmp_name'] as $k => $v){
            $images[] = array(
                'name' => $files['name'][$k],
                'tmp_name' => $files['tmp_name'][$k]
            );
        }
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if(in_array($extension, array('jpg', 'png'))){
            $db->query("INSERT INTO images SET work_id = $work_id");
            $image_id = $db->lastInsertId();
            $image_name = $image_id . '.' . $extension;
            move_uploaded_file($image['tmp_name'], IMAGES . '/works/' . $imagename);
            $image_name = $db->quote(image_name);
            $db->query('UPDATE images SET name=$image_name WHERE id = $image_id')
        }


        header('Location:work.php');
        die();
    }
    else{
        setFlash('Le slug n\'est pas valide', 'danger');
    }
    
}
/**
 * on récupère une réalisation
 */
if(isset($_GET['id'])){
    $id = $db->quote($_GET['id']);
    $select = $db->query("SELECT * FROM works WHERE id=$id");
    if($select->rowCount() == 0 ){
        setFlash("Il n'y a pas de réalisation avec cet ID", 'danger');
        header('Location:work.php');
        die();
    }
    $_POST = $select->fetch();
}

/**
 * Suppression d'une image
 */
if(isset($_GET['delete_image'])){
    checkCsrf();
    $id = $db->quote($_GET['delete_image']);
    $select = $db->query("SELECT name, work_id FROM images WHERE id=$id");
    $images = $select->fetch();
    unlink(IMAGES . '/works/' . $image ['name']);
    $db->query("DELETE FROM images WHERE id=$id");
    setFlash("L'image a bien été supprimée");
    header('Location:work_edit.php?id=' . $image['work_id']);
    die();
}


/**
 * on récupère la liste des categories
 */

$select = $db->query('SELECT id, name FROM categories ORDER BY name ASC');
$categories = $select->fetchAll();
$categories_list = array();
foreach($categories as $category){
    $categories_list[$category['id']] = $category['name'];
}

/**
 * on récupère la liste des images
 */
if(isset($_GET['id'])){
    $work_id = $db->quote($_GET['id']);
    $select = $db->query("SELECT id, name FROM images WHERE work_id=$work_id");
    $images = $select->fetchAll();
}else{
    $images = array();
}  

require '../partials/admin_header.php';
?>

<h1>Editer une réalisation</h1>
<div class="row">                  
<div class="col-sm-8">

<form action="#" method="post" enctype="multipart/form-data">
    <div class="form-group">
    <label for="name">Nom de la réalisation</label>
    <?php echo input('name'); ?>
    </div>

    <div class="form-group">
    <label for="slug">URL de la réalisation</label>
    <?php echo input('slug'); ?>
    </div>

    <div class="form-group">
    <label for="content">Contenu de la réalisation</label>
    <?php echo textarea('content'); ?>
    </div>

<div class="form-group">
    <label for="category_id">Catégorie</label>
    <?php echo select('category_id', $categories_list); ?>
    </div>

    <?php echo csrfInput(); ?>
    
    <div class="form-group">
    <input type="file" name="images[]">
    <input type="file" name="images[]" class="hidden" id="duplicate">
    <p><a href="#" class="btn btn_success" id="duplicatebtn">Ajouter une image</a></p>
    </div>
    <p><button type="submit" class="btn btn-default"> Enregistrer </button></p>
</form>
</div>
<div class="col-sm-4">
<?php foreach ($images as $k => $image): ?>
<a href="?delete_image=<?php echo $image['id'] ?>&<?php echo csrf(); ?>" onclick="return confirm('sur ?');"><img src="<?php echo WEBROOT; ?>img/works/<?php echo $image['name']; ?>"width="100"></a>
<?php endofforeach ?>
</div>
</div>

<?php require '../partials/footer.php'; ?>