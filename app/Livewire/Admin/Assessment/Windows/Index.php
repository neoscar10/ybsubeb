<?php

namespace App\Livewire\Admin\Assessment\Windows;

use App\Models\AssessmentWindow;
use App\Models\NeedsAssessment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';

    // Modal State
    public $showModal = false;
    public $editMode = false;
    public $windowId;

    // Form Fields
    public $title, $opens_at, $closes_at, $status = 'draft', $note, $scope;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'note' => 'nullable|string',
            'scope' => 'nullable|string',
        ];
    }

    public function render()
    {
        $windows = AssessmentWindow::query()
            ->withCount(['assessments'])
            ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(10);

        // KPI Stats
        $totalWindows = AssessmentWindow::count();
        $openWindow = AssessmentWindow::where('status', 'open')->first();
        $receivedSubmissions = $openWindow ? $openWindow->assessments()->where('status', '!=', 'draft')->count() : 0;
        
        return view('livewire.admin.assessment.windows.index', [
            'windows' => $windows,
            'totalWindows' => $totalWindows,
            'openWindow' => $openWindow,
            'receivedSubmissions' => $receivedSubmissions,
        ]);
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['title', 'opens_at', 'closes_at', 'note', 'status', 'scope', 'windowId']);
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->editMode = true;
        $this->windowId = $id;

        $window = AssessmentWindow::findOrFail($id);
        $this->title = $window->title;
        $this->opens_at = $window->opens_at->format('Y-m-d\TH:i');
        $this->closes_at = $window->closes_at->format('Y-m-d\TH:i');
        $this->status = $window->status;
        $this->note = $window->note;
        $this->scope = $window->scope;

        $this->showModal = true;
    }

    public function store()
    {
        $this->validate();

        AssessmentWindow::create([
            'title' => $this->title,
            'opens_at' => $this->opens_at,
            'closes_at' => $this->closes_at,
            'status' => 'draft', // Always draft initially
            'note' => $this->note,
            'scope' => $this->scope,
            'created_by' => Auth::id(),
        ]);

        $this->showModal = false;
        $this->dispatch('swal:success', ['title' => 'Window Created', 'text' => 'Assessment window created successfully.']);
    }

    public function update()
    {
        $this->validate();

        $window = AssessmentWindow::findOrFail($this->windowId);
        
        // If try to open, check overlap
        if ($this->status === 'open' && $window->status !== 'open') {
             if (AssessmentWindow::where('status', 'open')->where('id', '!=', $window->id)->exists()) {
                 $this->addError('status', 'Another assessment window is already open.');
                 return;
             }
        }

        $window->update([
            'title' => $this->title,
            'opens_at' => $this->opens_at,
            'closes_at' => $this->closes_at,
            'note' => $this->note,
            'scope' => $this->scope,
        ]);

        $this->showModal = false;
        $this->dispatch('swal:success', ['title' => 'Window Updated', 'text' => 'Assessment window updated successfully.']);
    }
    
    public function toggleStatus($id, $newStatus)
    {
        $window = AssessmentWindow::findOrFail($id);
        
        if ($newStatus === 'open') {
            if (AssessmentWindow::where('status', 'open')->where('id', '!=', $id)->exists()) {
                $this->dispatch('swal:error', ['title' => 'Error', 'text' => 'Another window is already open. Close it first.']);
                return;
            }
        }

        $window->update(['status' => $newStatus]);
        $this->dispatch('swal:success', ['title' => 'Status Updated', 'text' => "Window marked as {$newStatus}."]);
    }
    
    public function delete($id)
    {
        $window = AssessmentWindow::findOrFail($id);
        if ($window->assessments()->exists()) {
             $this->dispatch('swal:error', ['title' => 'Cannot Delete', 'text' => 'This window has associated submissions.']);
             return;
        }
        
        $window->delete();
        $this->dispatch('swal:success', ['title' => 'Deleted', 'text' => 'Window deleted successfully.']);
    }
}
