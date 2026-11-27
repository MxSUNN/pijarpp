
@extends('layouts.portal')

@section('title', 'Buat Pengajuan')

@section('content')
    <p class="eyebrow">PENGAJUAN BARU</p>

    <h1 class="title">Buat Pengajuan PIJAR–SUAR</h1>

    @php
        $user = auth()->user();
        $isKaprodi = ($user?->role ?? null) === 'kaprodi';
        $kaprodiProdi = $user?->study_program ?? '';
    @endphp

    <form class="panel form-grid"
          method="post"
          action="{{ route('portal.submissions.store') }}">

        @csrf

        <label>
            <span>Jenis program</span>
            <select name="program_type" required>
                <option value="PIJAR" {{ old('program_type') === 'PIJAR' ? 'selected' : '' }}>
                    PIJAR
                </option>
                <option value="SUAR" {{ old('program_type') === 'SUAR' ? 'selected' : '' }}>
                    SUAR
                </option>
            </select>
            @error('program_type')
                <small class="error">{{ $message }}</small>
            @enderror
        </label>

        {{-- PROGRAM STUDI --}}
        <label>
            <span>Program studi</span>
            <input
                class="input"
                value="{{ $kaprodiProdi }}"
                readonly
            >
        </label>

        {{-- COURSE DROPDOWN --}}
        <label class="wide">
            <span>Mata kuliah</span>

            <select
                class="input"
                name="course_id"
                id="course_id"
                required
            >
                <option value="">-- Pilih Mata Kuliah --</option>

                @foreach($courses as $course)
                    <option
                        value="{{ $course->id }}"
                        data-name="{{ $course->course_name }}"
                        data-code="{{ $course->course_code }}"
                        data-credits="{{ $course->credits }}"
                        {{ old('course_id') == $course->id ? 'selected' : '' }}
                    >
                        {{ $course->course_name }}
                    </option>
                @endforeach
            </select>

            @error('course_id')
                <small class="error">{{ $message }}</small>
            @enderror
        </label>

        {{-- AUTOMATIC COURSE DETAILS --}}
        <label>
            <span>Kode mata kuliah</span>
            <input
                class="input"
                type="text"
                id="course_code"
                value=""
                placeholder="Otomatis terisi"
                readonly
            >
        </label>

        <label>
            <span>SKS</span>
            <input
                class="input"
                type="text"
                id="credits"
                value=""
                placeholder="Otomatis terisi"
                readonly
            >
        </label>

        {{-- ESTIMATED SCHEDULE --}}
        <label>
            <span>Perkiraan jadwal</span>
            <input
                class="input"
                type="date"
                name="estimated_schedule"
                value="{{ old('estimated_schedule') }}"
            >
            @error('estimated_schedule')
                <small class="error">{{ $message }}</small>
            @enderror
        </label>

        {{-- LECTURER --}}
        <label>
            <span>Dosen</span>
            <select class="input" name="lecturer_id" required>
                <option value="">-- Pilih Dosen --</option>

                @foreach($lecturers as $x)
                    <option
                        value="{{ $x->id }}"
                        {{ old('lecturer_id') == $x->id ? 'selected' : '' }}
                    >
                        {{ $x->name }}
                    </option>
                @endforeach
            </select>

            @error('lecturer_id')
                <small class="error">{{ $message }}</small>
            @enderror
        </label>

        {{-- GOOGLE DRIVE LINK --}}
        <label class="wide">
            <span>Link Google Drive</span>

            <input
                class="input"
                type="url"
                name="drive_link"
                value="{{ old('drive_link') }}"
                placeholder="https://drive.google.com/drive/folders/..."
            >

            <small>
                Contoh link:
                https://drive.google.com/drive/folders/1AbCdEfGhIjKlMn
            </small>

            <small>
                Masukkan link Google Drive yang berisi dokumen RPS & SILABUS. Pastikan akses link sudah diatur agar dapat
                dibuka oleh pihak terkait.
            </small>

            @error('drive_link')
                <small class="error">{{ $message }}</small>
            @enderror
        </label>

        {{-- OTHER SUBMISSION DETAILS --}}
        @foreach([
            ['topic', 'Topik'],
            ['practitioner_competency', 'Kompetensi praktisi'],
            ['practitioner_qualification', 'Kualifikasi praktisi'],
            ['partner_needs', 'Kebutuhan mitra'],
            ['notes', 'Catatan'],
        ] as $f)
            <label class="wide">
                <span>{{ $f[1] }}</span>

                <textarea
                    name="{{ $f[0] }}"
                    @if(in_array($f[0], ['topic', 'practitioner_competency', 'practitioner_qualification']))
                        required
                    @endif
                >{{ old($f[0]) }}</textarea>

                @error($f[0])
                    <small class="error">{{ $message }}</small>
                @enderror
            </label>
        @endforeach

        <div class="wide">
            <button class="btn primary" type="submit">
                Kirim Pengajuan
            </button>
        </div>
    </form>

    {{-- AUTOMATIC COURSE INFORMATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courseSelect = document.getElementById('course_id');
            const courseCode = document.getElementById('course_code');
            const credits = document.getElementById('credits');

            function updateCourseDetails() {
                const selectedOption =
                    courseSelect.options[courseSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    courseCode.value = '';
                    credits.value = '';
                    return;
                }

                courseCode.value = selectedOption.dataset.code || '';
                credits.value = selectedOption.dataset.credits || '';
            }

            courseSelect.addEventListener('change', updateCourseDetails);

            // Restore values after validation errors
            updateCourseDetails();
        });
    </script>
@endsection
