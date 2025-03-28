<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<h1><?= isset($faq) ? 'Modifier' : 'Ajouter' ?> une question</h1>

<?php if (!empty($error)): ?>
    <div style="background-color: #ffd1d1; color: #900; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
        ⚠️ <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <label for="question">Question :</label><br>
    <input type="text" name="question" id="question" required
           value="<?= isset($faq['question']) ? htmlspecialchars($faq['question']) : '' ?>"><br><br>

    <label for="reponse">Réponse :</label><br>
    <textarea name="reponse" id="reponse" rows="6" cols="60" required><?= isset($faq['reponse']) ? htmlspecialchars($faq['reponse']) : '' ?></textarea><br><br>

    <input type="submit" value="<?= isset($faq) ? 'Mettre à jour' : 'Créer' ?> la FAQ">
</form>

<a href="index.php?pageback=faq">← Retour à la liste</a>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
