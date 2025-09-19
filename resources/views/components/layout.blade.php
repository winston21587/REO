<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
<header>
    <nav class="bg-red-700 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white font-bold text-xl">REO</a>
            <ul class="flex space-x-6">
                <li><a href="/" class="text-white hover:text-blue-200">Home</a></li>
                <li><a href="/login" class="text-white hover:text-blue-200">Login</a></li>
                <li><a href="/admin" class="text-white hover:text-blue-200">Admin</a></li>
            </ul>
        </div>
    </nav>
</header>

<main class="container">
    {{ $slot }}
</main>
</body>
</html>