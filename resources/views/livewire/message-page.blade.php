<div class="max-w-4xl px-4 py-2 mx-auto mt-16 lg:px-2 mb-4 font-space-grotesk">
    <div class="text-6xl font-semibold mb-16 text-slate-200">
        TV Messenger
    </div>
    <form wire:submit.prevent="sendMessage">
        <div class="space-y-4 mb-12">
            <span class="text-xl block text-slate-400">Choose device</span>
            <select wire:model="selectedTV"
                class="border w-full text-3xl rounded bg-slate-200 mb-4 px-2 py-2 hover:ring-2 hover:ring-green-600 hover:ring-offset-4 hover:ring-offset-slate-800 focus:outline-0">
                @foreach ($tvList as $key => $val)
                <option value="{{ $key }}">{{ $val['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="space-y-4 mb-20">
            <span class="text-xl block text-slate-400">Enter text to send to TV</span>
            <input wire:model.defer="messageToSend" name="messageToSend"
                class="border w-full text-3xl rounded bg-slate-200 px-3 py-2 hover:ring-2 hover:ring-green-600 hover:ring-offset-4 hover:ring-offset-slate-800
               active:ring-2 active:ring-green-600 active:ring-offset-4 active:ring-offset-slate-800 focus:ring-2 focus:ring-green-600 focus:ring-offset-4 focus:ring-offset-slate-800 focus:outline-0"
                placeholder="e.g. Lili is awake" />
            @error('messageToSend') <div x-data="{ show: true }" x-show="show"
                x-init="setTimeout(() => show = false, 3000)" class="text-lg block text-red-400 alert">{{ $message }}
            </div>@enderror
        </div>

        <div class="space-y-4 mb-20">
            <span class="text-xl block text-slate-400">How many times to send</span>
            <div class="flex justify-between">
                <button
                    class="px-8 py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 1 ? 'bg-slate-400' : 'bg-slate-500' }}"
                    type="button" wire:click="sendTimes(1)">1</button>
                <button
                    class="px-8 py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 2 ? 'bg-slate-400' : 'bg-slate-500' }}"
                    type="button" wire:click="sendTimes(2)">2</button>
                <button
                    class="px-8 py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 3 ? 'bg-slate-400' : 'bg-slate-500' }}"
                    type="button" wire:click="sendTimes(3)">3</button>
                <button
                    class="px-8 py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 4 ? 'bg-slate-400' : 'bg-slate-500' }}"
                    type="button" wire:click="sendTimes(4)">4</button>
                <button
                    class="px-8 py-8 rounded border border-slate-200 transition text-slate-200 {{ $sendNumOfTimes == 5 ? 'bg-slate-400' : 'bg-slate-500' }}"
                    type="button" wire:click="sendTimes(5)">5</button>
            </div>
        </div>

        <button type="submit"
            class="block rounded text-slate-300 border border-slate-400 px-8 py-4 w-full font-bold transition bg-slate-700 hover:bg-slate-600  hover:border-slate-300 active:bg-slate-600  active:border-slate-300 text-4xl">
            Send message to TV
        </button>
        @error('messageToSend') <span class="text-lg block text-red-400 alert">{{ $errorWhenSending }}</span>@enderror

    </form>
</div>