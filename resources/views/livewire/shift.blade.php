<div>
    @if(auth()->user()->isOnDuty())
        <div>
            <x-dialog wire:model="show">
                <x-dialog.open>
                    <button type="button"
                            class="p-2 -mr-1 transition duration-200 rounded focus:outline-none focus:shadow-outline dark:text-white"
                            aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="grey"
                             class="h-6 w-6 dark:stroke-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </x-dialog.open>

                <x-dialog.panel>
                    <form wire:submit="endShift" class="flex flex-col gap-4">
                        <h2 class="text-2xl font-bold mb-1">{{ t('End your shift!') }}</h2>

                        <hr class="w-[75%]">

                        <div class="relative">
                            <label for="shift_end-{{ $identifier }}"
                                   class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium text-gray-900 dark:text-white dark:bg-gray-900">{{ t('shift end') }}</label>
                            <input wire:model="shift_end" type="text" name="shift_end"
                                   id="shift_end-{{ $identifier }}"
                                   required
                                   class="block text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-white"
                                   placeholder="HH:MM">
                        </div>

                        <x-dialog.footer>
                            <x-dialog.close>
                                <button type="button"
                                        class="text-center rounded-xl bg-slate-300 text-slate-800 px-6 py-2 font-semibold">
                                    Cancel
                                </button>
                            </x-dialog.close>

                            <button type="submit"
                                    class="text-center rounded-xl bg-blue-500 text-white px-6 py-2 font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                                Save
                            </button>
                        </x-dialog.footer>
                    </form>
                </x-dialog.panel>
            </x-dialog>
        </div>
    @elseif(auth()->user()->locations()->count())
        <div>
            <x-dialog wire:model="show">
                <x-dialog.open>
                    <button type="button"
                            class="p-2 -mr-1 transition duration-200 rounded focus:outline-none focus:shadow-outline dark:text-white"
                            aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="grey"
                             class="w-6 h-6 dark:stroke-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </x-dialog.open>

                <x-dialog.panel>
                    <form wire:submit="startShift" class="flex flex-col gap-4">
                        <h2 class="text-2xl font-bold mb-1">{{ t('Start your shift!') }}</h2>

                        <hr class="w-[75%]">

                        <div class="relative">
                            <label for="shift_start-{{ $identifier }}"
                                   class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium text-gray-900 dark:text-white dark:bg-gray-900">{{ t('shift start') }}</label>
                            <input wire:model="shift_start" type="text" name="shift_start"
                                   id="shift_start-{{ $identifier }}"
                                   required
                                   class="block text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-white"
                                   placeholder="HH:MM">
                        </div>

                        <div>
                            <label for="location-{{ $identifier }}"
                                   class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">{{ t('shift location') }}</label>
                            <select wire:model="shift_location" id="location-{{ $identifier }}" name="location"
                                    required
                                    class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-white">
                                <option value="">{{ t('Select your location') }}</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-dialog.footer>
                            <x-dialog.close>
                                <button type="button"
                                        class="text-center rounded-xl bg-slate-300 text-slate-800 px-6 py-2 font-semibold">
                                    Cancel
                                </button>
                            </x-dialog.close>

                            <button type="submit"
                                    class="text-center rounded-xl bg-blue-500 text-white px-6 py-2 font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                                Save
                            </button>
                        </x-dialog.footer>
                    </form>
                </x-dialog.panel>
            </x-dialog>
        </div>

    @endif
</div>
