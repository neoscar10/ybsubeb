<?php

namespace App\Livewire\Principal\School;

use App\Models\NeedsAssessment;
use App\Models\NeedsItem;
use App\Models\School;
use App\Models\SchoolStaff;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowSchoolPage extends Component
{
    public $noSchoolAssigned = false;

    // Modals visibility
    public bool $showBasicModal = false;
    public bool $showInfraModal = false;

    // Form states
    public $basic = [
        'name' => '',
        'school_type' => '',
        'ownership' => '',
        'address' => '',
        'lga' => '',
        'ward' => '',
        'community' => '',
    ];

    public $infra = [
        'has_water' => false,
        'has_toilets' => false,
        'has_electricity' => false,
        'status' => 'active',
        'total_classes' => 0,
    ];

    protected $rules = [
        'basic.name' => 'required|string|max:255',
        'basic.school_type' => 'required|string',
        'basic.ownership' => 'required|string',
        'basic.address' => 'nullable|string',
        'basic.lga' => 'required|string',
        'basic.ward' => 'nullable|string',
        'basic.community' => 'nullable|string',

        'infra.has_water' => 'boolean',
        'infra.has_toilets' => 'boolean',
        'infra.has_electricity' => 'boolean',
        'infra.status' => 'required|string|in:active,inactive',
        'infra.total_classes' => 'required|integer|min:0',
    ];

    public function openBasicModal()
    {
        $school = $this->getSchool();
        if ($school) {
            $this->basic = [
                'name' => $school->name,
                'school_type' => $school->school_type,
                'ownership' => $school->ownership,
                'address' => $school->address,
                'lga' => $school->lga,
                'ward' => $school->ward,
                'community' => $school->community,
            ];
            $this->showBasicModal = true;
        }
    }

    public function saveBasic()
    {
        $this->validate([
            'basic.name' => 'required|string|max:255',
            'basic.school_type' => 'required|string',
            'basic.ownership' => 'required|string',
            'basic.address' => 'nullable|string',
            'basic.lga' => 'required|string',
            'basic.ward' => 'nullable|string',
            'basic.community' => 'nullable|string',
        ]);

        $school = $this->getSchool();
        if ($school) {
            $school->update([
                'name' => $this->basic['name'],
                'school_type' => $this->basic['school_type'],
                'ownership' => $this->basic['ownership'],
                'address' => $this->basic['address'],
                'lga' => $this->basic['lga'],
                'ward' => $this->basic['ward'],
                'community' => $this->basic['community'],
                'last_updated_by' => Auth::id(),
                'last_updated_at' => now(),
            ]);

            $this->showBasicModal = false;
            $this->dispatch('swal:success', [
                'title' => 'Updated!',
                'text'  => 'School basic information has been saved.'
            ]);
        }
    }

    public function openInfraModal()
    {
        $school = $this->getSchool();
        if ($school) {
            $this->infra = [
                'has_water' => (bool)$school->has_water,
                'has_toilets' => (bool)$school->has_toilets,
                'has_electricity' => (bool)$school->has_electricity,
                'status' => $school->status ?? 'active',
                'total_classes' => $school->total_classes ?? 0,
            ];
            $this->showInfraModal = true;
        }
    }

    public function saveInfra()
    {
        $this->validate([
            'infra.has_water' => 'boolean',
            'infra.has_toilets' => 'boolean',
            'infra.has_electricity' => 'boolean',
            'infra.status' => 'required|string|in:active,inactive',
            'infra.total_classes' => 'required|integer|min:0',
        ]);

        $school = $this->getSchool();
        if ($school) {
            $school->update([
                'has_water' => $this->infra['has_water'],
                'has_toilets' => $this->infra['has_toilets'],
                'has_electricity' => $this->infra['has_electricity'],
                'status' => $this->infra['status'],
                'total_classes' => $this->infra['total_classes'],
                'last_updated_by' => Auth::id(),
                'last_updated_at' => now(),
            ]);

            $this->showInfraModal = false;
            $this->dispatch('swal:success', [
                'title' => 'Updated!',
                'text'  => 'Infrastructure and Status have been saved.'
            ]);
        }
    }

    public function resetModals()
    {
        $this->showBasicModal = false;
        $this->showInfraModal = false;
    }

    private function getSchool()
    {
        $user = Auth::user();
        if (!$user->school_id) return null;
        return School::find($user->school_id);
    }

    // We'll pass all data via render() instead of public properties to avoid excessive serialization payload if not needed for reactivity, 
    // but building arrays/objects in mount or render is fine. Since this is read-only, passing it to the view from render is cleaner.

    public function render()
    {
        $user = Auth::user();

        if (!$user->school_id) {
            return view('livewire.principal.school.show-school-page', [
                'noSchoolAssigned' => true,
            ]);
        }

        // Fetch School with relationship counts
        $school = School::withCount([
            'students as total_students' => fn($q) => $q->where('status', 'active'),
            'students as male_students' => fn($q) => $q->where('status', 'active')->where('gender', 'male'),
            'students as female_students' => fn($q) => $q->where('status', 'active')->where('gender', 'female'),
            'staff as total_staff' => fn($q) => $q->where('is_active', true)
        ])->find($user->school_id);

        if (!$school) {
            return view('livewire.principal.school.show-school-page', [
                'noSchoolAssigned' => true,
            ]);
        }

        // Additional queries
        $maleStaff = SchoolStaff::where('school_id', $school->id)->where('is_active', true)->where('gender', 'Male')->count();
        $femaleStaff = SchoolStaff::where('school_id', $school->id)->where('is_active', true)->where('gender', 'Female')->count();

        // Needs Assessment Snapshot
        $needs = NeedsAssessment::where('school_id', $school->id)->where('status', '!=', 'draft')->get();
        $pendingNeeds = $needs->where('status', 'under_review')->count();
        $approvedNeeds = $needs->where('status', 'approved')->count();
        $rejectedNeeds = $needs->where('status', 'rejected')->count();
        
        $lastSubmit = $needs->whereNotNull('submitted_at')->sortByDesc('submitted_at')->first();
        $lastSubmissionDate = $lastSubmit ? $lastSubmit->submitted_at : null;

        $recentNeeds = NeedsItem::whereHas('assessment', function($q) use ($school) {
            $q->where('school_id', $school->id)->where('status', '!=', 'draft');
        })->with('assessment')->latest()->take(5)->get();

        return view('livewire.principal.school.show-school-page', [
            'noSchoolAssigned' => false,
            'school' => $school,
            'user' => $user,
            'maleStaff' => $maleStaff,
            'femaleStaff' => $femaleStaff,
            'pendingNeeds' => $pendingNeeds,
            'approvedNeeds' => $approvedNeeds,
            'rejectedNeeds' => $rejectedNeeds,
            'lastSubmissionDate' => $lastSubmissionDate,
            'recentNeeds' => $recentNeeds,
        ]);
    }
}
