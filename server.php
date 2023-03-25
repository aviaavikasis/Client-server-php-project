
<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');

// Open a new file
$database = new SQLite3('database.sqlite');

// Create users table into the file
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    name TEXT,
    phone TEXT,
    created_at TEXT,
    updated_at TEXT
)";

$database->exec($sql);

// Insert lines with values to the users table in database.sqlite
$sql = "INSERT INTO users (name, phone, created_at, updated_at) VALUES
    ('John Smith', '0526785543', '18.03.23', '20.03.23'),
    ('Jane Doe', '0526785541', '18.02.22', '20.04.22'),
    ('Alice Cooper', '0546785543', '17.03.21', '20.03.23'),
    ('Bob Johnson', '0526782243', '3.03.23', '20.03.23'),
    ('Cathy Lee', '0506785543', '19.01.23', '20.02.23')";

$database->exec($sql);

if (isset($_GET['search_term'])) {
    $search_word = $_GET['search_term'];

    // Select lines with name or phone that contain the search word
    $stmt = $database->prepare('SELECT * FROM users WHERE name LIKE 
                            :search_word OR phone LIKE :search_word');
    
    // Bind the search word parameter to the prepared statement
    $stmt->bindValue(':search_word', '%' . $search_word . '%', SQLITE3_TEXT);
    
    $results = $stmt->execute();
    
    $results_arr = array();
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $results_arr[] = $row;
    }
    
    // Create an empty array to hold the JSON strings
    $json_array = array();
    
    // Loop through the results and convert each row to a JSON string
    foreach ($results_arr as $row) {
        $json_array[] = json_encode($row);
    }
    
    // Send the array of JSON strings back to the client
    header('Content-Type: application/json');
    echo json_encode($json_array);
}

else
{
    echo "Please enter a search term.";
}
       
$database->close();
// Delete the file
unlink('database.sqlite');
?>


