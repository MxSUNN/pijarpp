<div style="overflow:auto">
    <table>
        <thead>
            <tr>
                <th>Pengajuan</th>
                <th>Program studi</th>
                <th>Approval</th>
                <th>Praktisi</th>
                <th>Mitra</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $x)
                <tr>
                    <td>
                        <strong>{{ $x->course_name }}</strong>
                        <br>
                        <small class="muted">{{ $x->code }} · {{ $x->program_type }}</small>
                    </td>
                    <td>{{ $x->study_program }}</td>
                    <td>
                        <span class="badge">{{ str_replace('_', ' ', $x->approval_status) }}</span>
                    </td>
                    <td>{{ $x->practitioner_status }}</td>
                    <td>{{ $x->partner_status }}</td>
                    <td>
                        <a class="btn secondary" href="{{ route('portal.submissions.show', $x->id) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>