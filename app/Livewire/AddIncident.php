<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

    public string $area = '';

    public int $involved = 1;

    public string $description = '';

    public string $measures = '';

    public $date;

    public $time;

    public int $reported_by;

    public function mount(): void
    {
        $this->reported_by = Auth::user()->id;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->time = Carbon::now()->format('H:i');

        $this->categories = $this->getCategories();
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
        //  $this->validate();

        $dateTime = Carbon::parse($this->date.' '.$this->time.':00')->toDateTimeString();

        Journal::create([
            'location_id' => $this->location_data->id,
            'category_id' => $this->categoryId,
            'description' => $this->description,
            'measures' => $this->measures,
            'reported_by' => $this->reported_by,
            'entry_by' => Auth::user()->id,
            'area' => $this->area,
            'involved' => $this->involved,
            'rescue_involved' => $this->rescue_involved,
            'fire_involved' => $this->fire_involved,
            'police_involved' => $this->police_involved,
            'incident_time' => $dateTime,
        ]);

        $this->reset('show');
        $this->dispatch('added');
    }

    public function updatedCategoryId(): void
    {
        $this->category = Category::find($this->categoryId);
        $this->involved = $this->category->usually_involved;
    }
}
