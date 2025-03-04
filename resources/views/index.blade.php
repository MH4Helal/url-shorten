<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        #urlForm, #decodeForm {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        input {
            margin-bottom: 10px;
            padding: 8px;
            width: 300px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        #result, #decodeResult {
            margin-top: 20px;
            text-align: center;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

    <h1>URL Shortener</h1>

    <div id="urlForm">
        <input type="url" id="longUrl" placeholder="Enter long URL">
        <button onclick="shortenUrl()">Shorten</button>
        <div id="result"></div>
    </div>

    <div id="decodeForm">
        <input type="text" id="shortUrl" placeholder="Enter short URL">
        <button onclick="decodeUrl()">Decode</button>
        <div id="decodeResult"></div>
    </div>

    <script>
        const apiUrl = '/encode';
        const decodeApiUrl = '/decode/';

        async function shortenUrl() {
            const longUrl = document.getElementById('longUrl').value;
            const resultDiv = document.getElementById('result');

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ url: longUrl })
                });

                const data = await response.json();

                if (response.ok) {
                    resultDiv.innerHTML = `<p>Short URL: <a href="${data.short_url}" target="_blank">${data.short_url}</a></p>`;
                } else {
                    resultDiv.innerHTML = `<p>Error: ${data.errors ? JSON.stringify(data.errors) : data.error}</p>`;
                }
            } catch (error) {
                resultDiv.innerHTML = `<p>Error: ${error.message}</p>`;
            }
        }

        async function decodeUrl() {
            const shortUrl = document.getElementById('shortUrl').value;
            const decodeResultDiv = document.getElementById('decodeResult');
            const shortCode = shortUrl.substring(shortUrl.lastIndexOf('/') + 1);

            try {
                const response = await fetch(decodeApiUrl + shortCode);
                const data = await response.json();

                if (response.ok) {
                    decodeResultDiv.innerHTML = `<p>Original URL: <a href="${data.original_url}" target="_blank">${data.original_url}</a></p>`;
                } else {
                    decodeResultDiv.innerHTML = `<p>Error: ${data.error}</p>`;
                }
            } catch (error) {
                decodeResultDiv.innerHTML = `<p>Error: ${error.message}</p>`;
            }
        }
    </script>
</body>
</html>