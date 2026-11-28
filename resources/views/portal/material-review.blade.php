@extends('layouts.portal') @section('title','Tinjau Laporan') @section('content')
<p class="eyebrow">TINJAU LAPORAN</p>
<h1 class="title">{{$submission->course_name}}</h1>
<section class="panel"><div class="form-grid">
@foreach(['lesson_plan_url'=>'RPS / lesson plan','presentation_url'=>'Materi presentasi','assignment_url'=>'Tugas / asesmen','reference_url'=>'Referensi pendukung'] as $k=>$l)
<div class="wide"><small class="muted">{{$l}}</small><p><a href="{{$material->$k}}" target="_blank" rel="noreferrer">{{$material->$k}}</a></p></div>
@endforeach
<div class="wide"><small class="muted">Catatan dosen</small><p><strong>{{$material->notes ?: '—'}}</strong></p></div>
<div><small class="muted">Status saat ini</small><p><span class="badge">{{$material->status}}</span></p></div>
<div><small class="muted">Terakhir diubah oleh</small><p><strong>{{$material->updated_by}}</strong></p></div>
</div></section>
@if($material->status==='submitted')
<form class="panel form-grid" method="post" action="{{route('portal.materials.review.save',$submission->id)}}">@csrf @method('PATCH')
<label><span>Keputusan</span><select name="status"><option value="complete">Lengkap (complete)</option><option value="revision_required">Perlu revisi</option></select></label>
<label class="wide"><span>Catatan (wajib jika perlu revisi)</span><textarea name="review_note">{{old('review_note')}}</textarea></label>
<div class="wide"><button class="btn primary">Simpan peninjauan</button> <a class="btn secondary" href="{{route('portal.materials')}}">Kembali</a></div>
</form>
@else
<p class="muted">Laporan ini berstatus {{$material->status}} dan tidak bisa ditinjau.</p>
@if($material->review_note)<section class="panel"><small class="muted">Catatan peninjau</small><p><strong>{{$material->review_note}}</strong></p></section>@endif
@endif
@endsection