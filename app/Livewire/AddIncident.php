<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AddIncident extends Component
{
    public $show = false;

    public $location_data;

    public $categories;

    public Category $category;

    public $categoryId = '';

    public bool $rescue_involved = false;

    public bool $fire_involved = false;

    public bool $police_involved = false;

    public string $incidentArea = '';

    public int $peopleInvolved = 1;

    public string $incidentDescription = '';

    public string $measures = '';

    public $incidentDate;

    public $incidentTime;

    public int $reportedById;

    public function mount(): void
    {
        abort_unless(Gate::allows('work', Auth::user()), 403);
        $this->reportedById = Auth::user()->id;
        $this->setDateTimeNow();
        $this->categories = $this->getCategories();
    }

    private function setDateTimeNow(): void
    {
        $this->incidentDate = Carbon::now()->format('Y-m-d');
        $this->incidentTime = Carbon::now()->format('H:i');
    }

    public function getCategories(): Collection
    {
        return Category::where(function ($query) {
            $query->where('customer_id', $this->location_data->customer->id)
                ->orWhere('location_id', $this->location_data->id);
        })->orWhere(function ($query) {
            $query->whereNull('customer_id')
                ->whereNull('location_id');
        })->get();
    }

    public function render()
    {
        return view('livewire.add-incident');
    }

    public function save(): void
    {
        abort_unless(Auth::user()->can('create-journal', $this->location_data), 403);
        $dateTime = Carbon::parse($this->incidentDate.' '.$this->incidentTime.':00')->toDateTimeString();
        Journal::create([
            'location_id' => $this->location_data->id,
            'category_id' => $this->pull('categoryId'),
            'description' => $this->pull('incidentDescription'),
            'measures' => $this->pull('measures'),
            'reported_by' => $this->pull('reportedById'),
            'entry_by' => Auth::user()->id,
            'area' => $this->pull('incidentArea'),
            'involved' => $this->pull('peopleInvolved'),
            'rescue_involved' => $this->pull('rescue_involved'),
            'fire_involved' => $this->pull('fire_involved'),
            'police_involved' => $this->pull('police_involved'),
            'incident_time' => $dateTime,
        ]);

        $this->reset('category');
        $this->reset('show');
        $this->dispatch('added');
    }

    public function updatedCategoryId(): void
    {
        $this->category = Category::find($this->categoryId);
        $this->peopleInvolved = $this->category->usually_involved;
    }
}
