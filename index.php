<?php
$questions = require('questions.php');
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Izveido vēstuli Valsts Vides Dienestam</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            background-color: #f5f5f5;
        }
        .form-section { 
            margin-bottom: 20px; 
            padding: 15px; 
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 5px;
        }
        .question-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px;
        }
        input[type="text"] { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            min-height: 100px;
        }
        .checkbox-group { 
            margin-left: 20px;
            line-height: 1.6;
        }
        .checkbox-item {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }
        .checkbox-item input[type="checkbox"] {
            margin-top: 4px;
            margin-right: 8px;
        }
        button { 
            padding: 10px 20px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover { 
            background-color: #45a049; 
        }
        .check-all-btn {
            margin-bottom: 10px;
            background-color: #2196F3;
        }
        .check-all-btn:hover {
            background-color: #1976D2;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        h2 {
            color: #34495e;
            margin-top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <script>
        function checkAll(category) {
            const checkboxes = document.querySelectorAll(`input[name="${category}[]"]`);
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }
    </script>
</head>
<body>
    <h1>Izveido vēstuli Valsts Vides Dienestam</h1>
    <form action="generate_pdf.php" method="post">
        <!-- Personal Information Section -->
        <div class="form-section">
            <h2>Nosūtītāja informācija</h2>
            <label for="name">Vārds:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="surname">Uzvārds:</label>
            <input type="text" id="surname" name="surname" required>
            
            <label for="address">Īpašuma nosaukums:</label>
            <input type="text" id="address" name="address">
            
            <label for="kadastra">Kadastra Nr:</label>
            <input type="text" id="kadastra" name="kadastra">

            <label for="kontakti">Kontakti:</label>
            <input type="text" id="kontakti" name="kontakti">

            <label for="custom_text">Personīgais iebildums:</label>
            <textarea id="custom_text" name="custom_text" placeholder="Ievadiet savu personīgo iebildumu šeit..."></textarea>
        </div>

        <!-- Dynamic Sections -->
        <?php foreach ($questions as $category => $data): ?>
        <div class="form-section">
            <h2>
                <?php echo htmlspecialchars($data['title']); ?>
                <button type="button" class="check-all-btn" onclick="checkAll('<?php echo $category; ?>')">Atzīmēt visus</button>
            </h2>
            <div class="checkbox-group">
                <?php foreach ($data['questions'] as $id => $question): ?>
                <div class="checkbox-item">
                    <input type="checkbox" name="<?php echo $category; ?>[]" value="<?php echo $id; ?>">
                    <label><?php echo htmlspecialchars($question); ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <button type="submit">Izveidot vēstuli</button>
    </form>
</body>
</html>