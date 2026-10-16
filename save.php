<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php?error=invalid_request');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$title = trim((string) ($_POST['title'] ?? ''));
$location = trim((string) ($_POST['location'] ?? ''));
$activityDate = trim((string) ($_POST['activity_date'] ?? ''));
$description = trim((string) ($_POST['description'] ?? ''));

if ($title === '' || $location === '' || $activityDate === '' || $description === '') {
    header('Location: admin.php?error=fill_all_fields');
    exit;
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['image']['tmp_name'])) {
    $file = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    if (!in_array($file['type'], $allowed, true)) {
        header('Location: admin.php?error=invalid_image_type');
        exit;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('activity_', true) . '.' . strtolower($ext);
    $target = UPLOAD_DIR . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        header('Location: admin.php?error=image_upload_failed');
        exit;
    }

    $imagePath = 'uploads/' . $filename;
}

if ($id > 0) {
    $existing = $pdo->prepare('SELECT image FROM activities WHERE id = :id LIMIT 1');
    $existing->execute([':id' => $id]);
    $existingItem = $existing->fetch();

    if ($existingItem && $imagePath !== null) {
        $oldFile = APP_ROOT . '/' . ltrim($existingItem['image'], '/');
        if (!empty($existingItem['image']) && !preg_match('#^https?://#i', $existingItem['image']) && is_file($oldFile)) {
            unlink($oldFile);
        }
    }

    $sql = 'UPDATE activities SET title = :title, location = :location, activity_date = :activity_date, description = :description' . ($imagePath !== null ? ', image = :image' : '') . ' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $params = [
        ':title' => $title,
        ':location' => $location,
        ':activity_date' => $activityDate,
        ':description' => $description,
        ':id' => $id,
    ];

    if ($imagePath !== null) {
        $params[':image'] = $imagePath;
    }

    $stmt->execute($params);
    header('Location: admin.php?success=updated');
    exit;
}

if ($imagePath === null) {
    header('Location: admin.php?error=photo_required');
    exit;
}

$stmt = $pdo->prepare('INSERT INTO activities (title, location, activity_date, description, image) VALUES (:title, :location, :activity_date, :description, :image)');
$stmt->execute([
    ':title' => $title,
    ':location' => $location,
    ':activity_date' => $activityDate,
    ':description' => $description,
    ':image' => $imagePath,
]);

header('Location: admin.php?success=created');
exit;
