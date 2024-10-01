<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Download Demo (PHP Version)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="bg-blue-600 p-6 text-white">
            <h1 class="text-2xl font-bold">API Download Demo</h1>
        </div>
        <form id="downloadForm" class="p-6 space-y-6">
            <div>
                <label for="provider" class="block text-sm font-medium text-gray-700 mb-1">Select Provider:</label>
                <select id="provider" name="provider" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose a provider</option>
                    <option value="lorempicsum">Lorem Picsum (Test)</option>
                    <option value="shutterstock">Shutterstock</option>
                    <option value="adobestock">Adobe Stock</option>
                    <option value="freepik">Freepik</option>
                    <!-- Add other providers as needed -->
                </select>
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Enter Code or Link:</label>
                <input type="text" id="code" name="code" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div id="optionsContainer" class="hidden">
                <label for="format" class="block text-sm font-medium text-gray-700 mb-1">Format (for video):</label>
                <select id="format" name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose a format</option>
                    <option value="HD">HD</option>
                    <option value="4K">4K</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                Download
            </button>
        </form>
        <div id="result" class="px-6 pb-6 text-sm"></div>
    </div>

    <script>
        const providerSelect = document.getElementById('provider');
        const optionsContainer = document.getElementById('optionsContainer');
        const resultDiv = document.getElementById('result');

        providerSelect.addEventListener('change', function() {
            const selectedProvider = this.value;
            if (selectedProvider.includes('video')) {
                optionsContainer.classList.remove('hidden');
            } else {
                optionsContainer.classList.add('hidden');
            }
        });

        document.getElementById('downloadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const provider = document.getElementById('provider').value;
            const codeOrLink = document.getElementById('code').value;
            const format = document.getElementById('format').value;
            resultDiv.innerHTML = '<p class="text-gray-600">Processing...</p>';

            let requestBody = {
                options: [{ name: '', value: '' }],
                providerName: provider
            };

            if (codeOrLink.startsWith('http')) {
                requestBody.link = codeOrLink;
            } else {
                requestBody.code = codeOrLink;
            }

            if (provider.includes('video') && format) {
                requestBody.options = [{ name: 'format', value: format }];
            }

            fetch('api-proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestBody)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.jobId) {
                    resultDiv.innerHTML = `<p class="text-gray-600">Job ID: ${data.jobId}<br>Status: Processing</p>`;
                    checkStatus(data.jobId);
                } else {
                    resultDiv.innerHTML = `<p class="text-red-600">Error: ${JSON.stringify(data)}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = `<p class="text-red-600">Error: ${error.message}<br>Please try again later.</p>`;
            });
        });

        function checkStatus(jobId) {
            fetch(`api-proxy.php?jobId=${jobId}`, {
                method: 'GET',
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.downloadLink) {
                    resultDiv.innerHTML = `<p class="text-green-600">Download completed: <a href="${data.downloadLink}" target="_blank" class="text-blue-600 hover:underline">Download Link</a></p>`;
                } else if (data.error) {
                    resultDiv.innerHTML = `<p class="text-red-600">Download failed: ${data.error}</p>`;
                } else if (data.progress !== undefined) {
                    resultDiv.innerHTML = `<p class="text-gray-600">Status: Processing - Progress: ${data.progress}%</p>`;
                    if (data.progress < 100) {
                        setTimeout(() => checkStatus(jobId), 5000);
                    }
                } else {
                    resultDiv.innerHTML = `<p class="text-gray-600">Status: Unknown - ${JSON.stringify(data)}</p>`;
                    setTimeout(() => checkStatus(jobId), 5000);
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
                resultDiv.innerHTML = `<p class="text-red-600">Error checking status: ${error.message}<br>Please try again later.</p>`;
            });
        }
    </script>
</body>
</html>