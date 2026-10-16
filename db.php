<?php

require_once __DIR__ . '/config.php';

$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$pdo->exec("
    CREATE TABLE IF NOT EXISTS activities (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        location TEXT NOT NULL,
        activity_date TEXT NOT NULL,
        description TEXT NOT NULL,
        image TEXT DEFAULT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )
");

$count = (int) $pdo->query('SELECT COUNT(*) FROM activities')->fetchColumn();

if ($count === 0) {
    $seedData = [
        [
            'title' => 'Cristo Rei Viewpoint',
            'location' => 'Dili, Timor-Leste',
            'activity_date' => '2026-07-10',
            'description' => 'A scenic morning walk and sightseeing activity around the Cristo Rei statue and coastline, attracting locals and visitors who enjoy the iconic view of the capital city.',
            'image' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'title' => 'Atauro Island Diving',
            'location' => 'Atauro Island',
            'activity_date' => '2026-08-02',
            'description' => 'A diving and snorkeling experience around coral reefs and beautiful sea life, popular with eco-tourists and adventure travelers in Timor-Leste.',
            'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'title' => 'Traditional Market Visit',
            'location' => 'Comoro Market, Dili',
            'activity_date' => '2026-06-15',
            'description' => 'Local food, crafts, and fresh produce are highlighted in this community activity, giving visitors a chance to experience daily life in Timor-Leste.',
            'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
        ],
        [
            'title' => 'Jaco Island Expedition',
            'location' => 'Jaco Island',
            'activity_date' => '2026-09-18',
            'description' => 'An adventurous island trip with beaches, ocean views, and unforgettable coastal landscapes, celebrated as one of the most beautiful natural destinations in the country.',
            'image' => 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80',
        ],
    ];

    $insertSql = 'INSERT INTO activities (title, location, activity_date, description, image, created_at)
                  VALUES (:title, :location, :activity_date, :description, :image, :created_at)';

    $insert = $pdo->prepare($insertSql);

    foreach ($seedData as $item) {
        $insert->execute([
            ':title' => $item['title'],
            ':location' => $item['location'],
            ':activity_date' => $item['activity_date'],
            ':description' => $item['description'],
            ':image' => $item['image'],
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
