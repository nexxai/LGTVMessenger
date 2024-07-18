<div>
    <div class="max-w-2xl px-4 py-2 mx-auto mt-8 mb-4 md:mt-16 lg:px-2 font-space-grotesk">
        <div class="flex items-center mb-16 text-4xl font-semibold md:text-6xl text-slate-200">
            <img src="/smart-tv.png" alt="Image of a TV" class="w-12 h-12 mr-8" />
            <div>TV Messenger</div>
        </div>

        @if (! empty($tvList))
        <form wire:submit.prevent="sendMessage">
            <div class="mb-12 space-y-4">
                <label class="block text-xl text-slate-400" for="tv">Choose device</label>
                <select wire:model="selectedTVIndex" id="tv"
                    class="w-full px-2 py-2 mb-4 text-3xl border rounded bg-slate-200 hover:ring-2 hover:ring-green-600 hover:ring-offset-4 hover:ring-offset-slate-800 focus:outline-0">
                    @foreach ($tvList as $key => $val)
                    <option value="{{ $key }}">{{ $val['name'] }}</option>
                    @endforeach
                </select>
            </div>

            @if ($this->precanned)
            <div class="mb-12 space-y-4 precanned">
                <label for="precanned" class="block text-xl text-slate-400">Choose a saved message to
                    send</label>
                <select wire:model="messageToSend" id="precanned"
                    class="w-full px-2 py-2 mb-4 text-2xl border rounded bg-slate-200 hover:ring-2 hover:ring-green-600 hover:ring-offset-4 hover:ring-offset-slate-800 focus:outline-0">
                    <option selected value="">Select a precanned message</option>
                    @foreach ($precanned as $key => $val)
                    <option value="{{ $val }}">{{ $val }}</option>
                    @endforeach
                </select>
                @error('precanned')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="block mb-12 text-lg text-red-400">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            <div class="mb-12 space-y-4">
                <label for="messageToSend" class="block text-xl text-slate-400">Enter text to send to TV</label>
                <input wire:model.defer="messageToSend" id="messageToSend"
                    class="w-full px-3 py-2 text-3xl border rounded bg-slate-200 hover:ring-2 hover:ring-green-600 hover:ring-offset-4 hover:ring-offset-slate-800 active:ring-2 active:ring-green-600 active:ring-offset-4 active:ring-offset-slate-800 focus:ring-2 focus:ring-green-600 focus:ring-offset-4 focus:ring-offset-slate-800 focus:outline-0 disabled:bg-slate-300 disabled:text-slate-600"
                    placeholder="e.g. Check your phone" />
                @error('messageToSend')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="block mb-12 text-lg text-red-400">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-12 space-y-4">
                <label class="block text-xl text-slate-400" for="sendNumOfTimes">How many times to send</label>
                <div class="flex justify-between" id="sendNumOfTimes">
                    <button
                        class="px-6 py-6 md:px-8 md:py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 1 ? 'bg-slate-400' : 'bg-slate-500' }}"
                        type="button" wire:click="sendTimes(1)">
                        1
                    </button>
                    <button
                        class="px-6 py-6 md:px-8 md:py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 2 ? 'bg-slate-400' : 'bg-slate-500' }}"
                        type="button" wire:click="sendTimes(2)">
                        2
                    </button>
                    <button
                        class="px-6 py-6 md:px-8 md:py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 3 ? 'bg-slate-400' : 'bg-slate-500' }}"
                        type="button" wire:click="sendTimes(3)">
                        3
                    </button>
                    <button
                        class="px-6 py-6 md:px-8 md:py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 4 ? 'bg-slate-400' : 'bg-slate-500' }}"
                        type="button" wire:click="sendTimes(4)">
                        4
                    </button>
                    <button
                        class="px-6 py-6 md:px-8 md:py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 5 ? 'bg-slate-400' : 'bg-slate-500' }}"
                        type="button" wire:click="sendTimes(5)">
                        5
                    </button>
                </div>
            </div>
            <div wire:poll>
                @if ($success)
                <template x-data="{ show: true }" x-if="show" x-init="setTimeout(() => show = false, 10000)">
                    <div class="block mb-12 text-lg text-blue-400">
                        <div>Message sent!</div>
                    </div>
                </template>
                @endif
            </div>
            <div wire:poll.20s="reset_tv_alive_status">
                @if (!$tv_alive)
                <template x-data="{ show: true }" x-if="show" x-init="setTimeout(() => show = false, 10000)">
                    <div class="block mb-12 text-lg text-red-400">
                        <div>TV not available</div>
                        <div>Could not send message</div>
                    </div>
                </template>
                @endif
            </div>
            <button type="submit"
                class="block w-full px-8 py-4 text-2xl font-bold transition border rounded md:text-4xl text-slate-300 border-slate-400 bg-slate-700 hover:bg-slate-600 hover:border-slate-300 active:bg-slate-600 active:border-slate-300 disabled:bg-slate-300 disabled:text-slate-600">
                Send message to TV
            </button>
        </form>
        @else
        <div class="mb-12 space-y-4">
            <span class="block text-xl text-red-500">No TVs configured</span>
            <div class="block text-xl text-slate-400">
                Please run
                <pre class="inline ml-4 mr-4 font-semibold text-slate-200">php artisan lg:first-time</pre>
                from the command-line to configure your first TV
            </div>
        </div>
        @endif
    </div>
</div>
