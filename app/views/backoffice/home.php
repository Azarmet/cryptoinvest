<?php require_once RACINE . 'app/views/backoffice/headerback.php'; ?>

<section class="backoffice-home">
    <h1 class="dashboard-title">Back Office Home</h1>

    <div class="dashboard-cards">
        <a href="index.php?pageback=faq" class="dashboard-card">
            <h2>Manage FAQ</h2>
            <p>Add, modify or delete frequently asked questions.</p>
        </a>

        <a href="index.php?pageback=learn" class="dashboard-card">
            <h2>Learn Management</h2>
            <p>Organize your educational articles for users.</p>
        </a>

        <a href="index.php?pageback=market" class="dashboard-card">
            <h2>Market Management</h2>
            <p>Supervise real-time crypto data.</p>
        </a>

        <a href="index.php?pageback=users" class="dashboard-card">
            <h2>Users</h2>
            <p>View and manage member profiles.</p>
        </a>
    </div>
</section>

<?php require_once RACINE . 'app/views/backoffice/footerback.php'; ?>
