@props(['name', 'title', 'width' => 'md:w-[500px]'])

<div x-show="{{ $name }}" style="display: none;" class="relative z-60">

    <div x-show="{{ $name }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="{{ $name }} = false">
    </div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">

                <div x-show="{{ $name }}" x-transition:enter="transition transform ease-out duration-300"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen {{ $width }}">

                    <div class="flex h-full flex-col bg-white shadow-xl">

                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
                            <h3 class="text-xl font-bold text-dark">{{ $title }}</h3>
                            <button @click="{{ $name }} = false" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6">
                            {{ $slot }}
                        </div>

                        @if (isset($footer))
                            <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                                {{ $footer }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>