<div>
    <x-dialog wire:model="show">
        <x-dialog.open>
            <button type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline dark:focus-visible:ring-2 dark:focus-visible:ring-offset-2 dark:focus-visible:ring-indigo-500">Add incident</button>
        </x-dialog.open>
        <x-dialog.panel>
            <form wire:submit="save">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-800/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-50">{{ __('Incident Report') }} - {{ $location_data->name }} - {{ $location_data->location }}</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">This information will be displayed publicly so be careful what you share.</p>
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-5">
                                <label for="reportedById" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('reported by') }}</label>
                                <div class="mt-2">
                                    <select wire:model="reportedById" id="reportedById" name="reportedById" autocomplete="reportedById" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-50 dark:bg-gray-700 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option disabled>{{ __('Select User') }}</option>
                                        @foreach($location_data->users as $user)
                                            <option value="{{ $user->id }}">{{ $user->lastname }}, {{ $user->firstname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="category" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Category') }}</label>
                                <div class="mt-2">
                                    <select wire:model.change="categoryId" id="category" name="category" autocomplete="category" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-50 dark:bg-gray-700 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option>{{ __('Select Category') }}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="sm:col-span-4 items-center">
                                <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Involved') }}</label>
                                <div class="mt-2 items-center space-x-2">
                                    @if(!$category || !$category->rescue_possible && !$category->fire_possible && !$category->police_possible)
                                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                            {{ __('No involvement of the emergency services.') }}
                                        </p>
                                    @endif
                                    @if($category && $category->rescue_possible)
                                        <input
                                            wire:model="rescue_involved"
                                            id="rescue_involved" name="rescue_involved"
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400"
                                        >
                                        <label for="rescue_involved" class="dark:text-gray-50">
                                            {{ __('Rescue') }}
                                        </label>
                                    @endif
                                    @if($category && $category->fire_possible)
                                        <input wire:model="fire_involved" id="fire_involved" name="fire_involved" type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                                        <label for="fire_involved" class="dark:text-gray-50">{{ __('Fire') }}</label>
                                    @endif
                                    @if($category && $category->police_possible)
                                        <input wire:model="police_involved" id="police_involved" name="police_involved" type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                                        <label for="police_involved" class="dark:text-gray-50">{{ __('Police') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <label for="incidentDate" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Date') }}</label>
                                <div class="mt-2">
                                    <input
                                        wire:model="incidentDate"
                                        type="date"
                                        name="incidentDate"
                                        id="incidentDate"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <label
                                    for="incidentTime"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                >
                                    {{ __('Time') }}
                                </label>
                                <div class="mt-2">
                                    <input
                                        wire:model="incidentTime"
                                        type="time"
                                        name="incidentTime"
                                        id="incidentTime"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    >
                                </div>
                            </div>
                            <div class="sm:col-span-4">
                                <label for="incidentArea" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Area / Location') }}</label>
                                <div class="mt-2">
                                    <input wire:model="incidentArea" type="text" name="incidentArea" id="incidentArea" autocomplete="incidentArea" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Persons involved') }}</label>
                                <div class="mt-2">
                                    <input wire:model.blur="peopleInvolved" type="text" name="peopleInvolved" id="peopleInvolved" autocomplete="peopleInvolved" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="col-span-full">
                                <label for="incidentDescription" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Incident Description') }}</label>
                                <div class="mt-2">
                                    <textarea wire:model="incidentDescription" id="incidentDescription" name="incidentDescription" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('The report about the Incident and what happened.') }}</p>
                            </div>
                            <div class="col-span-full">
                                <label for="measures" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Measures taken') }}</label>
                                <div class="mt-2">
                                    <textarea wire:model="measures" id="measures" name="measures" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('List of measures taken.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-50">{{ __('Details of the house ban') }}</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Personal details for issuing a house ban or trespassing notice. (optional)') }}</p>

                        @foreach($participants as $participant)
                            <div wire:key="participant-{{ $loop->index }}"
                                 class="{{ $loop->iteration % 2 == 0 ? 'bg-gray-200 dark:bg-gray-800' : ''}}">
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-2">
                                        <label for="lastname-{{ $loop->index }}" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Lastname') }}</label>
                                        <div class="mt-2">
                                            <input wire:model.blur="participants.{{ $loop->index }}.lastname" type="text" name="lastname-{{ $loop->index }}" id="lastname-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="firstname-{{ $loop->index }}" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Firstname') }}</label>
                                        <div class="mt-2">
                                            <input wire:model.blur="participants.{{ $loop->index }}.firstname" type="text" name="firstname-{{ $loop->index }}" id="firstname-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="dateOfBirth-{{ $loop->index }}" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Date of Birth') }}</label>
                                        <div class="mt-2">
                                            <input wire:model.blur="participants.{{ $loop->index }}.dateOfBirth" type="date" name="participants.{{ $loop->index }}.dateOfBirth" id="dateOfBirth-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>

                                    @if($participants[$loop->index]['lastname'] && $participants[$loop->index]['firstname'] && $participants[$loop->index]['dateOfBirth'])
                                        <div class="sm:col-span-2">
                                            <label for="incidentArea" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('house ban until') }}</label>
                                            <div class="mt-2">
                                                <input wire:model.blur="participants.{{ $loop->index }}.banUntil" type="date" name="incidentArea" id="incidentArea" autocomplete="incidentArea" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="sm:col-span-4 items-center">
                                            <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('further information') }}</label>
                                            <div class="mt-2 items-center space-x-2">
                                                <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                                    {{ __('No further information found.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-4">
                                            <label for="participants.{{ $loop->index }}.street" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Street') }}</label>
                                            <div class="mt-2">
                                                <input wire:model="participants.{{ $loop->index }}.street" type="text" name="participants.{{ $loop->index }}.street" id="participants.{{ $loop->index }}.street" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="participants.{{ $loop->index }}.number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('House Number') }}</label>
                                            <div class="mt-2">
                                                <input wire:model.blur="participants.{{ $loop->index }}.number" type="text" name="participants.{{ $loop->index }}.number" id="participants.{{ $loop->index }}.number" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="participants.{{ $loop->index }}.zipcode" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('zipcode') }}</label>
                                            <div class="mt-2">
                                                <input wire:model="participants.{{ $loop->index }}.zipcode" type="text" name="participants.{{ $loop->index }}.zipcode" id="participants.{{ $loop->index }}.zipcode" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="sm:col-span-4">
                                            <label for="participants.{{ $loop->index }}.city" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('city') }}</label>
                                            <div class="mt-2">
                                                <input wire:model.blur="participants.{{ $loop->index }}.city" type="text" name="participants.{{ $loop->index }}.city" id="participants.{{ $loop->index }}.city" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-dialog.footer>
                    <x-dialog.close>
                        <button type="button"
                                class="text-center rounded-xl bg-slate-300 dark:bg-gray-700 dark:text-gray-50 px-6 py-2 font-semibold">
                            Cancel
                        </button>
                    </x-dialog.close>
                    <button type="submit"
                            class="text-center rounded-xl bg-blue-500 dark:bg-blue-400 text-white px-6 py-2 font-semibold disabled:cursor-not-allowed disabled:opacity-50">
                        Save
                    </button>
                </x-dialog.footer>
            </form>
        </x-dialog.panel>
    </x-dialog>
</div>
