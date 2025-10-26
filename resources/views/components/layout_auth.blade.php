<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REO</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/reoc-nobg.png') }}" >
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style>
  /* Additional custom styles for better mobile experience */
  @media (max-width: 640px) {
    .mobile-padding {
      padding: 1.5rem 1rem;
    }
    
    .mobile-text-lg {
      font-size: 1.5rem;
    }
    
    .mobile-space-y-4 > * + * {
      margin-top: 1rem;
    }
  }
  
  /* Custom wider container */
  .signup-container {
    max-width: 500px;
    width: 100%;
  }
  
  @media (min-width: 768px) {
    .signup-container {
      max-width: 600px;
    }
  }
  
  @media (min-width: 1024px) {
    .signup-container {
      max-width: 700px;
    }


    /* Remove arrows from number input */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* For Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}
  }
</style>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#ea2a2a",
        "background-light": "#f8f6f6",
        "background-dark": "#211111",
      },
      fontFamily: {
        "display": ["Inter"]
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "full": "9999px"
      },
    },
  },
}
</script>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200 min-h-screen flex flex-col ">

<main class="flex-grow flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12">
    {{ $slot }}
</main>

</body>
</html>












{{-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign-Up/Login Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="./images/reoclogo1.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
{{ $slot }}
</body>
</html> --}}
