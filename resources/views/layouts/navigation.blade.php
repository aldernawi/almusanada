<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->isReviewer() || auth()->user()->isViewer() ? route('medical-auditing.index') : route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Almusanada" class="h-10 w-auto object-contain transition-all duration-300 group-hover:scale-105 drop-shadow-sm">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    @if(auth()->user()->isReviewer() || auth()->user()->isViewer())
                        <a href="{{ route('medical-auditing.index') }}" class="nav-link-modern {{ request()->routeIs('medical-auditing.*') || request()->routeIs('reviewer.forms.submissions') ? 'nav-active' : '' }}">
                            <i class="fas fa-clipboard-check text-sm"></i>
                            <span>Auditing</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link-modern {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                            <i class="fas fa-th-large text-sm"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('forms.index') }}" class="nav-link-modern {{ request()->routeIs('forms.*') ? 'nav-active' : '' }}">
                            <i class="fas fa-file-medical text-sm"></i>
                            <span>Forms</span>
                        </a>
                        <a href="{{ route('medical-auditing.index') }}" class="nav-link-modern {{ request()->routeIs('medical-auditing.*') || request()->routeIs('reviewer.forms.submissions') ? 'nav-active' : '' }}">
                            <i class="fas fa-clipboard-check text-sm"></i>
                            <span>Auditing</span>
                        </a>
                        <a href="{{ route('reviewer.assignment') }}" class="nav-link-modern {{ request()->routeIs('reviewer.assignment') ? 'nav-active' : '' }}">
                            <i class="fas fa-user-plus text-sm"></i>
                            <span>Reviewer Assignment</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="nav-link-modern {{ request()->routeIs('users.*') ? 'nav-active' : '' }}">
                            <i class="fas fa-users text-sm"></i>
                            <span>Users</span>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('home') }}" class="nav-link-modern" target="_blank">
                            <i class="fas fa-globe text-sm"></i>
                            <span>Website</span>
                        </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2.5 px-3 py-1.5 border border-transparent text-sm font-medium rounded-xl text-gray-600 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="text-right hidden md:block">
                                <div class="text-xs font-bold text-gray-800 leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-gray-400 leading-tight">{{ Auth::user()->email }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <div class="text-xs font-bold text-gray-400">Signed in as</div>
                            <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user-circle ml-2 text-gray-400"></i>
                            Profile
                        </x-dropdown-link>
                        @if(auth()->user()->isAdmin())
                            <x-dropdown-link :href="route('account.usage')">
                                <i class="fas fa-chart-pie ml-2 text-gray-400"></i>
                                Account Usage
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('api-keys.index')">
                                <i class="fas fa-key ml-2 text-gray-400"></i>
                                API Keys
                            </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt ml-2 text-red-400"></i>
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-primary-600 hover:bg-primary-50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-3 pb-4 space-y-1 px-4">
            @if(auth()->user()->isReviewer() || auth()->user()->isViewer())
                <a href="{{ route('medical-auditing.index') }}" class="responsive-nav-link-modern {{ request()->routeIs('medical-auditing.*') || request()->routeIs('reviewer.forms.submissions') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                    <i class="fas fa-clipboard-check w-5"></i> Auditing
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="responsive-nav-link-modern {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>
                <a href="{{ route('forms.index') }}" class="responsive-nav-link-modern {{ request()->routeIs('forms.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                    <i class="fas fa-file-medical w-5"></i> Forms
                </a>
                <a href="{{ route('medical-auditing.index') }}" class="responsive-nav-link-modern {{ request()->routeIs('medical-auditing.*') || request()->routeIs('reviewer.forms.submissions') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                    <i class="fas fa-clipboard-check w-5"></i> Auditing
                </a>
                <a href="{{ route('reviewer.assignment') }}" class="responsive-nav-link-modern {{ request()->routeIs('reviewer.assignment') ? 'bg-primary-50 text-primary-600' : 'text-gray-700' }}">
                    <i class="fas fa-user-plus w-5"></i> Reviewer Assignment
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-100 px-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}" class="responsive-nav-link-modern text-gray-700">
                    <i class="fas fa-user-circle w-5"></i> Profile
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="responsive-nav-link-modern text-red-600">
                        <i class="fas fa-sign-out-alt w-5"></i> Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border-bottom: 2px solid transparent;
    }
    .nav-link-modern:hover {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.08);
    }
    .nav-active {
        color: #2563eb !important;
        border-bottom: 2px solid #2563eb;
        background: rgba(37, 99, 235, 0.05);
    }
    .responsive-nav-link-modern {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .responsive-nav-link-modern:hover {
        background: rgba(37, 99, 235, 0.08);
    }
</style>
