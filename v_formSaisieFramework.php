<form action="index.php?uc=voir&action=loadParam" method="post" >
    <div class="mb-3">

        <label for="mtE1" class="form-label">Montant E1</label>
        <input type="text" class="form-control" id="mtE1" name="mtE1" placeholder="60">
        <label for="mtE2" class="form-label">Montant E2 (correspond au profit investi)</label>
        <input type="text" class="form-control" id="mtE2" name="mtE2" placeholder="25">
        <label for="txProfit" class="form-label">Montant taux de profit</label>
        <input type="text" class="form-control" id="txProfit" name="txProfit" placeholder="0">
        <label for="mtInvest" class="form-label">Montant de l'amortissement</label>
        <input type="text" class="form-control" id="mtInvest" name="mtInvest" placeholder="5">
        <label for="xport" class="form-label">Montant des Exportations</label>
        <input type="text" class="form-control" id="xport" name="xport" placeholder="6">
        <label for="mport" class="form-label">Montant des Importations</label>
        <input type="text" class="form-control" id="mport" name="mport" placeholder="7">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="mInter" name="mInter">
            <label class="form-check-label" for="mInter">
                La monnaie du pays est monnaie internationale
            </label>
        </div>
    </div>
    <input class="btn btn-primary" type="submit" name="envoyer" value="Envoyer">
    <input class="btn btn-warning" type="submit" name="raz" value="RAZ">
    <input class="btn btn-primary" type="submit" name="pasApas" value="Pas à Pas">

</form>


