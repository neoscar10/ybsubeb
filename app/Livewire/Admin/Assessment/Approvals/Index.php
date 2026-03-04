<?php

namespace App\Livewire\Admin\Assessment\Approvals;

use App\Models\AssessmentWindow;
use App\Models\NeedsAssessment;
use App\Models\NeedsItem;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $filterWindow = '';
    public $filterLga = '';
    public $filterStatus = '';

    // Review Modal State
    public $showReviewModal = false;
    public $viewAssessment;
    public $adminComment;
    
    // Quick Item Decision State
    public $decisionNote = '';

    public function mount()
    {
        // Default to latest open or closed window
        $latestWindow = AssessmentWindow::latest()->first();
        if ($latestWindow) {
            $this->filterWindow = $latestWindow->id;
        }
    }

    public function render()
    {
        $query = NeedsAssessment::query()
            ->with(['school', 'window', 'submitter'])
            ->withCount(['items as total_items', 
                         'items as approved_items' => fn($q) => $q->where('status', 'approved'),
                         'items as rejected_items' => fn($q) => $q->where('status', 'rejected'),
                         'items as pending_items' => fn($q) => $q->where('status', 'pending')])
            ->when($this->filterWindow, fn($q) => $q->where('assessment_window_id', $this->filterWindow))
            ->when($this->filterLga, fn($q) => $q->whereHas('school', fn($s) => $s->where('lga', $this->filterLga)))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, function ($q) {
                $q->whereHas('school', fn($s) => $s->where('name', 'like', '%'.$this->search.'%')
                                                   ->orWhere('code', 'like', '%'.$this->search.'%'));
            })
            // Only show submitted/under_review/approved/rejected (hide draft unless specifically needed, but usually draft is invisible to admin)
            ->where('status', '!=', 'draft'); 

        $assessments = $query->latest('submitted_at')->paginate(10);
        
        $windows = AssessmentWindow::latest()->get();
        // LGAs hardcoded for now or fetch from DB config
        $lgas = [
            'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 
            'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 
            'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
        ];

        return view('livewire.admin.assessment.approvals.index', [
            'assessments' => $assessments,
            'windows' => $windows,
            'lgas' => $lgas,
        ]);
    }

    public function openReview($id)
    {
        $this->viewAssessment = NeedsAssessment::with(['school', 'items.attachments', 'items.evaluator', 'submitter'])
            ->findOrFail($id);
            
        // If status is submitted, move to under_review automatically? 
        // Optional preference, but good for tracking who is looking at it.
        if ($this->viewAssessment->status === 'submitted') {
            $this->viewAssessment->update(['status' => 'under_review']);
        }
        
        $this->adminComment = $this->viewAssessment->admin_comment;
        $this->showReviewModal = true;
    }

    public function decideItem($itemId, $decision)
    {
        $item = NeedsItem::findOrFail($itemId);
        
        // Ensure belonging to current assessment view to prevent tampering
        if ($item->needs_assessment_id !== $this->viewAssessment->id) {
            return;
        }

        $item->update([
            'status' => $decision,
            'decision_by' => Auth::id(),
            'decided_at' => now(),
            // 'decision_note' => $this->decisionNote // Could add separate note field
        ]);
        
        // Refresh view
        $this->viewAssessment->refresh();
        $this->viewAssessment->load(['items.attachments', 'items.evaluator']);
        
        $this->dispatch('swal:toast', ['type' => 'success', 'properties' => "Item marked as {$decision}"]);
    }
    
    public function bulkDecide($decision)
    {
        $this->viewAssessment->items()
            ->where('status', 'pending')
            ->update([
                'status' => $decision,
                'decision_by' => Auth::id(),
                'decided_at' => now()
            ]);
            
        $this->viewAssessment->refresh();
        $this->dispatch('swal:toast', ['type' => 'success', 'properties' => "All pending items marked as {$decision}"]);
    }

    public function saveComment()
    {
        $this->viewAssessment->update(['admin_comment' => $this->adminComment]);
        $this->dispatch('swal:toast', ['type' => 'success', 'properties' => 'Comment saved']);
    }

    public function finalizeAssessment($status)
    {
        // Check if any pending items?
        // if ($status === 'approved' && $this->viewAssessment->items()->where('status', 'pending')->exists()) { ... warning ... }

        if (in_array($status, ['approved', 'rejected'])) {
            $this->viewAssessment->items()
                ->where('status', 'pending')
                ->update([
                    'status' => $status,
                    'decision_by' => Auth::id(),
                    'decided_at' => now(),
                    // 'decision_note' => $this->adminComment // Optional propagation
                ]);
        }

        $this->viewAssessment->update([
            'status' => $status,
            'admin_comment' => $this->adminComment
        ]);
        
        $this->showReviewModal = false;
        $this->dispatch('swal:success', ['title' => 'Assessment Finalized', 'text' => "Assessment marked as {$status}."]);
    }
}
