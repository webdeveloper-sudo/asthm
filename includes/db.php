<?php
$configFile = __DIR__ . '/config.php';

if (file_exists($configFile)) {
    // Production MySQL Database Connection
    require_once $configFile;
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        die("Production database connection failed: " . $e->getMessage());
    }
} else {
    // Local SQLite Database Fallback (for development environment)
    $dbFile = __DIR__ . '/../database.sqlite';
    $needsInit = !file_exists($dbFile);

    try {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        if ($needsInit) {
            // Initialize tables for local SQLite development
            $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE,
                password TEXT
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                excerpt TEXT,
                content TEXT NOT NULL,
                image_url TEXT,
                published_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS courses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                description TEXT,
                image_url TEXT
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS contact_inquiries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                email TEXT,
                phone TEXT,
                message TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS admissions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT,
                last_name TEXT,
                email TEXT,
                phone TEXT,
                course_id INTEGER,
                previous_education TEXT,
                parent_name TEXT DEFAULT '',
                state TEXT DEFAULT '',
                district TEXT DEFAULT '',
                board TEXT DEFAULT '',
                total_mark TEXT DEFAULT '',
                course_interest TEXT DEFAULT '',
                callback TEXT DEFAULT '',
                callback_from TEXT DEFAULT '',
                callback_till TEXT DEFAULT '',
                campus_date TEXT DEFAULT '',
                message TEXT DEFAULT '',
                is_read INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(course_id) REFERENCES courses(id)
            )");

            // Insert default admin (password: admin123)
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->exec("INSERT INTO admin_users (username, password) VALUES ('admin', '$hash')");

            // Insert sample blog posts
            $pdo->exec("INSERT INTO blog_posts (title, slug, excerpt, content, image_url) VALUES 
                ('Future of Hospitality Industry in 2024', 'future-of-hospitality-2024', 'Discover the upcoming trends in global hospitality, from AI-driven guest experiences to sustainable tourism.', '<p>The hospitality industry is evolving rapidly. In 2024, we expect to see a massive shift towards personalized guest experiences driven by artificial intelligence.</p><p>Sustainability is also no longer an option but a necessity. Hotels globally are adopting green practices to reduce carbon footprints.</p><p>At ACCHM, our curriculum is constantly updated to reflect these global shifts, ensuring our graduates are always industry-ready.</p>', '/assets/images/downloads/0I5A4162-2048x1365.jpg'),
                ('Why Choose a Career in Hotel Management?', 'why-choose-hotel-management', 'A degree in Hotel Management opens doors to a global career filled with exciting opportunities and immense growth.', '<p>Hotel Management is one of the most dynamic and fastest-growing sectors globally. From managing luxury resorts to curating culinary masterpieces, the opportunities are endless.</p><ul><li>Global Opportunities</li><li>Fast-tracked Career Growth</li><li>Creative Work Environment</li></ul><p>Join ACCHM to transform your passion for service into a highly rewarding profession.</p>', '/assets/images/downloads/0W7A6120-scaled.jpg')
            ");

            // Insert sample courses
            $pdo->exec("INSERT INTO courses (title, description, image_url) VALUES 
                ('Diploma in Hotel & Catering Administration', 'Diploma in Hotel & Catering Administration. Duration: 3 Years. Eligibility: 10th or equivalent. In today’s competitive world, secure a rewarding job after your higher education.', '/assets/images/downloads/0I5A4162-2048x1365.jpg'),
                ('B.Sc Hotel & Catering Administration', 'B.Sc Hotel & Catering Administration. Duration: 3 Years. Eligibility: 10+2 or equivalent. Hotel Management Degree courses are of high demand because of the numerous benefits which a student gets by pursuing them.', '/assets/images/downloads/0W7A6120-scaled.jpg'),
                ('MBA Hospitality Management', 'MBA Hospitality Management. Duration: 2 Years. Eligibility: 10+2+3. Offers in-depth knowledge and specialisation. Curated to enhance students’ career prospects and open-doors for higher-level-positions.', '/assets/images/downloads/0I5A4345-2048x1365.jpg')
            ");
        } else {
            // Safe migrations for existing SQLite database
            $columns = array_column($pdo->query("PRAGMA table_info(blog_posts)")->fetchAll(), 'name');
            if (!in_array('author', $columns)) {
                $pdo->exec("ALTER TABLE blog_posts ADD COLUMN author TEXT DEFAULT 'Admin'");
            }

            $columns = array_column($pdo->query("PRAGMA table_info(contact_inquiries)")->fetchAll(), 'name');
            if (!in_array('is_read', $columns)) {
                $pdo->exec("ALTER TABLE contact_inquiries ADD COLUMN is_read INTEGER DEFAULT 0");
            }

            $columns = array_column($pdo->query("PRAGMA table_info(admissions)")->fetchAll(), 'name');
            if (!in_array('is_read', $columns)) {
                $pdo->exec("ALTER TABLE admissions ADD COLUMN is_read INTEGER DEFAULT 0");
            }
            // New fields from the enhanced admission form
            $new_admission_cols = [
                'parent_name'    => "ALTER TABLE admissions ADD COLUMN parent_name TEXT DEFAULT ''",
                'state'          => "ALTER TABLE admissions ADD COLUMN state TEXT DEFAULT ''",
                'district'       => "ALTER TABLE admissions ADD COLUMN district TEXT DEFAULT ''",
                'board'          => "ALTER TABLE admissions ADD COLUMN board TEXT DEFAULT ''",
                'total_mark'     => "ALTER TABLE admissions ADD COLUMN total_mark TEXT DEFAULT ''",
                'course_interest'=> "ALTER TABLE admissions ADD COLUMN course_interest TEXT DEFAULT ''",
                'callback'       => "ALTER TABLE admissions ADD COLUMN callback TEXT DEFAULT ''",
                'callback_from'  => "ALTER TABLE admissions ADD COLUMN callback_from TEXT DEFAULT ''",
                'callback_till'  => "ALTER TABLE admissions ADD COLUMN callback_till TEXT DEFAULT ''",
                'campus_date'    => "ALTER TABLE admissions ADD COLUMN campus_date TEXT DEFAULT ''",
                'message'        => "ALTER TABLE admissions ADD COLUMN message TEXT DEFAULT ''",
            ];
            foreach ($new_admission_cols as $col => $sql) {
                if (!in_array($col, $columns)) {
                    $pdo->exec($sql);
                }
            }
        }
    } catch (PDOException $e) {
        die("Local SQLite database connection failed: " . $e->getMessage());
    }
}
?>
