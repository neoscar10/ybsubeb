<?php

namespace App\Livewire\Principal\Assessment;

use App\Models\AssessmentWindow;
use App\Models\NeedsAssessment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $filterWindow = '';
    public $filterStatus = '';

    // View Details Modal
    public $showDetailsModal = false;
    public $viewAssessment;

    public function render()
    {
        $user = Auth::user();
        
        if (!$user->school_id) {
            return view('livewire.principal.assessment.history', [
                'assessments' => [],
                'windows' => [],
                'noSchool' => true
            ]);
        }

        $query = NeedsAssessment::query()
            ->with(['window', 'items'])
            ->withCount(['items as items_count',
                         'items as approved_count' => fn($q) => $q->where('status', 'approved'),
                         'items as rejected_count' => fn($q) => $q->where('status', 'rejected'),
                         'items as pending_count' => fn($q) => $q->where('status', 'pending')])
            ->where('school_id', $user->school_id);

        if ($this->filterWindow) {
            $query->where('assessment_window_id', $this->filterWindow);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $assessments = $query->latest('submitted_at')->paginate(10);
        $windows = AssessmentWindow::latest()->get();

        return view('livewire.principal.assessment.history', [
            'assessments' => $assessments,
            'windows' => $windows,
            'noSchool' => false
        ]);
    }

    public function viewDetails($id)
    {
        $user = Auth::user();
        if (!$user->school_id) return;

        $this->viewAssessment = NeedsAssessment::with(['window', 'items.attachments', 'items.evaluator'])
            ->where('school_id', $user->school_id) // Strict ownership check
            ->findOrFail($id);
            
        $this->showDetailsModal = true;
        $this->dispatch('open-details-modal');
    }

    public function resetFilters()
    {
        $this->filterWindow = '';
        $this->filterStatus = '';
        $this->resetPage();
    }
}
