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
                            <div wire:loading.class="opacity-50" wire:target="categoryId" class="sm:col-span-2 relative">
                                <label
                                    for="category"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                                    {{ __('Category') }}
                                </label>
                                <div class="mt-2">
                                    <select
                                        wire:model.change="categoryId"
                                        id="category" name="category"
                                        autocomplete="category"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-50 dark:bg-gray-700 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6 @error('categoryId') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                                    >
                                        <option>{{ __('Select Category') }}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('categoryId')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div wire:loading.class="opacity-50" wire:target="categoryId" class="sm:col-span-4 items-center">
                                <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Involved') }}</label>
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
                                        <input
                                            wire:model="fire_involved"
                                            id="fire_involved" name="fire_involved"
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                                        <label for="fire_involved" class="dark:text-gray-50">
                                            {{ __('Fire') }}
                                        </label>
                                    @endif
                                    @if($category && $category->police_possible)
                                        <input
                                            wire:model="police_involved"
                                            id="police_involved" name="police_involved"
                                            type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                                        <label for="police_involved" class="dark:text-gray-50">
                                            {{ __('Police') }}
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div class="sm:col-span-3 relative">
                                <label for="incidentDate" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                                    {{ __('Date') }}
                                </label>
                                <div class="mt-2">
                                    <input
                                        wire:model.lazy="incidentDate"
                                        type="date"
                                        name="incidentDate"
                                        id="incidentDate"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('incidentDate') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                                    >
                                </div>
                                @error('incidentDate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-3 relative">
                                <label
                                    for="incidentTime"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                >
                                    {{ __('Time') }}
                                </label>
                                <div class="mt-2">
                                    <input
                                        wire:model.lazy="incidentTime"
                                        type="time"
                                        name="incidentTime"
                                        id="incidentTime"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('incidentTime') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                                    >
                                </div>
                                @error('incidentTime')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-4 relative">
                                <label for="incidentArea" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                                    {{ __('Area / Location') }}
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input
                                        wire:model.lazy="incidentArea" type="text"
                                        name="incidentArea" id="incidentArea"
                                        autocomplete="incidentArea"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('incidentArea') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                                    >
                                    <div class="absolute inset-y-0 right-4 flex items-center cursor-default">
                                        <svg class="h-5 w-5 @error('incidentArea') text-red-500 @else text-transparent @enderror" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('incidentArea')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="sm:col-span-2 relative">
                                <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                                    {{ __('Persons involved') }}
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input
                                        wire:model.lazy="peopleInvolved" type="number"
                                        required
                                        name="peopleInvolved" id="peopleInvolved"
                                        class="block w-full rounded-md pl-3 pr-10 py-2 sm:text-sm placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-50 dark:bg-gray-700 @error('peopleInvolved') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                                    >

                                    <div class="absolute inset-y-0 right-4 flex items-center cursor-default">
                                        <svg class="h-5 w-5 @error('peopleInvolved') text-red-500 @else text-transparent @enderror" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('peopleInvolved')
                                    <p class="mt-2 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-span-full relative">
                                <label
                                    for="incidentDescription"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                >
                                    {{ __('Incident Description') }}
                                </label>
                                <div class="mt-2">
                                    <textarea
                                        wire:model.lazy="incidentDescription"
                                        id="incidentDescription" name="incidentDescription" rows="3"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('incidentDescription') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror">
                                    </textarea>
                                </div>
                                @error('incidentDescription')
                                    <div class="mt-3">
                                        <p class="text-sm text-red-600">
                                            {{ $message }}
                                        </p>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <p class="text-sm leading-6 text-gray-600 dark:text-gray-400">
                                            {{ __('The report about the Incident and what happened.') }}
                                        </p>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-full relative">
                                <label
                                    for="measures"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                >
                                    {{ __('Measures taken') }}
                                </label>
                                <div class="mt-2">
                                    <textarea wire:model.lazy="measures"
                                              id="measures" name="measures" rows="3"
                                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('measures') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror">
                                    </textarea>
                                </div>
                                @error('measures')
                                    <div class="mt-3">
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    </div>
                                @else
                                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                        {{ __('List of measures taken.') }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @if(is_numeric($peopleInvolved) && $peopleInvolved >= 1)
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-50">
                                {{ __('Details of the house ban') }}
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                {{ __('Personal details for issuing a house ban or trespassing notice. (optional)') }}
                            </p>

                            @foreach($participants as $participant)
                                <div wire:key="participant-{{ $loop->index }}" wire:loading.class="opacity-50" wire:target="participants.{{ $loop->index }}.date_of_birth, participants.{{ $loop->index }}.ban_until"
                                     class="{{ $loop->iteration % 2 == 0 ? 'bg-gray-200 dark:bg-gray-800' : ''}}"
                                >
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-2">
                                            <label
                                                for="lastname-{{ $loop->index }}"
                                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                            >
                                                {{ __('Lastname') }}
                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    wire:model.blur="participants.{{ $loop->index }}.lastname"
                                                    type="text"
                                                    name="lastname-{{ $loop->index }}"
                                                    id="lastname-{{ $loop->index }}"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                >
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label
                                                for="firstname-{{ $loop->index }}"
                                                class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                            >
                                                {{ __('Firstname') }}
                                            </label>
                                            <div class="mt-2">
                                                <input wire:model.blur="participants.{{ $loop->index }}.firstname" type="text" name="firstname-{{ $loop->index }}" id="firstname-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="date_of_birth-{{ $loop->index }}" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('Date of Birth') }}</label>
                                            <div class="mt-2">
                                                <input wire:model.blur="participants.{{ $loop->index }}.date_of_birth" type="date" name="participants.{{ $loop->index }}.date_of_birth" id="date_of_birth-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        @if($participants[$loop->index]['lastname'] && $participants[$loop->index]['firstname'] && $participants[$loop->index]['date_of_birth'])
                                            <div class="sm:col-span-2">
                                                <label for="incidentArea" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('house ban until') }}</label>
                                                <div class="mt-2">
                                                    <input wire:model.blur="participants.{{ $loop->index }}.ban_until" type="date" name="incidentArea" id="incidentArea" autocomplete="incidentArea" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                            <div class="sm:col-span-4 items-center">
                                                <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ __('further information') }}</label>
                                                <div class="mt-2 items-center space-x-2">
                                                    @if(isset($participants[$loop->index]['id']))
                                                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                                            {{ __('house ban since') }} {{ \Carbon\Carbon::parse($participants[$loop->index]['ban_since'])->format('d.m.Y') }}, {{ count($participants[$loop->index]['trespasses']) }}x {{ __('trespasses') }}
                                                        </p>
                                                    @else
                                                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                                            {{ __('No further information found.') }}
                                                        </p>
                                                    @endif
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
                    @endif

                    @if ($errors->any())
                        <div class="sm:col-span-4 text-center">
                            <p class="text-sm text-red-600">{{ __('The form isn\'t filled correctly!') }}</p>
                        </div>
                    @endif

                    <div wire:loading.flex wire:target="save" class="flex justify-center items-center">
                        <x-icon.spinner size="6" class="text-gray-500 dark:text-gray-800" />
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
                            class="text-center rounded-xl bg-blue-600 dark:bg-blue-800 text-white px-6 py-2 font-semibold hover:bg-blue-500 dark:hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                        Save
                    </button>
                </x-dialog.footer>
            </form>
        </x-dialog.panel>
    </x-dialog>
</div>
