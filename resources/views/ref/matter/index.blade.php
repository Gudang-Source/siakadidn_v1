@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="col-6">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h5>Daftar Materi Pelajaran</h5>
                            <a href="{{ route('ref.matter.store') }}" class="btn btn-primary btn-sm px-2 rounded" data-toggle="modal" data-target="#modal" data-type="add" data-title="Tambah Materi" data-method="post">
                                <i width="14" class="mr-2" data-feather="plus"></i>Tambah Materi
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Materi</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Deskripsi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $no = 1; @endphp
                                @forelse ($matters as $matter)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $matter->name }}</td>
                                        <td>{{ $matter->subjectName}}</td>
                                        <td>{{ $matter->description }}</td>
                                        <td>
                                            <div class="btn-action d-flex justify-content-around">
                                                <a title="Edit Materi" href="{{ route('ref.matter.edit', $matter->id) }}" >
                                                    <i width="14" color="#04396c" data-feather="edit"></i>
                                                </a>
                                                <a title="Delete" id="deleteData" data-toggle="modal" data-target="#deleteModal" data-id="{{ $matter->id }}" href="#" class="text-danger" data-action="{{ route('ref.matter.destroy', $matter->id) }}">
                                                    <i width="14" color="red" data-feather="trash"></i>
                                                </a>
                                                <a title="Detail Materi" href="{{ route('ref.matter.show', $matter->id) }}">
                                                    <i width="14" data-feather="chevron-right"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Delete Data Materi -->
                                @include('layouts.form-delete', [
                                    'method' => 'POST',
                                    'methodx' => 'DELETE',
                                    'bgDanger' => '',
                                    'boxConfirmHeader' => 'box-confirm-header',
                                    'textWhite' => '',
                                    'title_modal' => 'Delete Data',
                                    'showdata' => "ref.matter.show-json",
                                    'title_menu' => 'matter'])

                                @empty
                                    <tr><td colspan="4" class="text-center">Data Kosong</td></tr>
                                @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-5">
                        @if($matter_details == null and $matter_show == null)
                        <div>No Data Selected!</div>
                        @else
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h5>Sub Materi {{ $matter_show->name }}</h5>
                            <a  href="{{ route('ref.matter.sub.store') }}" 
                                class="btn btn-primary btn-sm px-2 rounded" 
                                data-toggle="modal" 
                                data-target="#modalSubMateri" 
                                data-type="add" data-title="Tambah Sub Materi {{ $matter_show->name }}"
                                data-method="post">
                                <i width="14" class="mr-2" data-feather="plus"></i>Sub Materi
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Sub Materi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @forelse($matter_details as $matter_detail)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $matter_detail->name }}</td>
                                        <td>
                                            <div class="btn-action d-flex justify-content-around">
                                                <a href="#" >
                                                    <i width="14" color="#04396c" data-feather="edit"></i>
                                                </a>
                                                <a title="Delete" id="deleteDataDetail" data-toggle="modal" data-target="#deleteModalDetail" data-id="#" href="#" class="text-danger" data-action="#">
                                                    <i width="14" color="red" data-feather="trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No data!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @include('ref.matter.sub.form')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('ref.matter.form')
@endsection

@section('js')
<script>
    $(document).ready(function() {
        //modal
        $('#modalSubMateri').on('shown.bs.modal', function(event) {
            $('#name').trigger('focus');
        });
        $('#modalSubMateri').on('show.bs.modal', function (event) {
            const target = $(event.relatedTarget);
            // cek tipe
            $('#modalSubMateriLabel').html(target.attr('data-title'));
            $('#modalSubMateri').closest('form').attr('action', target.attr('href'));
            $('#modalSubMateri').closest('form').attr('method', target.attr('data-method'));
        });
        $('#modalSubMateri').closest('form').submit(function (event) {
            event.preventDefault();
            // elem
            const elem = $(this);
            const submit = elem.find('#submit-sub');
            submit.prop('disabled', true);
            // ajax
            axios({
                method: elem.attr('method'),
                url: elem.attr('action'),
                data: elem.serialize()
            })
                .then(result => window.location.reload())
                .catch(error => {
                    try {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const elem = $(`#${key}`)
                            const err = errors[key][0] || 'Error';
                            elem.addClass('is-invalid')
                            $(`#${key}-message`).html(err);
                        })
                    } catch (error) {}
                })
                .finally(() => submit.prop('disabled', false));
        });
    })
</script>
<script>
    $(document).ready(function () {
        // modal 
        $('#modal').on('shown.bs.modal', function (event) {
            $('#name').trigger('focus');
        });
        $('#modal').on('show.bs.modal', function (event) {
            const target = $(event.relatedTarget);
            // cek tipe
            if (target.attr('data-type') == 'edit') {
                // set data
                var jsons = JSON.parse( target.closest('td').find('.jsons').val() );
                $('#name').val(jsons.name);
                $('#matter').val(jsons.matter);
                $('#description').val(jsons.description);
            }
            $('#modalLabel').html(target.attr('data-title'));
            $('#modal').closest('form').attr('action', target.attr('href'));
            $('#modal').closest('form').attr('method', target.attr('data-method'));
        });
        $('#modal').closest('form').submit(function (event) {
            event.preventDefault();
            // elem
            const elem = $(this);
            const submit = elem.find('#submit-matter');
            submit.prop('disabled', true);
            // ajax
            axios({
                method: elem.attr('method'),
                url: elem.attr('action'),
                data: elem.serialize()
            })
                .then(result => window.location.reload())
                .catch(error => {
                    try {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const elem = $(`#${key}`)
                            const err = errors[key][0] || 'Error';
                            elem.addClass('is-invalid')
                            $(`#${key}-message`).html(err);
                        })
                    } catch (error) {}
                })
                .finally(() => submit.prop('disabled', false));
        });
        $('.delete').click(function (event) {
            event.preventDefault();
            // get data
            var jsons = JSON.parse( $(this).closest('td').find('.jsons').val() );
            if (confirm(`Hapus Materi ${jsons.name}`)) {
                // ajax
                axios
                    .delete($(this).attr('href'))
                    .then(result => window.location.reload())
                    .catch(error => {
                        try {
                            alert(error.response.message)
                        } catch (error) {}
                    });
            }
        })
    });
</script>
@endsection