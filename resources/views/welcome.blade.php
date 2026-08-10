<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prashnpatra — Previous Year Question Papers Portal</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            950: '#1d2a44',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        body {
            background-color: #0b0f19;
            background-image: radial-gradient(circle at 50% -20%, #1e293b, #0f172a, #090d16);
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen antialiased flex flex-col">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Prashnpatra</span>
                <span class="bg-blue-500/10 text-blue-400 text-xs px-2.5 py-0.5 rounded-full border border-blue-500/20 font-medium">Web Portal</span>
            </div>
            <nav class="flex items-center space-x-6">
                <a href="#browse" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Browse Papers</a>
                <a href="#monetization" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Premium Plans</a>
                <a href="/admin" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:shadow-lg hover:shadow-blue-500/20 transition-all">Admin Dashboard</a>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-6 py-12 flex-1 w-full space-y-16">
        
        <!-- Hero Section -->
        <section class="text-center max-w-3xl mx-auto space-y-6">
            <h1 class="text-5xl font-extrabold tracking-tight leading-none bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">
                Access All Previous Year Question Papers in One Place
            </h1>
            <p class="text-lg text-slate-400 leading-relaxed">
                Download verified high-quality boards and levels exam papers. Free tiers include up to 3 years. Premium unlock gets you instant unlimited access across all subjects.
            </p>
        </section>

        <!-- Stats Counter Dashboard -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass p-6 rounded-2xl flex flex-col space-y-2">
                <span class="text-slate-400 text-sm font-medium">Total Boards & Councils</span>
                <span class="text-3xl font-bold text-white">{{ $stats['boards'] }}</span>
            </div>
            <div class="glass p-6 rounded-2xl flex flex-col space-y-2">
                <span class="text-slate-400 text-sm font-medium">Verified Subjects</span>
                <span class="text-3xl font-bold text-white">{{ $stats['subjects'] }}</span>
            </div>
            <div class="glass p-6 rounded-2xl flex flex-col space-y-2">
                <span class="text-slate-400 text-sm font-medium">Question Papers Available</span>
                <span class="text-3xl font-bold text-white">{{ $stats['papers'] }}</span>
            </div>
            <div class="glass p-6 rounded-2xl flex flex-col space-y-2">
                <span class="text-slate-400 text-sm font-medium">Student Paper Requests</span>
                <span class="text-3xl font-bold text-white">{{ $stats['requests'] }}</span>
            </div>
        </section>

        <!-- Main Workspace Grid -->
        <section id="browse" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Column 1 & 2: Interactive Subjects list & Papers Grid -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass p-8 rounded-2xl space-y-6">
                    <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                        <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                        <span>Select Subject & Board</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($subjects as $subject)
                            <div class="p-4 rounded-xl border border-slate-800 bg-slate-900/50 hover:border-slate-700 transition-all flex justify-between items-center group">
                                <div class="space-y-1">
                                    <h3 class="font-semibold text-white group-hover:text-blue-400 transition-colors">{{ $subject->name }}</h3>
                                    <p class="text-xs text-slate-500">Board: {{ $subject->board->name }} | Code: {{ $subject->code ?? 'N/A' }}</p>
                                </div>
                                <span class="bg-slate-800 text-slate-300 text-xs px-2.5 py-1 rounded-md font-semibold">{{ $subject->papers_count }} Papers</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Column 3: Interactive Submit/Request Form Widget -->
            <div class="space-y-8">
                <!-- Request Form Card -->
                <div class="glass p-8 rounded-2xl space-y-6">
                    <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                        <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                        <span>Request Missing Paper</span>
                    </h2>
                    <form action="/requests/store" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Subject</label>
                            <select name="subject_id" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->board->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Year</label>
                                <input type="number" name="year" min="2000" max="2027" placeholder="e.g. 2024" required class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Set (Optional)</label>
                                <input type="text" name="paper_set" placeholder="e.g. A" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-3.5 rounded-xl text-sm hover:shadow-lg hover:shadow-indigo-500/20 transition-all">Submit Request</button>
                    </form>
                </div>

                <!-- Crowdsourced Upload Form Card -->
                <div class="glass p-8 rounded-2xl space-y-6">
                    <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                        <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                        <span>Upload & Contribute</span>
                    </h2>
                    <form action="/submissions/store" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Subject</label>
                            <select name="subject_id" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-emerald-500">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->board->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Year</label>
                                <input type="number" name="year" min="2000" max="2027" placeholder="e.g. 2024" required class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Set</label>
                                <input type="text" name="paper_set" placeholder="e.g. A" class="w-full bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">PDF File</label>
                            <input type="file" name="file" accept="application/pdf" required class="w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20">
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 rounded-xl text-sm hover:shadow-lg hover:shadow-emerald-500/20 transition-all">Upload Paper</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Premium Paywall Card View -->
        <section id="monetization" class="space-y-8">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <h2 class="text-3xl font-extrabold text-white">Upgrade to Premium Packages</h2>
                <p class="text-slate-400">Lock down premium access. Unlimited class switches or dedicated single class subscriptions.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Tier 1 Pricing -->
                <div class="glass p-8 rounded-3xl flex flex-col justify-between border-blue-500/20 bg-gradient-to-b from-slate-900/60 to-slate-950/80 hover:border-blue-500/40 transition-all duration-300">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-400 text-xs font-semibold uppercase tracking-widest bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">Single Class Access</span>
                            <span class="text-slate-500 text-xs">Best value</span>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-5xl font-black text-white">₹99</span>
                            <span class="text-slate-400">/ 1 year</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-slate-300">
                            <li class="flex items-center space-x-3">
                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                                <span>Access all years for selected Class and Stream</span>
                            </li>
                            <li class="flex items-center space-x-3 text-slate-500">
                                <span class="w-1.5 h-1.5 bg-slate-700 rounded-full"></span>
                                <span>No unlimited class changes</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tier 2 Pricing -->
                <div class="glass p-8 rounded-3xl flex flex-col justify-between border-indigo-500/20 bg-gradient-to-b from-slate-900/60 to-slate-950/80 hover:border-indigo-500/40 transition-all duration-300">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-400 text-xs font-semibold uppercase tracking-widest bg-indigo-500/10 px-3 py-1 rounded-full border border-indigo-500/20">Unlimited Access Pro</span>
                            <span class="text-amber-400 text-xs font-bold uppercase tracking-wider bg-amber-500/10 px-3 py-1 rounded-full">Recommended</span>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-5xl font-black text-white">₹149</span>
                            <span class="text-slate-400">/ 2 years</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-slate-300">
                            <li class="flex items-center space-x-3">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                                <span>Access unlimited years for any Class & Stream</span>
                            </li>
                            <li class="flex items-center space-x-3">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                                <span>Unlimited class switches & switches tracking</span>
                            </li>
                            <li class="flex items-center space-x-3 text-emerald-400 font-semibold">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                <span>Get 20% off when using friend's referral link</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="glass border-t border-slate-900 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between text-slate-500 text-xs gap-4">
            <span>© {{ date('Y') }} Prashnpatra App. All rights reserved. Powered by Laravel 11 & Filament v3.</span>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-slate-300">Security Specs</a>
                <a href="#" class="hover:text-slate-300">Free Tier Usage Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
