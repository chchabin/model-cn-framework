<?php if ($number > -1) : ?>
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
        <tr <?php if ($relations->{$operations['operation']} != 'R') echo('class="alert alert-warning"') ?>>
            <td><?= isset($v[0]) ?? 0 ?></td>
            <td><?= $operations['operation'] ?></td>
            <td><?= $operations['acheteur'] ?></td>
            <td><?= $operations['vendeur'] ?></td>
            <td><?= $operations['montant'] ?></td>
        </tr>
        </tbody>
    </table>
    <form action="index.php?uc=voir&action=deplacerPasApas" method="post">
        <input type="hidden" name="number" value="<?= $number ?? 0 ?>">
        <input type="submit" class="btn btn-info" name="reculer" value="Reculer">
        <input type="submit" class="btn btn-primary" name="avancer" value="Avancer">

    </form>
<?php endif; ?>
<?php foreach ($listAgents as $k => $v) : ?>
    <table class="tableaux">
        <thead>
        <tr>
            <th scope="col" colspan="4"><?= $v->getNom(); ?></th>
        </tr>
        <tr>
            <th scope="col" colspan="2">Actif</th>
            <th scope="col" colspan="2">Passif</th>
        </tr>
        </thead>
        <tbody>
        <?php //var_dump($v->getBilan());
        foreach ($v->getBilan() as $k => $w) : ?>
            <tr>
                <td class="key"><?= array_keys($w)[0] ?></td>
                <td <?php if (array_values($w)[0] != 0) echo('class="value"') ?>><?= array_values($w)[0] ?></td>
                <td class="key"><?= array_keys($w)[1] ?></td>
                <td <?php if (array_values($w)[1] != 0) echo('class="value"') ?>><?= array_values($w)[1] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>
<?php if (isset($CN)): ?>
    <table class="tableaux">
        <thead>
        <tr>
            <th scope="col" colspan="8"><?= $CN->getNom(); ?> TEE</th>
        </tr>
        <tr>
            <th scope="col" colspan="4">Actif</th>
            <th scope="col" colspan="4">Passif</th>

        </tr>
        <tr>
            <th scope="col"></th>
            <?php foreach ($CN->getActif_TEE() as $k => $v): ?>
                <th scope="col"><?= $k ?></th>
            <?php endforeach; ?>
            <th scope="col"></th>
            <?php foreach ($CN->getActif_TEE() as $k => $v): ?>
                <th scope="col"><?= $k ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($CN->getBilan_TEE() as $k => $v) : ?>
            <tr>
                <td class="key"><?= array_keys($v)[0] ?></td>
                <td <?php if (array_values($v)[0] != 0) echo('class="value"') ?>><?= array_values($v)[0] ?></td>
                <td <?php if (array_values($v)[1] != 0) echo('class="value"') ?>><?= array_values($v)[1] ?></td>
                <td <?php if (array_values($v)[2] != 0) echo('class="value"') ?>><?= array_values($v)[2] ?></td>
                <td class="key"><?= array_keys($v)[0] ?></td>
                <td <?php if (array_values($v)[3] != 0) echo('class="value"') ?>><?= array_values($v)[3] ?></td>
                <td <?php if (array_values($v)[4] != 0) echo('class="value"') ?>><?= array_values($v)[4] ?></td>
                <td <?php if (array_values($v)[5] != 0) echo('class="value"') ?>><?= array_values($v)[5] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <table class="tableaux">
        <thead>
        <tr>
            <th scope="col" colspan="8"><?= $CN->getNom() ?> TOF</th>
        </tr>
        <tr>
            <th scope="col" colspan="4">Actif</th>
            <th scope="col" colspan="4">Passif</th>
        </tr>
        <tr>
            <?php foreach ($CN->getBilan_TOF() as $k => $v) : ?>
                <th scope="col"><?= array_keys($v)[0] ?></th>
            <?php endforeach; ?>
            <?php foreach ($CN->getBilan_TOF() as $k => $v) : ?>
                <th scope="col"><?= array_keys($v)[0] ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php foreach ($CN->getBilan_TOF() as $k => $v) : ?>
                <td <?php if (array_values($v)[0] != 0) echo('class="value"') ?>><?= array_values($v)[0] ?></td>
            <?php endforeach; ?>
            <?php foreach ($CN->getBilan_TOF() as $k => $v) : ?>
                <td <?php if (array_values($v)[1] != 0) echo('class="value"') ?>><?= array_values($v)[1] ?></td>
            <?php endforeach; ?>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
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
    <?php foreach ($lesOperations as $k => $v) : ?>
        <input type="hidden" name="check[]" value="<?=  isset($v[0])??0 ?>">
        <input type="hidden" name="operation[]" value="<?=  $v['operation'] ?>">
        <input type="hidden" name="acheteur[]" value="<?=  $v['acheteur'] ?>">
        <input type="hidden" name="vendeur[]" value="<?=  $v['vendeur'] ?>">
        <input type="hidden" name="montant[]" value="<?=  $v['montant'] ?>">
        <tr <?php if($lesRelations->{$v['operation']}!='R') echo('class="alert alert-warning"')  ?>>
            <td><?= isset($v[0])??0 ?></td>
            <td><?= $v['operation'] ?></td>
            <td><?= $v['acheteur'] ?></td>
            <td><?= $v['vendeur'] ?></td>
            <td><?= $v['montant'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
