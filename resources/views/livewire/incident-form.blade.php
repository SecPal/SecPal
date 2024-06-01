<form wire:submit="save">
    <div class="space-y-12">
        <div class="border-b border-gray-900/10 dark:border-gray-800/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-50">{{ t('AddIncident Report') }} - {{ $location_data->name }} - {{ $location_data->location }}</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ t('This report will be read by superiors and customers. Make sure it\'s complete and worded correctly.') }}</p>
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-5">
                    <label for="reported_by" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('reported by') }}</label>
                    <div class="mt-2">
                        <x-select-search
                            :items="$this->form->getSelectSearchUser()"
                            placeholderMessage="{{  t('Who reported the incident?') }}"
                            noMatchMessage="{{ t('Sorry, no user was found for your request.') }}"
                            itemIdKey="form.reported_by"
                        ></x-select-search>
                    </div>
                </div>
                <div wire:loading.class="opacity-50" wire:target="form.category_id" class="sm:col-span-5 relative">
                    <label
                        for="category"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                        {{ t('Category') }}
                    </label>
                    <div class="mt-2">
                        <x-select-search
                            :items="$this->form->getSelectSearchCategory()"
                            placeholderMessage="{{ t('Which category does the incident fall into?') }}"
                            noMatchMessage="{{ t('No matching category found!') }}"
                            itemIdKey="form.category_id"
                        ></x-select-search>
                    </div>
                    @error('form.category_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div wire:loading.class="opacity-50" wire:target="form.category_id" class="sm:col-span-5 items-center">
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('Involved') }}</label>
                    <div class="mt-2 items-center space-x-2">
                        @if(!$this->form->category || !$this->form->category->rescue_possible && !$this->form->category->fire_possible && !$this->form->category->police_possible)
                            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                {{ t('No involvement of the emergency services.') }}
                            </p>
                        @endif
                        @if($this->form->category && $this->form->category->rescue_possible)
                            <input
                                wire:model="form.rescue_involved"
                                id="rescue_involved" name="rescue_involved"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400"
                            >
                            <label for="rescue_involved" class="dark:text-gray-50">
                                {{ t('Rescue') }}
                            </label>
                        @endif
                        @if($this->form->category && $this->form->category->fire_possible)
                            <input
                                wire:model="form.fire_involved"
                                id="fire_involved" name="fire_involved"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                            <label for="fire_involved" class="dark:text-gray-50">
                                {{ t('Fire') }}
                            </label>
                        @endif
                        @if($this->form->category && $this->form->category->police_possible)
                            <input
                                wire:model="form.police_involved"
                                id="police_involved" name="police_involved"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 dark:bg-gray-700 focus:ring-indigo-600 dark:focus:ring-indigo-400">
                            <label for="police_involved" class="dark:text-gray-50">
                                {{ t('Police') }}
                            </label>
                        @endif
                    </div>
                </div>
                <div class="sm:col-span-3 relative">
                    <label for="incidentDate" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                        {{ t('Date') }}
                    </label>
                    <div class="mt-2">
                        <input
                            wire:model.lazy="form.incidentDate"
                            type="date"
                            name="incidentDate"
                            id="incidentDate"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('form.incidentDate') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                        >
                    </div>
                    @error('form.incidentDate')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-3 relative">
                    <label
                        for="incidentTime"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                    >
                        {{ t('Time') }}
                    </label>
                    <div class="mt-2">
                        <input
                            wire:model.lazy="form.incidentTime"
                            type="time"
                            name="incidentTime"
                            id="incidentTime"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('form.incidentTime') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                        >
                    </div>
                    @error('form.incidentTime')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-4 relative">
                    <label for="area" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                        {{ t('Area / Location') }}
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                            wire:model.lazy="form.area" type="text"
                            name="area" id="area"
                            autocomplete="area"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('form.area') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                        >
                        <div class="absolute inset-y-0 right-4 flex items-center cursor-default">
                            <svg class="h-5 w-5 @error('form.area') text-red-500 @else text-transparent @enderror" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('form.area')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2 relative">
                    <label for="involved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">
                        {{ t('Persons involved') }}
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input
                            wire:model.lazy="form.involved" type="number"
                            required
                            name="involved" id="involved"
                            class="block w-full rounded-md pl-3 pr-10 py-2 sm:text-sm placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-50 dark:bg-gray-700 @error('form.involved') text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 focus:outline-none border-red-300 @enderror"
                        >

                        <div class="absolute inset-y-0 right-4 flex items-center cursor-default">
                            <svg class="h-5 w-5 @error('form.involved') text-red-500 @else text-transparent @enderror" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    @error('form.involved')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="col-span-full relative">
                    <label
                        for="description"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                    >
                        {{ t('AddIncident Description') }}
                    </label>
                    <div class="mt-2">
                        <x-quill-editor model="form.description" />
                    </div>
                    @error('form.description')
                        <div class="mt-3">
                            <p class="text-sm text-red-600">
                                {{ $message }}
                            </p>
                        </div>
                    @else
                        <div class="mt-3">
                            <p class="text-sm leading-6 text-gray-600 dark:text-gray-400">
                                {{ t('The report about the AddIncident and what happened.') }}
                            </p>
                        </div>
                    @enderror
                </div>
                <div class="col-span-full relative">
                    <label
                        for="measures"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                    >
                        {{ t('Measures taken') }}
                    </label>
                    <div class="mt-2">
                        <x-quill-editor model="form.measures" />
                    </div>
                    @error('form.measures')
                        <div class="mt-3">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @else
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                            {{ t('List of measures taken.') }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>
        @if(is_numeric($this->form->involved) && $this->form->involved >= 1)
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base font-semibold leading-7 text-gray-900 dark:text-gray-50">
                    {{ t('Details of the house ban') }}
                </h2>
                <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">
                    {{ t('Personal details for issuing a house ban or trespassing notice. (optional)') }}
                </p>

                @foreach($this->form->participants as $participant)
                    <div wire:key="participant-{{ $loop->index }}" wire:loading.class="opacity-50" wire:target="form.participants.{{ $loop->index }}.date_of_birth, form.participants.{{ $loop->index }}.ban_until"
                         class="{{ $loop->iteration % 2 == 0 ? 'bg-gray-200 dark:bg-gray-800' : ''}}"
                    >
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <label
                                    for="lastname-{{ $loop->index }}"
                                    class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50"
                                >
                                    {{ t('Lastname') }}
                                </label>
                                <div class="mt-2">
                                    <input
                                        wire:model.blur="form.participants.{{ $loop->index }}.lastname"
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
                                    {{ t('Firstname') }}
                                </label>
                                <div class="mt-2">
                                    <input wire:model.blur="form.participants.{{ $loop->index }}.firstname" type="text" name="firstname-{{ $loop->index }}" id="firstname-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="date_of_birth-{{ $loop->index }}" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('Date of Birth') }}</label>
                                <div class="mt-2">
                                    <input wire:model.blur="form.participants.{{ $loop->index }}.date_of_birth" type="date" name="participants.{{ $loop->index }}.date_of_birth" id="date_of_birth-{{ $loop->index }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>

                            @if($this->form->participants[$loop->index]['lastname'] && $this->form->participants[$loop->index]['firstname'] && $this->form->participants[$loop->index]['date_of_birth'])
                                <div class="sm:col-span-2">
                                    <label for="participants.{{ $loop->index }}.ban_until" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('house ban until') }}</label>
                                    <div class="mt-2">
                                        <input wire:model.blur="form.participants.{{ $loop->index }}.ban_until" type="date" name="participants.{{ $loop->index }}.ban_until" id="participants.{{ $loop->index }}.ban_until" autocomplete="incidentArea" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                <div class="sm:col-span-4 items-center">
                                    <label for="peopleInvolved" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('further information') }}</label>
                                    <div class="mt-2 items-center space-x-2">
                                        @if($this->form->participants[$loop->index]['active_house_ban'])
                                            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                                {{ t('existing house ban') }}, {{ count($this->form->participants[$loop->index]['trespasses']) }} {{ np('trespasses', 'previous trespass', 'previous trespassing', count($this->form->participants[$loop->index]['trespasses'])) }}
                                            </p>
                                        @else
                                            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
                                                {{ t('No current house ban found.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="sm:col-span-4">
                                    <label for="participants.{{ $loop->index }}.street" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('Street') }}</label>
                                    <div class="mt-2">
                                        <input wire:model="form.participants.{{ $loop->index }}.street" type="text" name="participants.{{ $loop->index }}.street" id="participants.{{ $loop->index }}.street" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="participants.{{ $loop->index }}.number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('House Number') }}</label>
                                    <div class="mt-2">
                                        <input wire:model.blur="form.participants.{{ $loop->index }}.number" type="text" name="participants.{{ $loop->index }}.number" id="participants.{{ $loop->index }}.number" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="participants.{{ $loop->index }}.zipcode" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('zipcode') }}</label>
                                    <div class="mt-2">
                                        <input wire:model="form.participants.{{ $loop->index }}.zipcode" type="text" name="participants.{{ $loop->index }}.zipcode" id="participants.{{ $loop->index }}.zipcode" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    </div>
                                </div>
                                <div class="sm:col-span-4">
                                    <label for="participants.{{ $loop->index }}.city" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-50">{{ t('city') }}</label>
                                    <div class="mt-2">
                                        <input wire:model.blur="form.participants.{{ $loop->index }}.city" type="text" name="participants.{{ $loop->index }}.city" id="participants.{{ $loop->index }}.city" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:bg-gray-700 dark:text-gray-50 shadow-sm ring-1 dark:ring-0 ring-inset ring-gray-300 placeholder:text-gray-400 dark:placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-2 dark:focus:ring-inset dark:focus:ring-indigo-600 sm:text-sm sm:leading-6">
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
                <p class="text-sm text-red-600">{{ t('The form isn\'t filled correctly!') }}</p>
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
