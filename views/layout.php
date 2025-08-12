<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Bell Work Check-in') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<!-- Offcanvas nav -->
<nav class="navbar navbar-light bg-light mb-3">
    <div class="container-fluid">
        <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#navDrawer" aria-controls="navDrawer">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand mb-0 h1">Bell Work Check-in</span>
        <div class="d-none d-md-flex gap-2 align-items-center">
            <a href="/attendance" class="btn btn-link">Attendance</a>
            <a href="/setup" class="btn btn-link">Setup</a>
            <?php if (!empty($_SESSION['user'])): ?>
                <span class="text-secondary small">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <a href="/logout" class="btn btn-link">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="offcanvas offcanvas-start" tabindex="-1" id="navDrawer" aria-labelledby="navDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="navDrawerLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a class="nav-link" href="/attendance">Attendance</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="/setup">Setup</a></li>
                <?php if (!empty($_SESSION['user'])): ?>
                    <li class="nav-item mb-2"><span class="nav-link disabled">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span></li>
                    <li class="nav-item mb-2"><a class="nav-link" href="/logout">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
</div>
<div class="container mt-4">
        <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-info"> <?= htmlspecialchars($_SESSION['flash']) ?> </div>
                <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <?= $content ?? '' ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/app.js"></script>
</body>
</html>
