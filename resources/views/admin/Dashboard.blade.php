<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-white text-gray-700 md:inline hidden text-sm hover:opacity-90 active:scale-95 transition-all w-40 h-11 rounded-full">
                Logout
            </button>
        </form>
<x-admin_layout>
</x-admin_layout>

</body>
</html> 