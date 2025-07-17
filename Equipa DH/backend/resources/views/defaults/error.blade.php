<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Error</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2; /* Light gray background */
            color: #333; /* Dark gray text */
            font-family: Arial, sans-serif;
        }
        #error-message {
            font-size: 2em;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="error-message">
        {{ $message }}
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                window.close();
            }, 5000); // Close the window after 3 seconds
        });
    </script>
</body>
</html>