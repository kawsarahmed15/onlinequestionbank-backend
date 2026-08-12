<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prashnpatra — Previous Year Question Papers Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['IBM Plex Mono', 'monospace'],
                    },
                    colors: {
                        ink: '#14171F',
                        ink2: '#2A2E3A',
                        paper: '#FFFFFF',
                        canvas: '#F6F7F9',
                        line: '#E8EAED',
                        line2: '#D7DBE1',
                        slate: '#5B6472',
                        slate2: '#8A8F9C',
                        brandRed: '#D7263D',
                        brandRedSoft: '#FCE8EA',
                        brandGreen: '#1C7C4C',
                        brandGreenSoft: '#E4F3EA',
                        brandAmber: '#B8860B',
                        brandAmberSoft: '#FBF1DC',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #F6F7F9;
            color: #14171F;
        }
        .border-hairline {
            border-width: 1.5px;
            border-color: #E8EAED;
        }
        
        /* Slide & Minimize transitions */
        .panel-transition {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-collapsed {
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            opacity: 0 !important;
            margin-right: 0 !important;
            padding-right: 0 !important;
            transform: translateX(-20px);
            overflow: hidden;
            pointer-events: none;
        }

        .subjects-minimized {
            width: 100% !important;
            max-width: 290px !important;
        }

        /* Styling compact subjects when minimized */
        .subjects-minimized .subject-row {
            padding: 0.75rem 1rem !important;
            border-radius: 12px !important;
        }
        .subjects-minimized .subject-row h3 {
            font-size: 0.875rem !important; /* text-sm */
        }
        .subjects-minimized .subject-row svg {
            display: none !important;
        }
        .subjects-minimized .subject-row .flex.items-center.space-x-2 {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b-2 border-line">
        <div class="max-w-[1400px] mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="/" class="text-2xl font-bold font-display tracking-tight text-ink hover:opacity-80 transition-all">
                    Prashnpatra
                </a>
                <span class="bg-canvas text-ink text-xs font-mono px-3 py-1 rounded border border-line font-bold">
                    WEB PORTAL
                </span>
            </div>

            <!-- Focus badge indicator -->
            <div class="flex items-center space-x-3">
                @if($focusLevel && $focusBoard)
                    <form action="/onboarding/clear" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-canvas hover:bg-line border border-line rounded px-3 py-1 text-[10px] font-bold font-mono text-slate transition-all flex items-center space-x-1">
                            <span>FOCUS SET</span>
                            <span class="w-1.5 h-1.5 bg-brandGreen rounded-full"></span>
                        </button>
                    </form>
                @endif
                <div class="w-8 h-8 rounded-full border border-line2 bg-canvas flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Dynamic Layout Container -->
    <main class="max-w-[1400px] w-full mx-auto px-6 py-8 flex-grow">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-2 border-brandGreen/20 rounded-2xl flex items-center space-x-3 text-brandGreen max-w-4xl mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-semibold font-sans">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-2 border-brandRed/20 rounded-2xl flex items-center space-x-3 text-brandRed max-w-4xl mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-semibold font-sans">{{ session('error') }}</span>
            </div>
        @endif

        <!-- ──────────────────────────────────────────── -->
        <!-- ONBOARDING FLOW (No active focus set) -->
        <!-- ──────────────────────────────────────────── -->
        @if(!$focusLevel || !$focusBoard)
            <section class="max-w-2xl mx-auto space-y-8 bg-white border-2 border-line rounded-3xl p-8 shadow-sm">
                <!-- Step progress track -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-mono font-bold text-slate uppercase">
                        <span>Progress</span>
                        <span id="step-percentage">0% Complete</span>
                    </div>
                    <div class="w-full h-1.5 bg-line rounded-full overflow-hidden">
                        <div id="step-progress-bar" class="h-full bg-ink transition-all duration-300" style="width: 0%;"></div>
                    </div>
                </div>

                <!-- Form container -->
                <form action="/onboarding/save" method="POST" class="space-y-6" id="onboarding-form">
                    @csrf
                    
                    <!-- Hidden inputs to store selections -->
                    <input type="hidden" name="level_id" id="hidden-level-id" required>
                    <input type="hidden" name="stream_id" id="hidden-stream-id">
                    <input type="hidden" name="board_id" id="hidden-board-id" required>
                    <input type="hidden" name="semester_id" id="hidden-semester-id">

                    <!-- STEP 1: Academic Level -->
                    <div class="onboarding-step space-y-6" id="step-level">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-extrabold font-display tracking-tight text-ink">Select Academic Level</h2>
                            <p class="text-sm text-slate">Choose your current academic grade or level of study.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($levels as $level)
                                <div class="option-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex items-center justify-between transition-all" data-value="{{ $level->id }}" onclick="selectLevel('{{ $level->id }}')">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-canvas border border-line rounded-full w-10 h-10 flex items-center justify-center text-xs font-mono font-bold text-ink">
                                            L{{ $level->sort_order }}
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold font-display text-ink">{{ $level->name }}</h3>
                                            <p class="text-xs text-slate mt-0.5">{{ $level->description ?: 'Syllabus papers coverage' }}</p>
                                        </div>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- STEP 2: Stream / Course -->
                    <div class="onboarding-step space-y-6 hidden" id="step-stream">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-extrabold font-display tracking-tight text-ink" id="stream-step-title">Select Stream</h2>
                            <p class="text-sm text-slate" id="stream-step-desc">Choose your specific course or stream.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3" id="stream-options-list">
                            <!-- Populated dynamically via JS -->
                        </div>
                        <button type="button" onclick="goBack()" class="border border-line hover:bg-canvas text-ink font-mono font-bold text-xs px-6 py-2.5 rounded-xl transition-all">BACK</button>
                    </div>

                    <!-- STEP 3: Board / University -->
                    <div class="onboarding-step space-y-6 hidden" id="step-board">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-extrabold font-display tracking-tight text-ink" id="board-step-title">Choose Board</h2>
                            <p class="text-sm text-slate" id="board-step-desc">Select the board or university you study under.</p>
                        </div>
                        <!-- Search input -->
                        <div class="bg-white border-2 border-line rounded-2xl p-3 flex items-center space-x-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" id="board-search-input" placeholder="Search by name..." class="w-full bg-transparent text-sm text-ink placeholder-slate2 focus:outline-none font-medium">
                        </div>
                        <div class="grid grid-cols-1 gap-3 max-h-60 overflow-y-auto pr-1" id="board-options-list">
                            <!-- Populated dynamically via JS -->
                        </div>
                        <button type="button" onclick="goBack()" class="border border-line hover:bg-canvas text-ink font-mono font-bold text-xs px-6 py-2.5 rounded-xl transition-all">BACK</button>
                    </div>

                    <!-- STEP 4: Semester -->
                    <div class="onboarding-step space-y-6 hidden" id="step-semester">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-extrabold font-display tracking-tight text-ink" id="semester-step-title">Select Semester</h2>
                            <p class="text-sm text-slate" id="semester-step-desc">Choose your current semester of study.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3" id="semester-options-list">
                            <!-- Populated dynamically via JS -->
                        </div>
                        <button type="button" onclick="goBack()" class="border border-line hover:bg-canvas text-ink font-mono font-bold text-xs px-6 py-2.5 rounded-xl transition-all">BACK</button>
                    </div>

                    <!-- STEP 5: Subject Selection -->
                    <div class="onboarding-step space-y-6 hidden" id="step-subjects">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-extrabold font-display tracking-tight text-ink">Select Subjects</h2>
                            <p class="text-sm text-slate">Choose the subjects you want to pin on your dashboard.</p>
                        </div>
                        
                        <!-- Subjects selection list/grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="subjects-selection-list">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <div class="flex items-center space-x-3 pt-4">
                            <button type="button" onclick="goBack()" class="border border-line hover:bg-canvas text-ink font-mono font-bold text-xs px-6 py-3.5 rounded-xl transition-all">BACK</button>
                            <button type="submit" class="flex-grow bg-ink text-white font-mono font-bold tracking-widest text-xs py-3.5 rounded-xl hover:bg-ink2 transition-all">
                                LOCK FOCUS & CONTINUE
                            </button>
                        </div>
                    </div>
                </form>
            </section>

        <!-- ──────────────────────────────────────────── -->
        <!-- CORE MULTI-PANEL SYSTEM (Matching Flutter Sidebar) -->
        <!-- ──────────────────────────────────────────── -->
        @else
            @php
                $layoutCollapsed = ($selectedSubject && $currentView === 'dashboard') ? true : false;
            @endphp
            <div class="flex flex-col lg:flex-row gap-8 items-start w-full">
                
                <!-- Left Sidebar Navigation Widget -->
                <div id="sidebar-panel" class="w-full lg:w-72 shrink-0 space-y-6 panel-transition {{ $layoutCollapsed ? 'sidebar-collapsed' : '' }}">
                    <div class="bg-white border-2 border-line rounded-3xl p-5 shadow-sm space-y-6">
                        <!-- User Card -->
                        <div class="pb-4 border-b border-line flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-ink flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    @if(is_null($webUser->mobile_number))
                                        <h4 class="font-bold font-display text-ink text-sm">Guest Student</h4>
                                        <span class="text-[9px] font-bold font-mono text-slate tracking-widest uppercase">WEB SESSION</span>
                                    @else
                                        <h4 class="font-bold font-display text-ink text-sm">{{ $webUser->name }}</h4>
                                        <span class="text-[9px] font-bold font-mono text-slate tracking-widest uppercase">{{ $webUser->mobile_number }}</span>
                                    @endif
                                </div>
                            </div>
                            @if(!is_null($webUser->mobile_number))
                                <button onclick="logoutRealUser()" class="text-[10px] font-bold font-mono text-brandRed hover:underline">
                                    LOGOUT
                                </button>
                            @endif
                        </div>

                        <!-- Menu Groups -->
                        <div class="space-y-5">
                            <!-- BROWSE -->
                            <div class="space-y-2">
                                <span class="text-[9px] font-bold font-mono tracking-widest text-slate2 uppercase block">BROWSE</span>
                                <div class="space-y-1">
                                    <!-- CBSE X -->
                                    <form action="/onboarding/quick-browse" method="POST">
                                        @csrf
                                        <input type="hidden" name="level_id" value="22222222-2222-2222-2222-222222222222">
                                        <input type="hidden" name="board_id" value="55555555-5555-5555-5555-555555555555">
                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all hover:bg-canvas font-sans text-ink flex items-center space-x-2">
                                            <span class="w-2 h-2 rounded-full bg-slate2"></span>
                                            <span>Class X Boards</span>
                                        </button>
                                    </form>
                                    <!-- CBSE XII -->
                                    <form action="/onboarding/quick-browse" method="POST">
                                        @csrf
                                        <input type="hidden" name="level_id" value="33333333-3333-3333-3333-333333333333">
                                        <input type="hidden" name="stream_id" value="77777777-7777-7777-7777-777777777777">
                                        <input type="hidden" name="board_id" value="55555555-5555-5555-5555-555555555555">
                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all hover:bg-canvas font-sans text-ink flex items-center space-x-2">
                                            <span class="w-2 h-2 rounded-full bg-slate2"></span>
                                            <span>Class XII Boards</span>
                                        </button>
                                    </form>
                                    <!-- Degree CBSE -->
                                    <form action="/onboarding/quick-browse" method="POST">
                                        @csrf
                                        <input type="hidden" name="level_id" value="44444444-4444-4444-4444-444444444444">
                                        <input type="hidden" name="board_id" value="55555555-5555-5555-5555-555555555555">
                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all hover:bg-canvas font-sans text-ink flex items-center space-x-2">
                                            <span class="w-2 h-2 rounded-full bg-slate2"></span>
                                            <span>Degree & Universities</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- LIBRARY (YOU) -->
                            <div class="space-y-2">
                                <span class="text-[9px] font-bold font-mono tracking-widest text-slate2 uppercase block">YOU</span>
                                <div class="space-y-1">
                                    <a href="/?view=dashboard" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all block hover:bg-canvas font-sans text-ink {{ $currentView === 'dashboard' ? 'bg-canvas text-ink font-bold' : '' }}">
                                        📚 Home Dashboard
                                    </a>
                                    <a href="/?view=saved" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all block hover:bg-canvas font-sans text-ink {{ $currentView === 'saved' ? 'bg-canvas text-ink font-bold' : '' }}">
                                        🔖 Saved Papers
                                    </a>
                                    <a href="/?view=uploads" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all block hover:bg-canvas font-sans text-ink {{ $currentView === 'uploads' ? 'bg-canvas text-ink font-bold' : '' }}">
                                        📤 My Uploads
                                    </a>
                                    <a href="/?view=requests" class="w-full text-left px-3 py-2 text-xs font-semibold rounded-xl transition-all block hover:bg-canvas font-sans text-ink {{ $currentView === 'requests' ? 'bg-canvas text-ink font-bold' : '' }}">
                                        📝 My Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right content viewport -->
                <div id="content-panel" class="flex-grow flex flex-col md:flex-row gap-8 w-full panel-transition animate-fade-in">

                    <!-- VIEW 1: HOME DASHBOARD -->
                    @if($currentView === 'dashboard')
                        <!-- Left Subjects Section -->
                        <div id="subjects-panel" class="w-full md:w-2/3 shrink-0 space-y-6 panel-transition {{ $layoutCollapsed ? 'subjects-minimized' : '' }}">
                                <!-- Study Focus badge header widget -->
                                <div class="bg-white border-2 border-line rounded-3xl p-6 flex justify-between items-center shadow-sm">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase">STUDY FOCUS</span>
                                        <h2 class="text-lg font-bold font-display text-ink uppercase">
                                            {{ $focusLevel->name }} • {{ $focusStream ? $focusStream->name . ' • ' : '' }}{{ $focusBoard->name }}
                                        </h2>
                                    </div>
                                    <form action="/onboarding/clear" method="POST">
                                        @csrf
                                        <button type="submit" class="border border-line2 hover:border-slate bg-canvas text-ink text-[10px] font-bold font-mono tracking-widest px-3.5 py-2 rounded-xl transition-all">
                                            CHANGE
                                        </button>
                                    </form>
                                </div>

                                <!-- Search bar -->
                                <div class="bg-white border-2 border-line rounded-3xl p-4 flex items-center space-x-3 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" id="subject-search" placeholder="Search subjects or codes..." class="w-full bg-transparent text-sm text-ink placeholder-slate2 focus:outline-none font-medium">
                                </div>

                                <!-- List -->
                                <div class="space-y-4" id="subjects-container">
                                    <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase px-2 block">YOUR SUBJECTS</span>
                                    
                                    @forelse($subjects as $subject)
                                        <div class="subject-row bg-white border-2 border-line hover:border-slate2 rounded-2xl p-5 flex justify-between items-center cursor-pointer transition-all shadow-sm {{ $selectedSubject && $selectedSubject->id === $subject->id ? 'border-ink shadow-md' : '' }}" data-id="{{ $subject->id }}" data-search="{{ strtolower($subject->name) }} {{ strtolower($subject->code) }}" onclick="selectSubjectRow('{{ $subject->id }}')">
                                            <div class="space-y-1">
                                                <h3 class="text-base font-bold font-display text-ink">{{ $subject->name }}</h3>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-[10px] font-semibold font-mono text-slate uppercase bg-canvas px-2 py-0.5 rounded border border-line">
                                                        {{ $subject->code ?: 'NO CODE' }}
                                                    </span>
                                                    <span class="text-[10px] font-mono text-brandGreen font-bold">
                                                        {{ $subject->papers_count }} {{ Str::plural('PAPER', $subject->papers_count) }} AVAILABLE
                                                    </span>
                                                </div>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @empty
                                        <div class="bg-white border-2 border-line rounded-3xl p-8 text-center text-slate">
                                            <p class="text-sm font-medium">No subjects found for this selection.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Right Subject Grid View (Years Panel) -->
                            <div id="years-panel" class="w-full md:w-1/3 flex-grow space-y-6 panel-transition {{ $selectedSubject ? '' : 'hidden opacity-0 overflow-hidden' }}">
                                @if($selectedSubject)
                                    <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6 sticky top-24">
                                        <div class="flex items-center space-x-3 pb-4 border-b border-line">
                                            <!-- Back button -->
                                            <button onclick="expandLayouts()" class="p-2 rounded-xl border border-line bg-canvas hover:bg-line transition-all flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <div class="space-y-1 flex-grow">
                                                <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">SUBJECT PAPERS GRID</span>
                                                <h3 class="text-xl font-bold font-display text-ink leading-tight">{{ $selectedSubject->name }}</h3>
                                                <span class="inline-block text-[10px] font-semibold font-mono text-slate uppercase bg-canvas px-2 py-0.5 rounded border border-line">
                                                    CODE: {{ $selectedSubject->code ?: 'N/A' }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Years Grid layout -->
                                        <div class="space-y-4">
                                            <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase block">CHOOSE EXAM YEAR</span>
                                            
                                            <div class="grid grid-cols-1 gap-3">
                                                @foreach($papersGrid as $year => $info)
                                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-line bg-canvas/30">
                                                        <div class="flex items-center space-x-3">
                                                            <!-- Year Boxes (Signature Component #1) -->
                                                            <div class="flex space-x-1">
                                                                @php
                                                                    $twoDigit = sprintf('%02d', $year % 100);
                                                                    $d1 = substr($twoDigit, 0, 1);
                                                                    $d2 = substr($twoDigit, 1, 1);
                                                                @endphp
                                                                @if($info['available'])
                                                                    <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">{{ $d1 }}</div>
                                                                    <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">{{ $d2 }}</div>
                                                                @else
                                                                    <div class="w-8 h-8 rounded bg-canvas border border-dashed border-line2 text-slate2 font-mono font-bold text-sm flex items-center justify-center">{{ $d1 }}</div>
                                                                    <div class="w-8 h-8 rounded bg-canvas border border-dashed border-line2 text-slate2 font-mono font-bold text-sm flex items-center justify-center">{{ $d2 }}</div>
                                                                @endif
                                                            </div>
                                                            <span class="text-sm font-bold font-display text-ink">{{ $year }}</span>
                                                        </div>

                                                        <div>
                                                            @if($info['available'])
                                                                @php
                                                                    $papers = $info['papers'];
                                                                @endphp
                                                                @if(count($papers) === 1)
                                                                    @php $paper = $papers[0]; @endphp
                                                                    <div class="flex items-center space-x-2">
                                                                        <button onclick="handlePaperSave('{{ $paper->id }}', event)" class="p-2 rounded-lg border border-line hover:bg-canvas bg-white" data-paper-id="{{ $paper->id }}">
                                                                            @if(in_array($paper->id, $userSavedPaperIds))
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brandRed" viewBox="0 0 20 20" fill="currentColor">
                                                                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                                                                </svg>
                                                                            @else
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                                                </svg>
                                                                            @endif
                                                                        </button>
                                                                        <button onclick="handlePaperPreview('{{ $paper->id }}', '{{ $paper->file_url }}', '{{ $selectedSubject->name }}', '{{ $year }}', '{{ $paper->paper_set ?: 'A' }}', event)" class="bg-ink hover:bg-ink2 text-white text-xs font-mono font-bold px-4 py-2 rounded-xl transition-all">
                                                                            PREVIEW
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <div class="flex flex-wrap gap-2 items-center justify-end">
                                                                        @foreach($papers as $paper)
                                                                            <div class="flex items-center space-x-1">
                                                                                <button onclick="handlePaperSave('{{ $paper->id }}', event)" class="p-1 rounded-lg border border-line hover:bg-canvas bg-white" data-paper-id="{{ $paper->id }}">
                                                                                    @if(in_array($paper->id, $userSavedPaperIds))
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brandRed" viewBox="0 0 20 20" fill="currentColor">
                                                                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                                                                        </svg>
                                                                                    @else
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                                                        </svg>
                                                                                    @endif
                                                                                </button>
                                                                                <button onclick="handlePaperPreview('{{ $paper->id }}', '{{ $paper->file_url }}', '{{ $selectedSubject->name }}', '{{ $year }}', '{{ $paper->paper_set ?: 'A' }}', event)" class="bg-ink hover:bg-ink2 text-white text-[10px] font-bold font-mono tracking-wider px-3 py-1.5 rounded-lg transition-all">
                                                                                    SET {{ $paper->paper_set ?: 'A' }}
                                                                                </button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <button onclick="openActionModal('{{ $year }}', '{{ $selectedSubject->id }}', '{{ $selectedSubject->name }}')" class="border border-line2 hover:border-slate text-ink text-[10px] font-bold font-mono tracking-wider px-3 py-1.5 rounded-lg bg-white transition-all">
                                                                    CONTRIBUTE
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                    @endif

                    <!-- VIEW 2: SAVED PAPERS -->
                    @if($currentView === 'saved')
                        <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="pb-4 border-b border-line">
                                <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">YOU</span>
                                <h2 class="text-xl font-bold font-display text-ink">Saved Papers</h2>
                            </div>

                            <div class="space-y-4">
                                @forelse($savedPapers as $paper)
                                    <div class="flex items-center justify-between p-4 rounded-2xl border border-line hover:border-slate2 transition-all">
                                        <div class="flex items-center space-x-4">
                                            <!-- Year digits -->
                                            <div class="flex space-x-1">
                                                @php
                                                    $twoDigit = sprintf('%02d', $paper->year % 100);
                                                @endphp
                                                <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">{{ substr($twoDigit, 0, 1) }}</div>
                                                <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">{{ substr($twoDigit, 1, 1) }}</div>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-bold font-display text-ink">{{ $paper->subject->name ?? 'Subject' }} Paper ({{ $paper->year }})</h3>
                                                <span class="text-[10px] font-mono text-slate font-bold">SET {{ $paper->paper_set ?: 'A' }} • {{ strtoupper($paper->exam_type) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <form action="/papers/{{ $paper->id }}/toggle-save-web" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-brandRed hover:opacity-85 text-xs font-mono font-bold border border-line px-3 py-1.5 rounded-lg">
                                                    REMOVE
                                                </button>
                                            </form>
                                            <button onclick="handlePaperPreview('{{ $paper->id }}', '{{ $paper->file_url }}', '{{ $paper->subject->name ?? 'Paper' }}', '{{ $paper->year }}', '{{ $paper->paper_set ?: 'A' }}', event)" class="bg-ink hover:bg-ink2 text-white text-xs font-mono font-bold px-4 py-1.5 rounded-lg">
                                                OPEN PDF
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate space-y-2">
                                        <p class="text-sm">No saved papers found.</p>
                                        <p class="text-xs">Browse subjects and tap the bookmark icon to keep them saved here.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- VIEW 3: MY UPLOADS -->
                    @if($currentView === 'uploads')
                        <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="pb-4 border-b border-line">
                                <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">YOU</span>
                                <h2 class="text-xl font-bold font-display text-ink">My Uploaded Papers</h2>
                            </div>

                            <div class="space-y-4">
                                @forelse($mySubmissions as $sub)
                                    @php
                                        $status = strtolower($sub->status);
                                        $statusBg = 'bg-brandAmberSoft text-brandAmber border-brandAmber/20';
                                        if ($status === 'approved' || $status === 'verified') {
                                            $statusBg = 'bg-brandGreenSoft text-brandGreen border-brandGreen/20';
                                        } elseif ($status === 'rejected') {
                                            $statusBg = 'bg-brandRedSoft text-brandRed border-brandRed/20';
                                        }
                                    @endphp
                                    <div class="p-4 rounded-2xl border border-line bg-white space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="border px-2 py-0.5 rounded text-[9px] font-mono font-bold {{ $statusBg }}">
                                                {{ strtoupper($status) }}
                                            </span>
                                            <span class="text-[9px] font-mono text-slate2 font-bold">{{ $sub->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-sm font-bold font-display text-ink">{{ $sub->subject->name ?? 'Subject' }} ({{ $sub->year }})</h3>
                                        <span class="block text-[10px] font-mono text-slate">Set: {{ $sub->paper_set ?: 'A' }}</span>
                                        @if($status === 'rejected' && $sub->rejection_reason)
                                            <div class="mt-2 p-2 bg-brandRedSoft text-brandRed text-xs font-semibold rounded-lg">
                                                Rejection Reason: {{ $sub->rejection_reason }}
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate space-y-2">
                                        <p class="text-sm">No uploads contributed yet.</p>
                                        <p class="text-xs">Help the catalog grow by uploading missing years from subject grids.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- VIEW 4: MY REQUESTS -->
                    @if($currentView === 'requests')
                        <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6">
                            <div class="pb-4 border-b border-line">
                                <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">YOU</span>
                                <h2 class="text-xl font-bold font-display text-ink">My Demand Requests</h2>
                            </div>

                            <div class="space-y-4">
                                @forelse($myRequests as $req)
                                    @php
                                        $status = strtolower($req->status);
                                        $statusBg = 'bg-brandAmberSoft text-brandAmber border-brandAmber/20';
                                        if ($status === 'approved' || $status === 'fulfilled' || $status === 'resolved') {
                                            $statusBg = 'bg-brandGreenSoft text-brandGreen border-brandGreen/20';
                                        } elseif ($status === 'rejected') {
                                            $statusBg = 'bg-brandRedSoft text-brandRed border-brandRed/20';
                                        }
                                    @endphp
                                    <div class="p-4 rounded-2xl border border-line bg-white space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="border px-2 py-0.5 rounded text-[9px] font-mono font-bold {{ $statusBg }}">
                                                {{ strtoupper($status) }}
                                            </span>
                                            <span class="text-[9px] font-mono text-slate2 font-bold">{{ $req->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-sm font-bold font-display text-ink">{{ $req->subject->name ?? 'Subject' }} ({{ $req->year }})</h3>
                                        <span class="block text-[10px] font-mono text-slate">Requested Set: {{ $req->paper_set ?: 'A' }}</span>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate space-y-2">
                                        <p class="text-sm">No demands requests logged yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif
    </main>

    <!-- Contribution Action Modal -->
    <div id="action-modal" class="fixed inset-0 z-50 bg-ink/40 backdrop-blur-sm hidden items-center justify-center p-6">
        <div class="bg-white border-2 border-line rounded-3xl p-6 max-w-md w-full space-y-6 shadow-xl">
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <span class="text-[9px] font-bold font-mono text-slate tracking-widest uppercase">CONTRIBUTE PREVIOUS PAPER</span>
                    <h3 class="text-xl font-bold font-display text-ink" id="modal-subject-name">Physics</h3>
                    <p class="text-xs text-slate">Year: <span id="modal-year-label" class="font-bold font-mono">2024</span></p>
                </div>
                <button onclick="closeActionModal()" class="text-slate hover:text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Options (Upload / Request) -->
            <div class="grid grid-cols-2 gap-4" id="modal-choices">
                <button onclick="showModalForm('upload')" class="border-2 border-line hover:border-slate p-4 rounded-2xl bg-white flex flex-col items-center justify-center space-y-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="text-xs font-bold font-mono text-ink">UPLOAD PDF</span>
                </button>
                <button onclick="showModalForm('request')" class="border-2 border-line hover:border-slate p-4 rounded-2xl bg-white flex flex-col items-center justify-center space-y-2 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-xs font-bold font-mono text-ink">LOG DEMAND</span>
                </button>
            </div>

            <!-- Upload PDF Form -->
            <form action="/submissions/store" method="POST" enctype="multipart/form-data" class="space-y-4 hidden" id="modal-upload-form">
                @csrf
                <input type="hidden" name="subject_id" id="upload-subject-id">
                <input type="hidden" name="year" id="upload-year">
                
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">Set / Exam Code</label>
                    <input type="text" name="paper_set" placeholder="e.g. A" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">Select PDF File (Max 10MB)</label>
                    <input type="file" name="file" accept="application/pdf" required class="w-full text-xs text-slate file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-2 file:border-line file:bg-white file:text-xs file:font-semibold file:text-ink hover:file:bg-canvas cursor-pointer">
                </div>
                <button type="submit" class="w-full bg-ink hover:bg-ink2 text-white font-mono font-bold text-xs py-3.5 rounded-xl transition-all">
                    SUBMIT TO MODERATORS
                </button>
            </form>

            <!-- Request Paper Form -->
            <form action="/requests/store" method="POST" class="space-y-4 hidden" id="modal-request-form">
                @csrf
                <input type="hidden" name="subject_id" id="request-subject-id">
                <input type="hidden" name="year" id="request-year">
                
                <p class="text-xs text-slate font-sans">Logging a demand registers this missing subject paper on the admin dashboard roadmap to prioritize its catalog ingestion.</p>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">Paper Set/Type</label>
                    <input type="text" name="paper_set" placeholder="e.g. A, B or Supplementary" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <button type="submit" class="w-full bg-ink hover:bg-ink2 text-white font-mono font-bold text-xs py-3.5 rounded-xl transition-all">
                    SUBMIT DEMAND REQUEST
                </button>
            </form>
        </div>
    </div>

    <!-- ──────────────────────────────────────────── -->
    <!-- AUTHENTICATION MODAL (Login/Signup popup) -->
    <!-- ──────────────────────────────────────────── -->
    <div id="auth-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-ink/85 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white border-2 border-line rounded-3xl max-w-md w-full p-8 shadow-2xl relative space-y-6 animate-fade-in">
            <!-- Close Button -->
            <button onclick="closeAuthModal()" class="absolute top-4 right-4 p-2 rounded-full hover:bg-canvas text-slate">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Auth Form Header -->
            <div class="text-center space-y-2">
                <h3 id="auth-modal-title" class="text-2xl font-extrabold font-display tracking-tight text-ink">Student Log In</h3>
                <p id="auth-modal-subtitle" class="text-xs text-slate">Enter your registered mobile number and password to log in.</p>
            </div>

            <!-- Error message container -->
            <div id="auth-error-msg" class="hidden bg-brandRedSoft border border-brandRed/20 text-brandRed rounded-xl p-3 text-xs font-semibold"></div>

            <!-- Tab Buttons to switch Login / Signup -->
            <div class="flex border-2 border-line rounded-xl p-1 bg-canvas">
                <button onclick="setAuthTab('login')" id="tab-login-btn" class="w-1/2 py-2 text-xs font-bold font-mono rounded-lg bg-white text-ink shadow-sm transition-all">SIGN IN</button>
                <button onclick="setAuthTab('register')" id="tab-register-btn" class="w-1/2 py-2 text-xs font-bold font-mono rounded-lg text-slate transition-all">SIGN UP</button>
            </div>

            <!-- Login Form -->
            <form id="login-form" class="space-y-4" onsubmit="submitAuthForm(event, 'login')">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">MOBILE NUMBER</label>
                    <input type="tel" name="mobile_number" placeholder="e.g. 9876543210" required class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">PASSWORD</label>
                    <input type="password" name="password" placeholder="••••••" required class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <button type="submit" class="w-full bg-ink hover:bg-ink2 text-white font-mono font-bold text-xs py-3.5 rounded-xl transition-all">
                    LOG IN & ACCESS PAPERS
                </button>
            </form>

            <!-- Register Form -->
            <form id="register-form" class="space-y-4 hidden" onsubmit="submitAuthForm(event, 'register')">
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">FULL NAME</label>
                    <input type="text" name="name" placeholder="e.g. John Doe" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">MOBILE NUMBER</label>
                    <input type="tel" name="mobile_number" placeholder="e.g. 9876543210" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">SCHOOL / COLLEGE NAME</label>
                    <input type="text" name="school_college_name" placeholder="e.g. Greenwood Academy" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">PASSWORD</label>
                    <input type="password" name="password" placeholder="Min 6 characters" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">EMAIL ADDRESS (OPTIONAL)</label>
                    <input type="email" name="email" placeholder="e.g. john@example.com" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold font-mono text-slate uppercase">REFERRAL CODE (OPTIONAL)</label>
                    <input type="text" name="referral_code" placeholder="e.g. PRASHN123" class="w-full border-2 border-line rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-slate text-ink font-medium">
                </div>
                <button type="submit" class="w-full bg-ink hover:bg-ink2 text-white font-mono font-bold text-xs py-3.5 rounded-xl transition-all">
                    SIGN UP & ACCESS PAPERS
                </button>
            </form>
        </div>
    </div>

    <!-- ──────────────────────────────────────────── -->
    <!-- PAPER PREVIEW MODAL (Full Screen popup) -->
    <!-- ──────────────────────────────────────────── -->
    <div id="preview-modal" class="fixed inset-0 z-[110] hidden flex-col bg-canvas transition-all duration-300">
        <!-- Modal Header -->
        <header class="bg-white border-b-2 border-line px-6 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-3">
                <button onclick="closePreviewModal()" class="p-2 rounded-xl border border-line bg-canvas hover:bg-line transition-all flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h3 id="preview-subject-title" class="text-base font-bold font-display text-ink"></h3>
                    <p id="preview-paper-meta" class="text-xs text-slate font-mono font-bold"></p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a id="preview-download-btn" href="#" download target="_blank" class="bg-brandGreen hover:opacity-90 text-white font-mono font-bold text-xs px-5 py-2.5 rounded-xl transition-all flex items-center space-x-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>DOWNLOAD</span>
                </a>
            </div>
        </header>

        <!-- Modal Body (Iframe) -->
        <main class="flex-grow p-6">
            <iframe id="preview-pdf-iframe" src="" class="w-full h-full rounded-3xl border-2 border-line shadow-inner bg-white"></iframe>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t-2 border-line py-6 mt-12">
        <div class="max-w-[1400px] mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-slate text-xs font-medium gap-4">
            <span class="font-mono text-[10px]">© 2026 PRASHNPATRA APP. ALL RIGHTS RESERVED.</span>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-ink transition-all">Security Engine</a>
                <a href="#" class="hover:text-ink transition-all">Usage Terms</a>
            </div>
        </div>
    </footer>

    <!-- JS controllers -->
    <script>
        // Dynamic taxonomy data passed from Blade
        const levels = @json($levels);
        const streams = @json($streams);
        const boards = @json($boards);
        const semesters = @json($semesters);

        let currentStepIndex = 0;
        let stepsOrder = [];

        let selectedLevelId = null;
        let selectedStreamId = null;
        let selectedBoardId = null;
        let selectedSemesterId = null;
        let selectedSubjectIds = [];

        function updateProgress() {
            if (stepsOrder.length <= 1) return;
            const progressPct = Math.round((currentStepIndex / (stepsOrder.length - 1)) * 100);
            document.getElementById('step-percentage').innerText = progressPct + '% Complete';
            document.getElementById('step-progress-bar').style.width = progressPct + '%';
        }

        function showStep(stepId) {
            document.querySelectorAll('.onboarding-step').forEach(step => {
                step.classList.add('hidden');
            });
            document.getElementById('step-' + stepId).classList.remove('hidden');
            updateProgress();
        }

        function selectLevel(levelId) {
            selectedLevelId = levelId;
            document.getElementById('hidden-level-id').value = levelId;

            selectedStreamId = null;
            selectedBoardId = null;
            selectedSemesterId = null;
            selectedSubjectIds = [];
            document.getElementById('hidden-stream-id').value = '';
            document.getElementById('hidden-board-id').value = '';
            document.getElementById('hidden-semester-id').value = '';
            
            // Remove any previously added hidden subject inputs
            document.querySelectorAll('input[name="subject_ids[]"]').forEach(el => el.remove());

            const level = levels.find(l => l.id === levelId);
            const config = level.onboarding_config || {};

            stepsOrder = ['level'];
            if (config.requires_stream) stepsOrder.push('stream');
            if (config.requires_board) stepsOrder.push('board');
            if (config.requires_semester) stepsOrder.push('semester');
            stepsOrder.push('subjects');

            if (config.requires_stream) {
                const streamList = document.getElementById('stream-options-list');
                streamList.innerHTML = '';
                
                document.getElementById('stream-step-title').innerText = 'Select ' + (config.stream_label || 'Stream');
                document.getElementById('stream-step-desc').innerText = config.step_descriptions?.stream || 'Choose your specific course or stream.';

                const levelStreams = streams.filter(s => s.level_id === levelId);
                levelStreams.forEach(stream => {
                    const card = document.createElement('div');
                    card.className = 'option-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex items-center justify-between transition-all';
                    card.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-full bg-canvas flex items-center justify-center text-xs font-mono font-bold text-slate">🎓</span>
                            <span class="text-lg font-bold font-display text-ink">${stream.name}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    `;
                    card.onclick = () => selectStream(stream.id);
                    streamList.appendChild(card);
                });
            }

            if (config.requires_board) {
                document.getElementById('board-step-title').innerText = 'Choose ' + (config.board_label || 'Board');
                document.getElementById('board-step-desc').innerText = config.step_descriptions?.board || 'Select the board or university you study under.';
                
                renderBoards(config.board_filter_type);
                
                const searchInput = document.getElementById('board-search-input');
                searchInput.value = '';
                searchInput.placeholder = config.board_placeholder || 'Search...';
                searchInput.oninput = (e) => {
                    renderBoards(config.board_filter_type, e.target.value);
                };
            }

            if (config.requires_semester) {
                const semList = document.getElementById('semester-options-list');
                semList.innerHTML = '';
                document.getElementById('semester-step-title').innerText = 'Choose ' + (config.semester_label || 'Semester');
                document.getElementById('semester-step-desc').innerText = config.step_descriptions?.semester || 'Select your current semester.';

                const levelSemesters = semesters.filter(s => s.level_id === levelId);
                levelSemesters.forEach(sem => {
                    const card = document.createElement('div');
                    card.className = 'option-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex items-center justify-between transition-all';
                    card.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-8 rounded-full bg-canvas flex items-center justify-center text-xs font-mono font-bold text-slate">📅</span>
                            <span class="text-lg font-bold font-display text-ink">${config.semester_label || 'Semester'} ${sem.number}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    `;
                    card.onclick = () => selectSemester(sem.id);
                    semList.appendChild(card);
                });
            }

            currentStepIndex = 1;
            showStep(stepsOrder[currentStepIndex]);
        }

        function selectStream(streamId) {
            selectedStreamId = streamId;
            document.getElementById('hidden-stream-id').value = streamId;

            currentStepIndex = stepsOrder.indexOf('stream') + 1;
            showStep(stepsOrder[currentStepIndex]);
        }

        function renderBoards(filterType, query = '') {
            const boardList = document.getElementById('board-options-list');
            boardList.innerHTML = '';

            let filteredBoards = boards;

            if (filterType === 'university') {
                filteredBoards = boards.filter(b => b.name.toLowerCase().includes('university') || (b.full_name && b.full_name.toLowerCase().includes('university')));
            } else if (filterType === 'board') {
                filteredBoards = boards.filter(b => !b.name.toLowerCase().includes('university') && !(b.full_name && b.full_name.toLowerCase().includes('university')));
            }

            if (query.trim() !== '') {
                const q = query.toLowerCase();
                filteredBoards = filteredBoards.filter(b => b.name.toLowerCase().includes(q) || (b.full_name && b.full_name.toLowerCase().includes(q)));
            }

            if (filteredBoards.length === 0) {
                boardList.innerHTML = '<p class="text-sm text-slate py-4 text-center">No boards or universities found matching your search.</p>';
                return;
            }

            filteredBoards.forEach(board => {
                const card = document.createElement('div');
                card.className = 'option-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-4 bg-white flex items-center justify-between transition-all';
                card.innerHTML = `
                    <div>
                        <span class="text-[9px] font-bold font-mono tracking-wider uppercase bg-canvas text-ink px-2 py-0.5 rounded border border-line">
                            ${board.is_national ? 'National' : (board.state?.name ?? 'State')}
                        </span>
                        <h3 class="text-base font-bold font-display mt-2 text-ink">${board.name}</h3>
                        <p class="text-xs text-slate mt-0.5">${board.full_name || 'State examination council'}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                `;
                card.onclick = () => selectBoard(board.id);
                boardList.appendChild(card);
            });
        }

        function selectBoard(boardId) {
            selectedBoardId = boardId;
            document.getElementById('hidden-board-id').value = boardId;

            currentStepIndex = stepsOrder.indexOf('board') + 1;
            showStep(stepsOrder[currentStepIndex]);

            if (stepsOrder[currentStepIndex] === 'subjects') {
                fetchOnboardingSubjects();
            }
        }

        function selectSemester(semesterId) {
            selectedSemesterId = semesterId;
            document.getElementById('hidden-semester-id').value = semesterId;

            currentStepIndex = stepsOrder.indexOf('semester') + 1;
            showStep(stepsOrder[currentStepIndex]);

            fetchOnboardingSubjects();
        }

        function fetchOnboardingSubjects() {
            const listContainer = document.getElementById('subjects-selection-list');
            listContainer.innerHTML = '<div class="col-span-full py-8 flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-ink"></div></div>';

            let url = `/web/subjects?board_id=${selectedBoardId}`;
            if (selectedStreamId) url += `&stream_id=${selectedStreamId}`;
            if (selectedSemesterId) url += `&semester_id=${selectedSemesterId}`;

            fetch(url)
                .then(res => res.json())
                .then(json => {
                    const list = json.data || [];
                    listContainer.innerHTML = '';
                    if (list.length === 0) {
                        listContainer.innerHTML = '<p class="text-sm text-slate py-8 col-span-full text-center">No subjects found for this scope. Lock focus to proceed to dashboard.</p>';
                        return;
                    }
                    
                    list.forEach(subject => {
                        const card = document.createElement('div');
                        card.className = 'subject-select-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-4 bg-white flex items-center justify-between transition-all select-none';
                        card.setAttribute('data-id', subject.id);
                        card.innerHTML = `
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-ink">${subject.name}</h4>
                                <span class="inline-block text-[9px] font-mono font-bold text-slate bg-canvas px-1.5 py-0.5 rounded border border-line">
                                    ${subject.code || 'SUBJ'}
                                </span>
                            </div>
                            <div class="checkbox-indicator w-5 h-5 rounded border border-line2 flex items-center justify-center transition-all bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white hidden" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </div>
                        `;
                        
                        card.onclick = () => {
                            const indicator = card.querySelector('.checkbox-indicator');
                            const checkIcon = indicator.querySelector('svg');
                            const subId = subject.id;
                            
                            const index = selectedSubjectIds.indexOf(subId);
                            if (index > -1) {
                                selectedSubjectIds.splice(index, 1);
                                card.classList.remove('border-ink');
                                indicator.classList.remove('bg-ink', 'border-ink');
                                indicator.classList.add('bg-white', 'border-line2');
                                checkIcon.classList.add('hidden');
                                const input = document.getElementById('subject-input-' + subId);
                                if (input) input.remove();
                            } else {
                                selectedSubjectIds.push(subId);
                                card.classList.add('border-ink');
                                indicator.classList.remove('bg-white', 'border-line2');
                                indicator.classList.add('bg-ink', 'border-ink');
                                checkIcon.classList.remove('hidden');
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'subject_ids[]';
                                input.value = subId;
                                input.id = 'subject-input-' + subId;
                                document.getElementById('onboarding-form').appendChild(input);
                            }
                        };
                        
                        listContainer.appendChild(card);
                    });
                })
                .catch(err => {
                    listContainer.innerHTML = '<p class="text-sm text-brandRed py-8 col-span-full text-center">Failed to load subjects. Please check connection and try again.</p>';
                });
        }

        function goBack() {
            if (currentStepIndex > 0) {
                currentStepIndex--;
                showStep(stepsOrder[currentStepIndex]);
            }
        }

        // Subject list filter
        const searchInput = document.getElementById('subject-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                document.querySelectorAll('.subject-row').forEach(row => {
                    const searchData = row.getAttribute('data-search');
                    if (searchData.includes(query)) {
                        row.style.display = 'flex';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Action Modal
        function openActionModal(year, subjectId, subjectName) {
            document.getElementById('modal-subject-name').innerText = subjectName;
            document.getElementById('modal-year-label').innerText = year;
            
            document.getElementById('upload-subject-id').value = subjectId;
            document.getElementById('upload-year').value = year;
            document.getElementById('request-subject-id').value = subjectId;
            document.getElementById('request-year').value = year;

            document.getElementById('modal-choices').classList.remove('hidden');
            document.getElementById('modal-upload-form').classList.add('hidden');
            document.getElementById('modal-request-form').classList.add('hidden');

            document.getElementById('action-modal').classList.remove('hidden');
            document.getElementById('action-modal').classList.add('flex');
        }

        function closeActionModal() {
            document.getElementById('action-modal').classList.remove('flex');
            document.getElementById('action-modal').classList.add('hidden');
        }

        function showModalForm(type) {
            document.getElementById('modal-choices').classList.add('hidden');
            if (type === 'upload') {
                document.getElementById('modal-upload-form').classList.remove('hidden');
            } else if (type === 'request') {
                document.getElementById('modal-request-form').classList.remove('hidden');
            }
        }

        // --- NEW SLIDING PANELS CONTROLLERS ---
        let activeSubjectId = null;

        function selectSubjectRow(subjectId) {
            // Highlight selected row
            document.querySelectorAll('.subject-row').forEach(row => {
                row.classList.remove('border-ink', 'shadow-md');
            });
            const clickedRow = document.querySelector(`.subject-row[data-id="${subjectId}"]`);
            if (clickedRow) {
                clickedRow.classList.add('border-ink', 'shadow-md');
            }

            activeSubjectId = subjectId;

            // Update query param
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?subject=' + subjectId;
            window.history.pushState({ path: newUrl }, '', newUrl);

            // Fetch papers grid
            loadSubjectPapers(subjectId);
        }

        function loadSubjectPapers(subjectId) {
            const yearsPanel = document.getElementById('years-panel');
            const sidebar = document.getElementById('sidebar-panel');
            const subjects = document.getElementById('subjects-panel');

            // Collapse sidebar, minimize subjects panel, show years panel
            if (sidebar) sidebar.classList.add('sidebar-collapsed');
            if (subjects) subjects.classList.add('subjects-minimized');
            if (yearsPanel) {
                yearsPanel.classList.remove('hidden', 'opacity-0', 'overflow-hidden');
            }

            // Show loading state inside years panel
            yearsPanel.innerHTML = `
                <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6 sticky top-24 animate-pulse">
                    <div class="flex items-center space-x-3 pb-4 border-b border-line">
                        <button onclick="expandLayouts()" class="p-2 rounded-xl border border-line bg-canvas hover:bg-line transition-all flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div class="space-y-1">
                            <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">LOADING PAPERS...</span>
                        </div>
                    </div>
                    <div class="py-12 flex justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-ink"></div>
                    </div>
                </div>
            `;

            fetch(`/web/subjects/${subjectId}/papers`)
                .then(res => res.json())
                .then(response => {
                    if (!response.success) {
                        yearsPanel.innerHTML = `<div class="bg-white border-2 border-line rounded-3xl p-6 text-center text-brandRed">Error loading papers.</div>`;
                        return;
                    }

                    const subject = response.subject;
                    const grid = response.grid;

                    let gridHtml = '';
                    grid.forEach(item => {
                        const year = item.year;
                        const available = item.available;
                        const papers = item.papers;

                        const twoDigit = String(year % 100).padStart(2, '0');
                        const d1 = twoDigit.substring(0, 1);
                        const d2 = twoDigit.substring(1, 2);

                        let actionHtml = '';
                        if (available) {
                            if (papers.length === 1) {
                                const paper = papers[0];
                                actionHtml = `
                                    <div class="flex items-center space-x-2">
                                        <button onclick="handlePaperSave('${paper.id}', event)" class="p-2 rounded-lg border border-line hover:bg-canvas bg-white" data-paper-id="${paper.id}">
                                            ${renderSaveIcon(paper.id)}
                                        </button>
                                        <button onclick="handlePaperPreview('${paper.id}', '${paper.file_url}', '${subject.name.replace(/'/g, "\\'")}', '${year}', '${paper.paper_set || 'A'}', event)" class="bg-ink hover:bg-ink2 text-white text-xs font-mono font-bold px-4 py-2 rounded-xl transition-all">
                                            PREVIEW
                                        </button>
                                    </div>
                                `;
                            } else {
                                actionHtml = `<div class="flex flex-wrap gap-2 items-center justify-end">`;
                                papers.forEach(paper => {
                                    actionHtml += `
                                        <div class="flex items-center space-x-1">
                                            <button onclick="handlePaperSave('${paper.id}', event)" class="p-1 rounded-lg border border-line hover:bg-canvas bg-white" data-paper-id="${paper.id}">
                                                ${renderSaveIcon(paper.id)}
                                            </button>
                                            <button onclick="handlePaperPreview('${paper.id}', '${paper.file_url}', '${subject.name.replace(/'/g, "\\'")}', '${year}', '${paper.paper_set || 'A'}', event)" class="bg-ink hover:bg-ink2 text-white text-[10px] font-bold font-mono tracking-wider px-3 py-1.5 rounded-lg transition-all">
                                                SET ${paper.paper_set || 'A'}
                                            </button>
                                        </div>
                                    `;
                                });
                                actionHtml += `</div>`;
                            }
                        } else {
                            actionHtml = `
                                <button onclick="openActionModal('${year}', '${subject.id}', '${subject.name.replace(/'/g, "\\'")}')" class="border border-line2 hover:border-slate text-ink text-[10px] font-bold font-mono tracking-wider px-3 py-1.5 rounded-lg bg-white transition-all">
                                    CONTRIBUTE
                                </button>
                            `;
                        }

                        gridHtml += `
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-line bg-canvas/30">
                                <div class="flex items-center space-x-3">
                                    <div class="flex space-x-1">
                                        ${available ? `
                                            <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">${d1}</div>
                                            <div class="w-8 h-8 rounded bg-ink text-white font-mono font-bold text-sm flex items-center justify-center">${d2}</div>
                                        ` : `
                                            <div class="w-8 h-8 rounded bg-canvas border border-dashed border-line2 text-slate2 font-mono font-bold text-sm flex items-center justify-center">${d1}</div>
                                            <div class="w-8 h-8 rounded bg-canvas border border-dashed border-line2 text-slate2 font-mono font-bold text-sm flex items-center justify-center">${d2}</div>
                                        `}
                                    </div>
                                    <span class="text-sm font-bold font-display text-ink">${year}</span>
                                </div>
                                <div>
                                    ${actionHtml}
                                </div>
                            </div>
                        `;
                    });

                    yearsPanel.innerHTML = `
                        <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6 sticky top-24">
                            <div class="flex items-center space-x-3 pb-4 border-b border-line">
                                <button onclick="expandLayouts()" class="p-2 rounded-xl border border-line bg-canvas hover:bg-line transition-all flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <div class="space-y-1 flex-grow">
                                    <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">SUBJECT PAPERS GRID</span>
                                    <h3 class="text-xl font-bold font-display text-ink leading-tight">${subject.name}</h3>
                                    <span class="inline-block text-[10px] font-semibold font-mono text-slate uppercase bg-canvas px-2 py-0.5 rounded border border-line">
                                        CODE: ${subject.code || 'N/A'}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase block">CHOOSE EXAM YEAR</span>
                                <div class="grid grid-cols-1 gap-3">
                                    ${gridHtml}
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(err => {
                    yearsPanel.innerHTML = `<div class="bg-white border-2 border-line rounded-3xl p-6 text-center text-brandRed">Failed to retrieve papers.</div>`;
                });
        }

        function expandLayouts() {
            // Restore URL query param
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({ path: newUrl }, '', newUrl);

            document.querySelectorAll('.subject-row').forEach(row => {
                row.classList.remove('border-ink', 'shadow-md');
            });

            activeSubjectId = null;

            const sidebar = document.getElementById('sidebar-panel');
            const subjects = document.getElementById('subjects-panel');
            const years = document.getElementById('years-panel');

            if (sidebar) sidebar.classList.remove('sidebar-collapsed');
            if (subjects) subjects.classList.remove('subjects-minimized');
            if (years) {
                years.classList.add('hidden', 'opacity-0', 'overflow-hidden');
            }
        }

        // Bookmark saved list
        const userSavedPaperIds = @json($userSavedPaperIds);

        function renderSaveIcon(paperId) {
            const saved = userSavedPaperIds.includes(paperId);
            if (saved) {
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brandRed" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                    </svg>
                `;
            } else {
                return `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                `;
            }
        }

        function handlePaperSave(paperId, event) {
            event.stopPropagation();
            
            fetch(`/papers/${paperId}/toggle-save-web`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                const index = userSavedPaperIds.indexOf(paperId);
                if (index > -1) {
                    userSavedPaperIds.splice(index, 1);
                } else {
                    userSavedPaperIds.push(paperId);
                }

                // Update the icon inside all buttons referencing this paperId
                document.querySelectorAll(`button[onclick*="handlePaperSave('${paperId}'"]`).forEach(btn => {
                    btn.innerHTML = renderSaveIcon(paperId);
                });
            })
            .catch(err => console.error("Error toggling save:", err));
        }

        // --- NEW WEB AUTH & PREVIEW CONTROLLERS ---
        const userIsReal = {{ is_null($webUser->mobile_number) ? 'false' : 'true' }};

        function handlePaperPreview(paperId, fileUrl, subjectName, year, paperSet, event) {
            if (event) event.stopPropagation();

            if (!userIsReal) {
                const pending = {
                    id: paperId,
                    url: fileUrl,
                    name: subjectName,
                    year: year,
                    set: paperSet
                };
                sessionStorage.setItem('pending_preview', JSON.stringify(pending));
                openAuthModal();
            } else {
                openFullPreviewModal(fileUrl, subjectName, year, paperSet);
            }
        }

        function openFullPreviewModal(fileUrl, subjectName, year, paperSet) {
            document.getElementById('preview-subject-title').innerText = subjectName;
            document.getElementById('preview-paper-meta').innerText = `${year} EXAMINATION PAPER • SET ${paperSet}`;
            document.getElementById('preview-pdf-iframe').src = fileUrl;
            document.getElementById('preview-download-btn').href = fileUrl;

            const modal = document.getElementById('preview-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            document.body.style.overflow = 'hidden';
        }

        function closePreviewModal() {
            const modal = document.getElementById('preview-modal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('preview-pdf-iframe').src = '';
            document.body.style.overflow = '';
        }

        function logoutRealUser() {
            fetch('/web/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                window.location.href = '/';
            });
        }

        function openAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('auth-error-msg').classList.add('hidden');
        }

        function closeAuthModal() {
            const modal = document.getElementById('auth-modal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            sessionStorage.removeItem('pending_preview');
        }

        let activeAuthTab = 'login';
        function setAuthTab(tab) {
            activeAuthTab = tab;
            const loginForm = document.getElementById('login-form');
            const regForm = document.getElementById('register-form');
            const loginBtn = document.getElementById('tab-login-btn');
            const regBtn = document.getElementById('tab-register-btn');
            const title = document.getElementById('auth-modal-title');
            const subtitle = document.getElementById('auth-modal-subtitle');

            document.getElementById('auth-error-msg').classList.add('hidden');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                regForm.classList.add('hidden');
                loginBtn.classList.add('bg-white', 'text-ink', 'shadow-sm');
                loginBtn.classList.remove('text-slate');
                regBtn.classList.remove('bg-white', 'text-ink', 'shadow-sm');
                regBtn.classList.add('text-slate');
                title.innerText = 'Student Log In';
                subtitle.innerText = 'Enter your registered mobile number and password to log in.';
            } else {
                loginForm.classList.add('hidden');
                regForm.classList.remove('hidden');
                regBtn.classList.add('bg-white', 'text-ink', 'shadow-sm');
                regBtn.classList.remove('text-slate');
                loginBtn.classList.remove('bg-white', 'text-ink', 'shadow-sm');
                loginBtn.classList.add('text-slate');
                title.innerText = 'Create Account';
                subtitle.innerText = 'Register today to access previous year papers, track progress, and bookmark topics.';
            }
        }

        function submitAuthForm(event, type) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            const url = type === 'login' ? '/web/login' : '/web/register';
            const errorContainer = document.getElementById('auth-error-msg');
            errorContainer.classList.add('hidden');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    errorContainer.innerText = data.message || 'Auth failed. Please check inputs.';
                    errorContainer.classList.remove('hidden');
                }
            })
            .catch(err => {
                console.error(err);
                errorContainer.innerText = 'An error occurred. Please try again.';
                errorContainer.classList.remove('hidden');
            });
        }

        // Restore pending preview on document ready if logged in
        document.addEventListener('DOMContentLoaded', () => {
            const pendingPreview = sessionStorage.getItem('pending_preview');
            if (pendingPreview && userIsReal) {
                const pending = JSON.parse(pendingPreview);
                openFullPreviewModal(pending.url, pending.name, pending.year, pending.set);
                sessionStorage.removeItem('pending_preview');
            }
        });
    </script>
</body>
</html>
