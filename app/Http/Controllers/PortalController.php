<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopesByRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    use ScopesByRole;

    private const DIRECTORY_TYPES = ['lecturers', 'practitioners', 'partners'];
    private const ADMIN_TYPES = ['users', 'master', 'audit', 'recap'];
    private const ROLES = ['super_admin', 'admin_pijar_suar', 'admin_kerma', 'kaprodi', 'dosen'];

    /* ------------------------------------------------------------------
     | Pengajuan
     * ------------------------------------------------------------------ */

    public function submissions(Request $request)
    {
        $query = $this->scopedByRole(DB::table('submissions'), $request);

        if ($request->user()->role === 'admin_kerma') {
            $query->where('approval_status', 'approved');
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = '%' . $request->q . '%';

            $query->where(fn ($x) => $x
                ->where('course_name', 'like', $keyword)
                ->orWhere('code', 'like', $keyword));
        }

        return view('portal.submissions', [
            'items' => $query->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function createSubmission()
    {
        return view('portal.submission-form', [
            'lecturers' => DB::table('lecturers')->where('is_active', true)->get(),
        ]);
    }

    public function storeSubmission(Request $request)
    {
        $data = $request->validate([
            'program_type'               => ['required', Rule::in(['PIJAR', 'SUAR'])],
            'course_name'                => 'required|max:150',
            'course_code'                => 'required|max:30',
            'study_program'              => 'required|max:100',
            'lecturer_id'                => 'required|integer',
            'credits'                    => 'required|integer|min:1|max:8',
            'topic'                      => 'required',
            'practitioner_competency'    => 'required',
            'practitioner_qualification' => 'required',
            'partner_needs'              => 'nullable',
            'estimated_schedule'         => 'nullable|date',
            'notes'                      => 'nullable',
        ]);

        $data += [
            'code'                => 'SUB-' . now()->format('YmdHis'),
            'approval_status'     => 'submitted',
            'practitioner_status' => 'not_assigned',
            'partner_status'      => 'not_assigned',
            'submitted_by'        => $request->user()->id,
            'created_at'          => now(),
            'updated_at'          => now(),
        ];

        DB::table('submissions')->insert($data);

        return redirect()
            ->route('portal.submissions')
            ->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function showSubmission(Request $request, int $id)
    {
        $item = $this->scopedByRole(DB::table('submissions'), $request)
            ->where('id', $id)
            ->first();

        abort_unless($item, 404);

        return view('portal.submission-detail', [
            'item'          => $item,
            'practitioners' => DB::table('practitioners')->where('is_active', true)->get(),
            'partners'      => DB::table('partners')->where('is_active', true)->get(),
        ]);
    }

    public function decide(Request $request, int $id)
    {
        $data = $request->validate([
            'approval_status' => ['required', Rule::in(['approved', 'rejected', 'revision_required'])],
            'decision_note'   => 'required|min:5',
        ]);

        DB::table('submissions')->where('id', $id)->update($data + [
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Keputusan tersimpan.');
    }

    public function assign(Request $request, int $id)
    {
        $data = $request->validate([
            'kind'        => ['required', Rule::in(['practitioner', 'partner'])],
            'assignee_id' => 'required|integer',
        ]);

        $submission = DB::table('submissions')->find($id);

        abort_unless($submission && $submission->approval_status === 'approved', 422);

        if ($data['kind'] === 'practitioner') {
            abort_unless($submission->program_type === 'SUAR', 422);
        }

        DB::table('submissions')->where('id', $id)->update([
            $data['kind'] . '_id'     => $data['assignee_id'],
            $data['kind'] . '_status' => 'assigned',
            'updated_at'              => now(),
        ]);

        return back()->with('success', 'Penugasan tersimpan.');
    }

    /* ------------------------------------------------------------------
     | Data master (dosen, praktisi, mitra)
     * ------------------------------------------------------------------ */

    public function directory(Request $request, string $type)
    {
        abort_unless(in_array($type, self::DIRECTORY_TYPES, true), 404);

        return view('portal.directory', [
            'type'  => $type,
            'items' => DB::table($type)->orderBy('name')->get(),
        ]);
    }

    public function saveDirectory(Request $request, string $type, ?int $id = null)
    {
        abort_unless(in_array($type, self::DIRECTORY_TYPES, true), 404);

        $rules = [
            'name'          => 'required|max:150',
            'institution'   => 'nullable|max:150',
            'study_program' => 'nullable|max:100',
            'position'      => 'nullable|max:100',
            'competencies'  => 'nullable',
            'qualification' => 'nullable',
            'expertise'     => 'nullable',
            'contact'       => 'nullable|max:150',
            'industry'      => 'nullable|max:150',
            'contact_name'  => 'nullable|max:150',
        ];

        $allowedFields = [
            'lecturers'     => ['name', 'institution', 'study_program', 'contact'],
            'practitioners' => ['name', 'institution', 'position', 'competencies', 'qualification', 'expertise', 'contact'],
            'partners'      => ['name', 'institution', 'industry', 'contact_name', 'contact'],
        ][$type];

        $data = collect($request->validate($rules))
            ->only($allowedFields)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();

        $data['is_active'] = $request->boolean('is_active', true);
        $data['updated_at'] = now();

        if ($id) {
            DB::table($type)->where('id', $id)->update($data);
        } else {
            DB::table($type)->insert($data + ['created_at' => now()]);
        }

        return back()->with('success', 'Data berhasil disimpan.');
    }

    /* ------------------------------------------------------------------
     | Jadwal
     * ------------------------------------------------------------------ */

    public function schedules()
    {
        return view('portal.schedules', [
            'items' => DB::table('schedules')->orderBy('date')->get(),
        ]);
    }

    /* ------------------------------------------------------------------
     | Bahan mengajar
     * ------------------------------------------------------------------ */

    public function materials(Request $request)
    {
        $query = DB::table('submissions')->where('approval_status', 'approved');

        $this->scopedByRole($query, $request);

        $items = $query
            ->leftJoin('teaching_materials', 'submissions.id', '=', 'teaching_materials.submission_id')
            ->select(
                'submissions.*',
                'teaching_materials.status as material_status',
                'teaching_materials.id as material_id'
            )
            ->get();

        return view('portal.materials', ['items' => $items]);
    }

    public function editMaterial(Request $request, int $submission)
    {
        $item = $this->scopedByRole(DB::table('submissions'), $request)
            ->where('id', $submission)
            ->where('approval_status', 'approved')
            ->first();

        abort_unless($item, 404);

        return view('portal.material-form', [
            'submission' => $item,
            'material'   => DB::table('teaching_materials')->where('submission_id', $submission)->first(),
        ]);
    }

    public function saveMaterial(Request $request, int $submission)
    {
        $exists = $this->scopedByRole(DB::table('submissions'), $request)
            ->where('id', $submission)
            ->where('approval_status', 'approved')
            ->exists();

        abort_unless($exists, 404);

        $data = $request->validate([
            'lesson_plan_url'  => 'required|url',
            'presentation_url' => 'required|url',
            'assignment_url'   => 'required|url',
            'reference_url'    => 'required|url',
            'notes'            => 'nullable',
        ]);

        $data += [
            'submission_id' => $submission,
            'status'        => $request->has('submit') ? 'submitted' : 'draft',
            'updated_by'    => $request->user()->name,
            'updated_at'    => now(),
        ];

        DB::table('teaching_materials')->updateOrInsert(
            ['submission_id' => $submission],
            $data + ['created_at' => now()]
        );

        return redirect()
            ->route('portal.materials')
            ->with('success', 'Bahan mengajar tersimpan.');
    }

    /* ------------------------------------------------------------------
     | Dokumen
     * ------------------------------------------------------------------ */

    public function documents()
    {
        return view('portal.documents', [
            'items' => DB::table('documents')->latest()->get(),
        ]);
    }

    public function storeDocument(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|max:150',
            'category'    => 'required|max:80',
            'drive_url'   => 'required|url',
            'description' => 'nullable',
        ]);

        DB::table('documents')->insert($data + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Dokumen ditambahkan.');
    }

    /* ------------------------------------------------------------------
     | Administrasi (super admin)
     * ------------------------------------------------------------------ */

    public function administration(string $type)
    {
        abort_unless(in_array($type, self::ADMIN_TYPES, true), 404);

        $items = match ($type) {
            'users'  => DB::table('users')->orderBy('name')->get(),
            'master' => DB::table('master_data')->orderBy('category')->get(),
            'audit'  => DB::table('audit_logs')->latest()->limit(100)->get(),
            default  => DB::table('submissions')
                ->selectRaw("study_program, count(*) total, sum(approval_status = 'approved') approved")
                ->groupBy('study_program')
                ->get(),
        };

        return view('portal.administration', compact('type', 'items'));
    }

    public function saveAdministration(Request $request, string $type)
    {
        if ($type === 'users') {
            $data = $request->validate([
                'name'          => 'required|max:150',
                'email'         => 'required|email|unique:users,email',
                'password'      => 'required|min:8',
                'role'          => ['required', Rule::in(self::ROLES)],
                'study_program' => 'nullable',
                'lecturer_id'   => 'nullable|integer',
            ]);

            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = true;

            DB::table('users')->insert($data + [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($type === 'master') {
            $data = $request->validate([
                'category'    => 'required',
                'name'        => 'required',
                'description' => 'nullable',
            ]);

            DB::table('master_data')->insert($data + [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            abort(404);
        }

        DB::table('audit_logs')->insert([
            'user_id'    => $request->user()->id,
            'action'     => 'create',
            'subject'    => $type,
            'details'    => 'Data ditambahkan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Data administrasi disimpan.');
    }
}