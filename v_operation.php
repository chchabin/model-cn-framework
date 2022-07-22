<?php if (isset($operations)) :  //var_dump( $operations); ?>
<form method="post" action="index.php?uc=voir&action=saveOperations">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">check</th>
            <th scope="col">Operation</th>
            <th scope="col">Acheteur</th>
            <th scope="col">Vendeur</th>
            <th scope="col">Montant</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($operations as $k => $v) : ?>
            <input type="hidden" name="check[]" value="<?=  isset($v[0])??0 ?>">
            <input type="hidden" name="operation[]" value="<?=  $v['operation'] ?>">
            <input type="hidden" name="acheteur[]" value="<?=  $v['acheteur'] ?>">
            <input type="hidden" name="vendeur[]" value="<?=  $v['vendeur'] ?>">
            <input type="hidden" name="montant[]" value="<?=  $v['montant'] ?>">
            <tr <?php if($relations->{$v['operation']}->type!='R') echo('class="alert alert-warning"')  ?>>
                <td><?= isset($v[0])??0 ?></td>
                <td><?= $v['operation'] ?></td>
                <td><?= $v['acheteur'] ?></td>
                <td><?= $v['vendeur'] ?></td>
                <td><?= $v['montant'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <label for="titre" class="form-label">Titre</label>
    <input type="text" name="titre" id="titre">
    <input type="text" name="commentaire" id="commentaire">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php endif ?>
