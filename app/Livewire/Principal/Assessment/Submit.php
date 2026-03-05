<?php

namespace App\Livewire\Principal\Assessment;

use App\Models\AssessmentWindow;
use App\Models\NeedsAssessment;
use App\Models\NeedsItem;
use App\Models\NeedsAttachment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Submit extends Component
{
    use WithFileUploads;

    public $window;
    public $assessment;
    public $school;
    
    // Window State
    public $noWindowOpen = false;
    public $windowClosed = false;
    public $noSchoolLinked = false;

    // Item Form
    public $showItemModal = false;
    public $itemId;
    public $excludeItemIds = [];
    
    public $category;
    public $title;
    public $description;
    public $quantity = 1;
    public $unit;
    public $priority = 'medium';
    public $estimated_cost;
    public $justification;

    // Attachments
    public $showAttachmentModal = false;
    public $attachmentItem; // Item we are attaching to (null for assessment-level if needed, but per-item is better)
    public $newAttachments = []; // Array of UploadedFile
    public $tempAttachments = []; // For display before save (if needed, but simpler to save direct)

    protected $rules = [
        'category' => 'required|string',
        'title' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'priority' => 'required|in:low,medium,high,critical',
        'estimated_cost' => 'nullable|numeric|min:0',
        'unit' => 'nullable|string|max:50',
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user->school_id) {
            $this->noSchoolLinked = true;
            return;
        }

        $this->school = $user->school;

        // Check for active window
        $this->window = AssessmentWindow::where('status', 'open')->latest()->first();

        if (!$this->window) {
            // Check if there was a recent closed one to show read-only? 
            // For now, simple "No Window" state.
            $this->noWindowOpen = true;
            return;
        }

        // Check dates
        $now = now();
        if ($now < $this->window->opens_at || $now > $this->window->closes_at) {
            $this->windowClosed = true; // Shows "Outside submission period"
        }

        // Find or Create Draft Assessment
        $this->assessment = NeedsAssessment::firstOrCreate(
            [
                'assessment_window_id' => $this->window->id,
                'school_id' => $this->school->id,
            ],
            [
                'submitted_by' => $user->id, // Initial owner
                'status' => 'draft',
            ]
        );
    }

    // --- Item CRUD ---

    public function openItemModal($id = null)
    {
        $this->resetValidation();
        $this->resetItemForm();

        if ($id) {
            $item = NeedsItem::find($id);
            if ($item && $item->needs_assessment_id == $this->assessment->id) {
                $this->itemId = $item->id;
                $this->category = $item->category;
                $this->title = $item->title;
                $this->description = $item->description;
                $this->quantity = $item->quantity;
                $this->unit = $item->unit;
                $this->priority = $item->priority;
                $this->estimated_cost = $item->estimated_cost;
                $this->justification = $item->justification;
            }
        }

        $this->showItemModal = true;
        // Dispatch UI event for modal (Velzon uses Bootstrap)
        $this->dispatch('open-item-modal');
    }

    public function resetItemForm()
    {
        $this->itemId = null;
        $this->category = 'infrastructure'; // Default
        $this->title = '';
        $this->description = '';
        $this->quantity = 1;
        $this->unit = '';
        $this->priority = 'medium';
        $this->estimated_cost = null;
        $this->justification = '';
        $this->newAttachments = []; // Reset attachments
    }

    public function saveItem()
    {
        $this->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|in:low,medium,high,critical',
            'estimated_cost' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'newAttachments.*' => 'file|max:10240', // 10MB limit
        ]);

        if ($this->assessment->status !== 'draft') {
            return; // Guard
        }

        $item = NeedsItem::updateOrCreate(
            ['id' => $this->itemId],
            [
                'needs_assessment_id' => $this->assessment->id,
                'category' => $this->category,
                'title' => $this->title,
                'description' => $this->description,
                'quantity' => $this->quantity,
                'unit' => $this->unit,
                'priority' => $this->priority,
                'estimated_cost' => $this->estimated_cost,
                'justification' => $this->justification,
                'status' => 'pending'
            ]
        );

        // Handle Attachments
        if (!empty($this->newAttachments)) {
            $windowId = $this->window->id;
            $schoolId = $this->school->id;
            
            foreach ($this->newAttachments as $file) {
                // Structured path: needs/window_ID/school_ID/items/item_ID/
                $path = $file->store("needs/{$windowId}/{$schoolId}/items/{$item->id}", 'public');
                
                NeedsAttachment::create([
                    'needs_item_id' => $item->id,
                    'needs_assessment_id' => $this->assessment->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        $this->showItemModal = false;
        $this->dispatch('close-item-modal');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Item saved successfully',
        ]);
        
        $this->resetItemForm(); // Clear form and attachments
        $this->assessment->refresh(); // Refresh relations
    }

    public function deleteItem($id)
    {
        if ($this->assessment->status !== 'draft') return; // Guard

        $item = NeedsItem::find($id);
        if ($item && $item->needs_assessment_id == $this->assessment->id) {
            $item->delete();
            $this->assessment->refresh();
            $this->dispatch('swal:toast', [
                'type' => 'success',
                'title' => 'Item deleted',
            ]);
        }
    }

    // --- Attachments ---

    public function openAttachmentModal($itemId)
    {
        $this->attachmentItem = NeedsItem::find($itemId);
        $this->newAttachments = [];
        $this->showAttachmentModal = true;
        $this->dispatch('open-attachment-modal');
    }

    public function saveAttachments()
    {
        $this->validate([
            'newAttachments.*' => 'file|max:10240', // 10MB limit
        ]);

        foreach ($this->newAttachments as $file) {
            $path = $file->store('needs', 'public');
            
            NeedsAttachment::create([
                'needs_item_id' => $this->attachmentItem->id,
                'needs_assessment_id' => $this->assessment->id,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
        }

        $this->newAttachments = [];
        $this->showAttachmentModal = false;
        $this->dispatch('close-attachment-modal');
        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Attachments uploaded',
        ]);
    }
    
    public function removeAttachment($id)
    {
         if ($this->assessment->status !== 'draft') return; // Guard

         $att = NeedsAttachment::find($id);
         if ($att && $att->uploaded_by == Auth::id()) { // Simple owner check
             // Technically should delete from storage too
             // Storage::disk('public')->delete($att->file_path);
             $att->delete();
             $this->dispatch('swal:toast', [ // Alert
                'type' => 'success',
                'title' => 'Attachment removed',
             ]);
         }
    }

    public function removeNewAttachment($index)
    {
        if ($this->assessment->status !== 'draft') return; // Guard

        if (isset($this->newAttachments[$index])) {
            unset($this->newAttachments[$index]);
            $this->newAttachments = array_values($this->newAttachments);
        }
    }

    // --- Submission ---

    public function confirmSubmit()
    {
        // Validation check
        if ($this->assessment->items()->count() == 0) {
            $this->dispatch('swal:error', [
                'title' => 'Empty Assessment',
                'text' => 'You must add at least one item before submitting.'
            ]);
            return;
        }

        $this->dispatch('confirm-submit-modal');
    }

    public function submit()
    {
        // Final guard
         if ($this->assessment->items()->count() == 0) return;

         $this->assessment->update([
             'status' => 'submitted',
             'submitted_at' => now(),
         ]);

         $this->dispatch('swal:success', [
             'title' => 'Submitted Successfully',
             'text' => 'Your needs assessment has been submitted to the board.',
         ]);
    }

    public function render()
    {
        return view('livewire.principal.assessment.submit', [
            'items' => $this->assessment ? $this->assessment->items()->with('attachments')->latest()->get() : [],
        ]);
    }
}
