<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/layout/header.php';
$q = htmlspecialchars($_GET['user_query'] ?? '');
?>
<main class="container">
    <h1>Search Results</h1>
    <p>You searched for: <strong><?php echo $q; ?></strong></p>
    <p><em>Product search is coming in Task 10.</em></p>
    <p><a href="<?php echo BASE_URL; ?>/index.php">← Back to Home</a></p>
</main>
<?php require_once __DIR__ . '/layout/footer.php'; ?>