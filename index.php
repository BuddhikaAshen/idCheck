<?php

// Function to fetch student details based on umisid
function getStudentInfo($umisid)
{
    $url = 'https://students.nsbm.ac.lk/photo_upload/std_register_online_data.php?Command=search_data&umisid=' . $umisid;
    
    // Initialize cURL session
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);  // Set the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0',
        'Accept: application/json'
    ]);
    
    // Execute cURL request and get the response
    $response = curl_exec($ch);
    
    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    
    // Close cURL session
    curl_close($ch);
    
    // Decode JSON response
    $data = json_decode($response, true);
    
    // Check if data exists and return it
    if (isset($data['data_exist']) && $data['data_exist'] == 1) {
        return [
            'name' => $data['name'],
            'nic' => $data['nic'],
            'faculty' => $data['faculty'],
            'degree' => $data['degree']
        ];
    } else {
        return null;
    }
}

// Handle the form submission
if (isset($_POST['submit'])) {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $umisid = $_POST['id'];
        // Fetch student data
        $studentInfo = getStudentInfo($umisid);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .student-info p {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }

        .error-message {
            color: #d9534f;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Information</h2>
        <form action="index.php" method="post">
            <input type="text" placeholder="Enter NSBM Student Number" name="id" required><br>
            <input type="submit" name="submit" value="Submit">
        </form>

        <?php
        if (isset($studentInfo)) {
            if ($studentInfo) {
                echo "<div class='student-info'>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($studentInfo['name']) . "</p>";
                echo "<p><strong>NIC:</strong> " . htmlspecialchars($studentInfo['nic']) . "</p>";
                echo "<p><strong>Faculty:</strong> " . htmlspecialchars($studentInfo['faculty']) . "</p>";
                echo "<p><strong>Degree:</strong> " . htmlspecialchars($studentInfo['degree']) . "</p>";
                echo "</div>";
            } else {
                echo "<p class='error-message'>No data found for the given Student ID.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
