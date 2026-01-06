@auth
    @if(auth()->user()->isAdmin())
        @php
            $maintenanceMode = get_setting('maintenance_mode', '0') == '1';
        @endphp
        <div class="relative bg-[color-mix(in_oklab,#7f1d1d_60%,#000000_40%)] text-white py-1.5">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 px-8">
                <div class="flex items-center gap-2.5 flex-1 min-w-0">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-xs"></i>
                    </div>
                    <p class="text-xs font-medium flex-1 min-w-0">
                        Welcome <strong class="font-bold">{{ auth()->user()->name }}{{ auth()->user()->last_name ? ' ' . auth()->user()->last_name : '' }}</strong>, you are logged in with <span class="font-semibold">admin privileges</span>. 
                        <a href="{{ route('admin.home') }}" class="ml-1 inline-flex items-center gap-1 font-bold underline-offset-2 hover:underline transition-all duration-200">
                            <span>Go to admin panel</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                        @if($maintenanceMode)
                            <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded bg-yellow-500/30 border border-yellow-400/50">
                                <i class="fas fa-tools text-[10px]"></i>
                                <span class="font-semibold">Maintenance Mode Active</span>
                            </span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline flex-shrink-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-white/20 hover:bg-white/30 transition-all duration-200 text-xs font-medium">
                        <i class="fas fa-sign-out-alt text-[10px]"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
@endauth

