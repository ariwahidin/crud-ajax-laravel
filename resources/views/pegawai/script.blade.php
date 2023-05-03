<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
    crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverside: true,
            ajax: "{{ url('pegawaiAjax') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'nama',
                name: 'Nama'
            }, {
                data: 'email',
                name: 'Email'
            }, {
                data: 'aksi',
                name: 'Aksi'
            }]
        });
    });

    // GLOBAL SETUP
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    // 02_PROSES_SIMPAN
    $('body').on('click', '.tombol-tambah', function(e) {
        e.preventDefault()
        $('#exampleModal').modal('show')

        $('.tombol-simpan').click(function() {
            simpan();
        })
    });

    // 03_PROSES_EDIT
    $('body').on('click', '.tombol-edit', function(e) {
        var id = $(this).data('id')
        $.ajax({
            url: 'pegawaiAjax/' + id + '/edit',
            type: 'GET',
            success: function(response) {
                $('#exampleModal').modal('show');
                $('#nama').val(response.result.nama)
                $('#email').val(response.result.email)
                // console.log(response)
                $('.tombol-simpan').click(function() {
                    simpan(id);
                })
            }
        });
    });

    // 04_PROSES_DELETE
    $('body').on('click', '.tombol-del', function(e) {

        if (confirm('Yakin akan hapus data ini?') == true) {
            var id = $(this).data('id')
            $.ajax({
                url: 'pegawaiAjax/' + id,
                type: 'DELETE',
            });
            $('#myTable').DataTable().ajax.reload();
        }

    })

    // fungsi simpan dan update
    function simpan(id = '') {

        if (id == '') {
            var var_url = 'pegawaiAjax';
            var var_type = 'POST';
        } else {
            var var_url = 'pegawaiAjax/' + id;
            var var_type = 'PUT';
        }

        var nama = $('#nama').val()
        var email = $('#email').val()

        $.ajax({
            url: var_url,
            type: var_type,
            data: {
                nama,
                email
            },
            success: function(response) {
                if (response.errors) {
                    $('.alert-danger').removeClass('d-none');
                    $('.alert-danger').html("<ul>");
                    $.each(response.errors, function(key, value) {
                        $('.alert-danger').find('ul').append("<li>" + value +
                            "</li>");
                    });
                    $('.alert-danger').append("</ul>");
                } else {
                    $('.alert-success').removeClass('d-none');
                    $('.alert-success').html(response.success);
                }
                $('#myTable').DataTable().ajax.reload();
                // $('#exampleModal').modal('hide')
            }
        })
    }

    $('#exampleModal').on('hidden.bs.modal', function() {
        $('#nama').val('');
        $('#email').val('');

        $('.alert-danger').addClass('d-none');
        $('.alert-danger').html('');

        $('.alert-success').addClass('d-none');
        $('.alert-success').html('');
    })
</script>
