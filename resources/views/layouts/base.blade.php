<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Lab') · E-Lab</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'] },
                colors: { brand: { 50:'#eef6ff',100:'#d9eaff',500:'#2f6fed',600:'#1f57d1',700:'#1b46a8',900:'#13285c' } }
            } }
        }
    </script>
    <style>[x-cloak]{display:none}</style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    @yield('body')
</body>
</html>
