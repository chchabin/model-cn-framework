<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"
            defer></script>
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Site CN</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?uc=voir&action=accueil">Modèle de Comptabilité Nationale CN</a>

    </div>
</nav>

<div class="container">
    <!--Body content-->
    <?= $message ?? ""; ?>
    <?php echo $contenu; ?>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript">

    const relations =<?php echo json_encode($relations) ?? null ?>;

    function listAcht() {
        let chaine = "";
        //const relationsData=JSON.parse(JSON.stringify(relations));
        const select = document.getElementById('operation').value;
        const acheteur = relations[select]['acheteur'];
        console.log(relations[select]['acheteur'])
        //console.log(relationsData);
        //alert (select);

        chaine = "<select class='form-select' aria-label='acheteur' name='acheteur' id='acheteur' onchange='listVdr(\"" + select + "\")'>"
        chaine += "<option selected>Choisir un acheteur</option>"
        acheteur.forEach(value => {
            // console.log(value))
            chaine += "<option>" + value + "</option>"
        })
        chaine += "</select>"
        document.getElementById("test").innerHTML = chaine;
    }

    function listVdr(select) {
        let chaine = "";
        const vendeur = relations[select]['vendeur'];
        chaine = "<select class='form-select' aria-label='vendeur' name='vendeur' id='vendeur'>"
        chaine += "<option selected>Choisir un vendeur</option>"
        vendeur.forEach(value => {
            // console.log(value))
            chaine += "<option>" + value + "</option>"
        })
        chaine += "</select>"
        document.getElementById("test2").innerHTML = chaine;
    }

</script>
</body>
</html>

