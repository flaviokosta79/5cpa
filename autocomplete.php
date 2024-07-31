<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autocomplete Example</title>
    <link rel="stylesheet" href="assets/jquery-ui/jquery-ui.min.css">
    <script src="assets/jquery-ui/external/jquery/jquery.js"></script>
    <script src="assets/jquery-ui/jquery-ui.min.js"></script>
</head>
<body>
    <div class="form-group">
        <label for="origem">Origem</label>
        <input type="text" id="origem" name="origem" class="form-control" required>
    </div>

    <script>
    $(document).ready(function() {
        var locations = ["1º BPM", "2º BPM", "3º BPM", "4º BPM", "5º BPM", "10º BPM"];
        $("#origem").autocomplete({
            source: locations
        });
    });
    </script>
</body>
</html>
