<?php include '../config.php'; ?>

<?php 
    $res = array();

    // Get the requested URI
    $requested_uri = $_SERVER['REQUEST_URI'];

    // Extract the parameter from the URI
    preg_match('/\/(\d+)\/?$/', $requested_uri, $matches);

    // Check if a parameter was found in the URI
    if(isset($matches[1])) {
        // Assuming 'filter' parameter is the ID you want to filter by
        $filter_id = $matches[1];

        // Prepare SQL statement with a WHERE clause to filter by ID
        $sql = "SELECT * FROM utenti WHERE id_utente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $filter_id); // Assuming ID is an integer
    } else {
        // If no parameter found in the URI, retrieve all records
        $sql = "SELECT * FROM utenti";
        $stmt = $conn->prepare($sql);
    }

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the results and store in $res array
    while ($row = $result->fetch_assoc()) {
        $res[] = $row;
    }

    // Output the JSON encoded result
    echo json_encode($res);
?>
