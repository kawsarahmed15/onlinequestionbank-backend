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
                        brandGreen: '#1C7C4C',
                        brandAmber: '#B8860B',
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
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white border-b-2 border-line">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="/" class="text-2xl font-bold font-display tracking-tight text-ink hover:opacity-80 transition-all">
                    Prashnpatra
                </a>
                <span class="bg-canvas text-ink text-xs font-mono px-3 py-1 rounded border border-line font-bold">
                    WEB PORTAL
                </span>
            </div>

            <!-- Profile Avatar (Matches Flutter AppBar Profile icon) -->
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

    <!-- Main Container -->
    <main class="max-w-4xl w-full mx-auto px-6 py-8 flex-grow">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-2 border-brandGreen/20 rounded-2xl flex items-center space-x-3 text-brandGreen">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- ──────────────────────────────────────────── -->
        <!-- ONBOARDING FLOW (No active focus set) -->
        <!-- ──────────────────────────────────────────── -->
        @if(!$focusLevel || !$focusBoard)
            <section class="max-w-2xl mx-auto space-y-8 bg-white border-2 border-line rounded-3xl p-8 shadow-sm">
                <div class="space-y-2 text-center">
                    <h1 class="text-3xl font-extrabold font-display tracking-tight text-ink">Choose your Focus</h1>
                    <p class="text-sm text-slate">Match the layout, data structure, and question bank filters to your current curriculum studies.</p>
                </div>

                <form action="/onboarding/save" method="POST" class="space-y-6" id="onboarding-form">
                    @csrf

                    <!-- 1. Select Class/Level -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold font-mono tracking-wider text-slate uppercase">1. CHOOSE CLASS / LEVEL</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($levels as $level)
                                <label class="level-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex flex-col justify-between transition-all" data-id="{{ $level->id }}">
                                    <input type="radio" name="level_id" value="{{ $level->id }}" required class="sr-only">
                                    <div class="flex justify-between items-start">
                                        <div class="bg-canvas border border-line rounded-full w-8 h-8 flex items-center justify-center text-xs font-mono font-bold text-ink">
                                            L{{ $level->sort_order }}
                                        </div>
                                        <span class="check-indicator hidden w-4 h-4 bg-ink rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold font-display mt-6 text-ink">{{ $level->name }}</h3>
                                    <p class="text-xs text-slate mt-1">Syllabus papers coverage</p>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Select Stream (Appears dynamically if Class XII or Degree selected) -->
                    <div class="space-y-3 hidden" id="stream-container">
                        <label class="block text-xs font-bold font-mono tracking-wider text-slate uppercase">2. CHOOSE STREAM</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($streams as $stream)
                                <label class="stream-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex flex-col justify-between transition-all" data-level="{{ $stream->level_id }}" data-id="{{ $stream->id }}">
                                    <input type="radio" name="stream_id" value="{{ $stream->id }}" class="sr-only">
                                    <div class="flex justify-between items-center w-full">
                                        <span class="text-sm font-semibold font-mono text-slate">STREAM</span>
                                        <span class="check-indicator hidden w-4 h-4 bg-ink rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold font-display mt-4 text-ink">{{ $stream->name }}</h3>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Select Board -->
                    <div class="space-y-3">
                        <label class="block text-xs font-bold font-mono tracking-wider text-slate uppercase">3. CHOOSE EDUCATION BOARD</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($boards as $board)
                                <label class="board-card cursor-pointer border-2 border-line hover:border-slate rounded-2xl p-5 bg-white flex flex-col justify-between transition-all" data-id="{{ $board->id }}">
                                    <input type="radio" name="board_id" value="{{ $board->id }}" required class="sr-only">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[10px] font-bold font-mono tracking-wider uppercase bg-canvas text-ink px-2 py-0.5 rounded border border-line">
                                            {{ $board->is_national ? 'National' : ($board->state->name ?? 'State') }}
                                        </span>
                                        <span class="check-indicator hidden w-4 h-4 bg-ink rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold font-display mt-6 text-ink">{{ $board->name }}</h3>
                                    <p class="text-xs text-slate mt-1">{{ $board->full_name }}</p>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Focus Button (Stepper CTA) -->
                    <button type="submit" class="w-full bg-ink text-white font-mono font-bold tracking-widest text-xs py-4 rounded-xl hover:bg-ink2 transition-all">
                        LOCK FOCUS & CONTINUE
                    </button>
                </form>
            </section>

        <!-- ──────────────────────────────────────────── -->
        <!-- DASHBOARD VIEW (Active focus set) -->
        <!-- ──────────────────────────────────────────── -->
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left/Main Section: Subjects List -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Dynamic Onboarding Focus Header Widget -->
                    <div class="bg-white border-2 border-line rounded-3xl p-6 flex justify-between items-center shadow-sm">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase">STUDY FOCUS FOCUS</span>
                            <div class="flex items-center space-x-2">
                                <h2 class="text-lg font-bold font-display text-ink uppercase">
                                    {{ $focusLevel->name }} • {{ $focusStream ? $focusStream->name . ' • ' : '' }}{{ $focusBoard->name }}
                                </h2>
                            </div>
                        </div>
                        <form action="/onboarding/clear" method="POST">
                            @csrf
                            <button type="submit" class="border border-line2 hover:border-slate bg-canvas text-ink text-[10px] font-bold font-mono tracking-widest px-3.5 py-2 rounded-xl transition-all">
                                CHANGE
                            </button>
                        </form>
                    </div>

                    <!-- Subject Search Box (Matching Flutter search bar) -->
                    <div class="bg-white border-2 border-line rounded-3xl p-4 flex items-center space-x-3 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="subject-search" placeholder="Search subjects or codes..." class="w-full bg-transparent text-sm text-ink placeholder-slate2 focus:outline-none font-medium">
                    </div>

                    <!-- Subjects List Rows -->
                    <div class="space-y-4" id="subjects-container">
                        <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase px-2">YOUR DASHBOARD</span>
                        
                        @forelse($subjects as $subject)
                            <div class="subject-row bg-white border-2 border-line hover:border-slate2 rounded-2xl p-5 flex justify-between items-center cursor-pointer transition-all shadow-sm" data-search="{{ strtolower($subject->name) }} {{ strtolower($subject->code) }}" onclick="window.location.href='/?subject={{ $subject->id }}'">
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
                                <p class="text-sm font-medium">No subjects found for this class & board focus.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Section: Subject Detail Years Grid Or Info Cards -->
                <div class="lg:col-span-1 space-y-6">
                    @if($selectedSubject)
                        <div class="bg-white border-2 border-line rounded-3xl p-6 shadow-sm space-y-6 sticky top-24">
                            <!-- Subject Header details -->
                            <div class="space-y-2 pb-4 border-b border-line">
                                <span class="text-[9px] font-mono text-slate font-bold tracking-widest uppercase">SUBJECT PAPERS GRID</span>
                                <h3 class="text-xl font-bold font-display text-ink leading-tight">{{ $selectedSubject->name }}</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-semibold font-mono text-slate uppercase bg-canvas px-2 py-0.5 rounded border border-line">
                                        CODE: {{ $selectedSubject->code ?: 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Year List Grid (Signature digit elements) -->
                            <div class="space-y-4">
                                <span class="text-[10px] font-bold font-mono tracking-widest text-slate uppercase block">CHOOSE EXAM YEAR</span>
                                
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($papersGrid as $year => $info)
                                        <div class="flex items-center justify-between p-3 rounded-2xl border border-line bg-canvas/30">
                                            <!-- Year Digit Box (Signature component #1) -->
                                            <div class="flex items-center space-x-3">
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

                                            <!-- Options triggers -->
                                            <div>
                                                @if($info['available'])
                                                    <div class="space-y-1 text-right">
                                                        @foreach($info['papers'] as $paper)
                                                            <a href="{{ $paper->file_path }}" target="_blank" class="inline-block bg-ink text-white text-[10px] font-bold font-mono tracking-wider px-3 py-1.5 rounded-lg hover:bg-ink2 transition-all">
                                                                SET {{ $paper->paper_set ?: 'A' }} PDF
                                                            </a>
                                                        @endforeach
                                                    </div>
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
                    @else
                        <!-- No subject selected empty panel -->
                        <div class="bg-white border-2 border-line rounded-3xl p-8 text-center text-slate space-y-3">
                            <div class="w-12 h-12 bg-canvas rounded-full flex items-center justify-center mx-auto text-slate2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h4 class="font-bold font-display text-ink text-base">Subject Details View</h4>
                            <p class="text-xs">Select any subject from the dashboard list on the left to display its available years status grid.</p>
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

            <!-- Options buttons toggles (Upload / Request) -->
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
                
                <p class="text-xs text-slate">Logging a demand registers this missing subject paper on the admin dashboard roadmap to prioritize its catalog ingestion.</p>
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

    <!-- Footer -->
    <footer class="bg-white border-t-2 border-line py-6 mt-12">
        <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between text-slate text-xs font-medium gap-4">
            <span class="font-mono text-[10px]">© 2026 PRASHNPATRA APP. ALL RIGHTS RESERVED.</span>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-ink transition-all">Security Engine</a>
                <a href="#" class="hover:text-ink transition-all">Usage Terms</a>
            </div>
        </div>
    </footer>

    <!-- Interactive JS controllers -->
    <script>
        // 1. Dynamic Onboarding Stepper logic
        document.querySelectorAll('.level-card').forEach(card => {
            card.addEventListener('click', () => {
                // Clear active selections
                document.querySelectorAll('.level-card').forEach(c => {
                    c.classList.remove('border-ink');
                    c.querySelector('.check-indicator').classList.add('hidden');
                });
                card.classList.add('border-ink');
                card.querySelector('.check-indicator').classList.remove('hidden');
                
                // Show streams mapping dynamically if selected XII (33333333-3333-3333-3333-333333333333) or Degree
                const levelId = card.getAttribute('data-id');
                const streamContainer = document.getElementById('stream-container');
                if (levelId === '33333333-3333-3333-3333-333333333333' || levelId === '44444444-4444-4444-4444-444444444444') {
                    streamContainer.classList.remove('hidden');
                    // filter streams
                    document.querySelectorAll('.stream-card').forEach(sc => {
                        if (sc.getAttribute('data-level') === levelId) {
                            sc.classList.remove('hidden');
                        } else {
                            sc.classList.add('hidden');
                        }
                    });
                } else {
                    streamContainer.classList.add('hidden');
                    // Uncheck stream inputs
                    document.querySelectorAll('input[name="stream_id"]').forEach(i => i.checked = false);
                }
            });
        });

        document.querySelectorAll('.stream-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.stream-card').forEach(c => {
                    c.classList.remove('border-ink');
                    c.querySelector('.check-indicator').classList.add('hidden');
                });
                card.classList.add('border-ink');
                card.querySelector('.check-indicator').classList.remove('hidden');
            });
        });

        document.querySelectorAll('.board-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.board-card').forEach(c => {
                    c.classList.remove('border-ink');
                    c.querySelector('.check-indicator').classList.add('hidden');
                });
                card.classList.add('border-ink');
                card.querySelector('.check-indicator').classList.remove('hidden');
            });
        });

        // 2. Real-time Subject list filter search
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

        // 3. Action Modal triggers
        function openActionModal(year, subjectId, subjectName) {
            document.getElementById('modal-subject-name').innerText = subjectName;
            document.getElementById('modal-year-label').innerText = year;
            
            // Set input values
            document.getElementById('upload-subject-id').value = subjectId;
            document.getElementById('upload-year').value = year;
            document.getElementById('request-subject-id').value = subjectId;
            document.getElementById('request-year').value = year;

            // Reset modal state view
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
    </script>
</body>
</html>
