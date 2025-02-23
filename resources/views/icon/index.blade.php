<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Иконки</title>
</head>
<body>
<h1>Управление иконками</h1>

<form id="iconForm">
    <label for="name">Название:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="svg">SVG:</label>
    <textarea id="svg" name="svg" required></textarea><br>

    <label for="keywords">Ключевые слова (через запятую):</label>
    <input type="text" id="keywords" name="keywords"><br>

    <button type="submit">Сохранить</button>
</form>

<script>
    document.getElementById('iconForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = {
            name: formData.get('name'),
            svg: formData.get('svg'),
            keywords: formData.get('keywords').split(',').map(k => k.trim()),
        };

        const response = await fetch('/api/icons', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (response.ok) {
            alert('Иконка сохранена!');
        } else {
            alert('Ошибка при сохранении иконки.');
        }
    });
</script>
</body>
</html>
