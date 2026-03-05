<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolIndex extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $search = '';
    public $filterLga = '';
    public $filterType = '';
    public $filterStatus = '';

    // Modal State
    public $showModal = false;
    public $editMode = false;
    public $schoolId;

    // Form Fields
    public $code, $name, $school_type, $ownership = 'public', $status = 'active';
    public $phone, $email, $year_established;
    public $lga, $ward, $community, $address;
    public $latitude, $longitude;
    public $notes;
    
    // Infrastructure
    public $has_water = false, $has_toilets = false, $has_electricity = false;
    // View Modal State
    public $showViewModal = false;
    public $viewSchool; // The school model instance for viewing

    // Bulk Upload State
    public $showChoiceModal = false;
    public $showBulkModal = false;
    public $csvFile;
    public $importSummary = null;
    
    // Preview processing state
    public $hasPreview = false;
    public $headers = [];
    public $previewRows = [];
    public $totalRows = 0;
    public $validRows = 0;
    public $invalidRows = 0;
    public $rowErrors = [];
    public $stagedPath = '';
    public $importToken = '';

    // ... (existing properties)



    public function render()
    {
        $query = School::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('lga', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterLga, fn($q) => $q->where('lga', $this->filterLga))
            ->when($this->filterType, function($q) {
                // Flexible filter for "primary" vs "Primary" and "junior_secondary" vs "Junior Secondary"
                $type = $this->filterType;
                $humanType = ucwords(str_replace('_', ' ', $type));
                
                $q->where(function($sub) use ($type, $humanType) {
                    $sub->where('school_type', $type)
                        ->orWhere('school_type', $humanType);
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus));

        // KPIs
        $statsQuery = clone $query;
        $kpis = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('status', 'active')->count(),
            'inactive' => (clone $statsQuery)->where('status', '!=', 'active')->count(),
            'primary' => (clone $statsQuery)->where(fn($q) => $q->where('school_type', 'primary')->orWhere('school_type', 'Primary'))->count(),
            'jss' => (clone $statsQuery)->where(fn($q) => $q->where('school_type', 'junior_secondary')->orWhere('school_type', 'Junior Secondary'))->count(),
            'has_principal' => (clone $statsQuery)->has('principal')->count(),
        ];

        $schools = $query->withCount([
            'students as students_count' => fn($q) => $q->where('status', 'active'),
            'students as filtered_male' => fn($q) => $q->where('status', 'active')->where('gender', 'male'),
            'students as filtered_female' => fn($q) => $q->where('status', 'active')->where('gender', 'female'),
            'staff as filtered_teachers_male' => fn($q) => $q->where('is_active', true)->where('staff_type', 'teacher')->where('gender', 'male'),
            'staff as filtered_teachers_female' => fn($q) => $q->where('is_active', true)->where('staff_type', 'teacher')->where('gender', 'female'),
        ])->latest()->paginate(10);

        return view('livewire.admin.schools.school-index', [
            'schools' => $schools,
            'kpis' => $kpis,
            'lgas' => $this->getLgas(), // You'd typically pull this from a config or DB
            'types' => ['primary', 'junior_secondary', 'basic', 'special'],
        ]);
    }

    public function create()
    {
        $this->showChoiceModal = true;
    }

    public function chooseSingle()
    {
        $this->showChoiceModal = false;
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function chooseBulk()
    {
        $this->showChoiceModal = false;
        $this->resetValidation();
        $this->csvFile = null;
        $this->importSummary = null;
        $this->resetPreviewState();
        $this->showBulkModal = true;
    }

    public function resetPreviewState()
    {
        $this->hasPreview = false;
        $this->headers = [];
        $this->previewRows = [];
        $this->totalRows = 0;
        $this->validRows = 0;
        $this->invalidRows = 0;
        $this->rowErrors = [];
        $this->stagedPath = '';
        $this->importToken = '';
    }

    public function replaceFile()
    {
        $this->resetPreviewState();
        $this->csvFile = null;
    }

    public function downloadTemplate()
    {
        $headers = [
            'name', 'code', 'school_type', 'lga', 'ward', 'community', 'address',
            'has_water', 'has_toilets', 'has_electricity'
        ];
        
        $sampleRow1 = ['Damaturu Primary School', 'DAM-001', 'primary', 'Damaturu', 'Nayi-Nawa', 'Central', '123 Main St', '1', '1', '1'];
        $sampleRow2 = ['Potiskum JSS', 'POT-002', 'junior_secondary', 'Potiskum', 'Dogo Nini', 'North', '456 Market Rd', '1', '1', '0'];

        $callback = function() use ($headers, $sampleRow1, $sampleRow2) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, $sampleRow1);
            fputcsv($file, $sampleRow2);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="schools_template.csv"',
        ]);
    }

    public function stageCsvForPreview()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);

        $this->resetPreviewState();

        // Backup to a temp folder so it persists for the confirm step
        $this->stagedPath = $this->csvFile->store('csv_imports', 'local');
        $fullPath = Storage::disk('local')->path($this->stagedPath);
        
        $fileData = array_map('str_getcsv', file($fullPath));
        
        if (empty($fileData)) {
            $this->addError('csvFile', 'The CSV file is empty.');
            return;
        }

        $this->headers = array_map(function($header) {
            return trim(strtolower($header));
        }, array_shift($fileData));

        $requiredHeaders = ['name', 'lga', 'school_type'];
        foreach ($requiredHeaders as $req) {
            if (!in_array($req, $this->headers)) {
                $this->addError('csvFile', 'Invalid template. Missing required column Header: ' . $req);
                return;
            }
        }

        $this->totalRows = count($fileData);
        $previewCount = 0;
        $this->rowErrors = [];

        foreach ($fileData as $index => $row) {
            if (empty(array_filter($row))) {
                $this->totalRows--; // Ignore completely blank rows
                continue;
            }

            $rowNum = $index + 2; 

            $rowData = [];
            foreach ($this->headers as $colIndex => $colName) {
                $rowData[$colName] = isset($row[$colIndex]) ? trim($row[$colIndex]) : null;
            }

            if ($previewCount < 10) {
                $this->previewRows[] = $rowData;
                $previewCount++;
            }

            $validator = \Illuminate\Support\Facades\Validator::make($rowData, [
                'name' => 'required|string|max:255',
                'lga' => 'required|string',
                'school_type' => 'required|string',
                'code' => 'nullable|string',
            ]);

            $rowHasError = false;
            $rowErrorsList = [];

            if ($validator->fails()) {
                $rowHasError = true;
                $rowErrorsList = array_merge($rowErrorsList, $validator->errors()->all());
            }
            
            if (!empty($rowData['code'])) {
                $codeExisting = School::where('code', $rowData['code'])->first();
                if ($codeExisting) {
                    $rowHasError = true;
                    $rowErrorsList[] = "School with code '{$rowData['code']}' already exists.";
                }
            }

            $existing = School::where('name', $rowData['name'])->where('lga', $rowData['lga'])->first();
            if ($existing) {
                $rowHasError = true;
                $rowErrorsList[] = "School already exists (duplicate name and LGA).";
            }

            if ($rowHasError) {
                $this->invalidRows++;
                $this->rowErrors[] = [
                    'row' => $rowNum,
                    'errors' => $rowErrorsList
                ];
            } else {
                $this->validRows++;
            }
        }

        $this->importToken = md5(time() . uniqid());
        $this->hasPreview = true;
    }

    public function confirmImport()
    {
        if (!$this->hasPreview || !$this->importToken || !$this->stagedPath) {
            return;
        }

        $fullPath = Storage::disk('local')->path($this->stagedPath);
        if (!file_exists($fullPath)) {
            $this->addError('csvFile', 'Uploaded file was lost. Please upload again.');
            return;
        }

        $fileData = array_map('str_getcsv', file($fullPath));
        array_shift($fileData); // remove header again

        $createdCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $finalErrors = [];

        foreach ($fileData as $index => $row) {
            if (empty(array_filter($row))) continue;

            $rowNum = $index + 2; 

            $rowData = [];
            foreach ($this->headers as $colIndex => $colName) {
                $rowData[$colName] = isset($row[$colIndex]) ? trim($row[$colIndex]) : null;
            }

            $validator = \Illuminate\Support\Facades\Validator::make($rowData, [
                'name' => 'required|string|max:255',
                'lga' => 'required|string',
                'school_type' => 'required|string',
                'code' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $failedCount++;
                $finalErrors[] = ['row' => $rowNum, 'errors' => $validator->errors()->all()];
                continue;
            }
            
            if (!empty($rowData['code']) && School::where('code', $rowData['code'])->exists()) {
                $skippedCount++;
                $finalErrors[] = ['row' => $rowNum, 'errors' => ["School with code '{$rowData['code']}' already exists."]];
                continue;
            }

            if (School::where('name', $rowData['name'])->where('lga', $rowData['lga'])->exists()) {
                $skippedCount++;
                $finalErrors[] = ['row' => $rowNum, 'errors' => ["School already exists (duplicate name and LGA)."]];
                continue;
            }

            try {
                School::create([
                    'name' => $rowData['name'],
                    'code' => $rowData['code'] ?: null,
                    'school_type' => $rowData['school_type'],
                    'ownership' => 'public', 
                    'status' => 'active', 
                    'lga' => $rowData['lga'],
                    'ward' => $rowData['ward'] ?? null,
                    'community' => $rowData['community'] ?? null,
                    'address' => $rowData['address'] ?? null,
                    'has_water' => filter_var($rowData['has_water'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'has_toilets' => filter_var($rowData['has_toilets'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'has_electricity' => filter_var($rowData['has_electricity'] ?? false, FILTER_VALIDATE_BOOLEAN),
                ]);
                $createdCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $finalErrors[] = ['row' => $rowNum, 'errors' => ['System error while saving.']];
            }
        }

        $this->importSummary = [
            'created' => $createdCount,
            'skipped' => $skippedCount,
            'failed' => $failedCount,
        ];
        $this->rowErrors = $finalErrors;
        
        $this->hasPreview = false;
        $this->csvFile = null;
        $this->importToken = '';

        @unlink($fullPath);

        session()->flash('import_message', "Import complete. $createdCount created, $skippedCount skipped, $failedCount failed.");
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->editMode = true;
        $this->schoolId = $id;

        $school = School::findOrFail($id);
        $this->fill($school->only([
            'code', 'name', 'school_type', 'ownership', 'status',
            'phone', 'email', 'year_established', 'latitude', 'longitude',
            'lga', 'ward', 'community', 'address',
            'has_water', 'has_toilets', 'has_electricity', 'notes'
        ]));
        
        $this->showModal = true;
    }

    public function openViewModal($id)
    {
        $this->viewSchool = School::with('principal')->findOrFail($id);
        $this->showViewModal = true;
    }

    public function store()
    {
        $this->validateRules();

        $data = $this->prepareData();
        
        School::create($data);

        $this->showModal = false;
        session()->flash('message', 'School created successfully.');
    }

    public function update()
    {
        $this->validateRules();

        $school = School::findOrFail($this->schoolId);
        $data = $this->prepareData();
        
        // Audit update if stats changed? For now just standard update
        $school->update($data);
        
        // Update user who modified it
        $school->last_updated_by = Auth::id();
        $school->last_updated_at = now();
        $school->save();

        $this->showModal = false;
        session()->flash('message', 'School updated successfully.');
    }

    private function prepareData()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'school_type' => $this->school_type,
            'ownership' => $this->ownership,
            'status' => $this->status,
            'phone' => $this->phone,
            'email' => $this->email,
            'year_established' => $this->year_established,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'community' => $this->community,
            'address' => $this->address,
            'has_water' => $this->has_water,
            'has_toilets' => $this->has_toilets,
            'has_electricity' => $this->has_electricity,
            'notes' => $this->notes,
        ];
    }

    private function validateRules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'lga' => 'required|string',
            'school_type' => 'required|string',
            'code' => 'nullable|string|unique:schools,code,' . ($this->schoolId ?? 'NULL'),
        ];

        $this->validate($rules);
    }

    private function resetForm()
    {
        $this->reset([
            'code', 'name', 'school_type', 'ownership', 'status',
            'phone', 'email', 'year_established', 'lga', 'ward', 'community', 'address',
            'has_water', 'has_toilets', 'has_electricity', 'notes', 'schoolId'
        ]);
    }

    private function getLgas()
    {
        // Simple list for now -> In real app, maybe from a config or DB
        return [
            'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 
            'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 
            'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
        ];
    }
}
