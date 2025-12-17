<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع ملف CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        label {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        input[type="file"] {
            display: block;
            margin: 10px auto 20px auto;
            padding: 10px;
            font-size: 1rem;
            color: #333;
            border: 2px dashed #fff;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        button {
            background: #34c759;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #28a745;
        }

        p {
            margin-top: 10px;
            font-size: 1rem;
        }

        #file-content {
            margin-top: 20px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 10px;
            overflow-x: auto;
            max-width: 90%;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #6a11cb;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-size: 1rem;
        }

        td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 0.9rem;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h1>رفع ملف CSV</h1>

    @if (session('success'))
        <p style="color: #34c759; font-weight: bold;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">اختر الملف:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit">رفع</button>
    </form>

    <div id="file-content" style="display: none;">
        <h3>بعض البيانات الموجوده في الملف</h3>
        <table id="csv-table"></table>
    </div>

    <script>
        const fileInput = document.getElementById('file');
        const fileContent = document.getElementById('file-content');
        const csvTable = document.getElementById('csv-table');

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type === 'text/csv') {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const content = e.target.result;
                    displayCSV(content);
                };

                reader.readAsText(file);
            } else {
                alert('يرجى اختيار ملف CSV صالح.');
            }
        });

        function displayCSV(content) {
            const rows = content.split('\n').slice(0, 30); // إظهار أول 30 صف فقط
            csvTable.innerHTML = '';

            rows.forEach((row, index) => {
                const rowElement = document.createElement('tr');
                const columns = row.split(',').slice(0, 9);;

                columns.forEach(col => {
                    const cell = document.createElement(index === 0 ? 'th' : 'td');
                    cell.textContent = col.trim();
                    rowElement.appendChild(cell);
                });

                csvTable.appendChild(rowElement);
            });

            fileContent.style.display = 'block';
        }
    </script>
</body>

</html>
