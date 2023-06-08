<script>
    $(function() {
        var table = $("#dataTable2").DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            ajax: "{{ route('tagihan.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'nama_tagihan',
                    name: 'nama_tagihan'
                },
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'kelas.nama_kelas',
                    name: 'kelas.nama_kelas'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    render: function(data, type, row) {
                        //sum nominal
                        var sum = 0;
                        for (const tagihanSiswa of row.tagihan_siswa) {
                            sum += tagihanSiswa.nominal
                        }

                        if (sum != 0) {
                            return '';
                        } else {
                            return data;
                        }
                    },
                    orderable: false,
                    searchable: true
                },
            ]
        });
    });


    // Reset Form
    function resetForm() {
        $("[name='nominal']").val("")
    }

    // Create
    $("#store").on("submit", function(e) {
        e.preventDefault()
        $.ajax({
            url: "{{ route('tagihan.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    $("#createModal").modal("hide")
                    $('#dataTable2').DataTable().ajax.reload()
                    Swal.fire(
                        '',
                        response.message,
                        'success'
                    )
                    resetForm()
                } else {
                    printErrorMsg(response.error)
                }
            },
            error: function(err) {
                if (err.status == 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Not allowed!'
                    })
                }
            }
        })
    })

    // create-error-validation
    function printErrorMsg(msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'block');
        $.each(msg, function(key, value) {
            $(".print-error-msg").find("ul").append('<li>' + value + '</li>')
        });
    }

    // Edit
    $('body').on("click", ".btn-edit", function() {
        var id = $(this).attr("id")

        $.ajax({
            url: "/admin/tagihan/" + id + "/edit",
            method: "GET",
            success: function(response) {
                $("#editModal").modal("show")
                $("#id_edit").val(response.data.id)
                $("#nominal_edit").val(response.data.nominal)
                $("#nama_tagihan_edit").val(response.data.nama_tagihan)
            },
            error: function(err) {
                if (err.status == 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Not allowed!'
                    })
                }
            }
        })
    });

    // update
    $("#update").on("submit", function(e) {
        e.preventDefault()
        var id = $("#id_edit").val()
        $.ajax({
            url: "/admin/tagihan/" + id,
            method: "PATCH",
            data: $(this).serialize(),
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    $('#dataTable2').DataTable().ajax.reload();
                    $("#editModal").modal("hide")
                    Swal.fire(
                        '',
                        response.message,
                        'success'
                    )
                } else {
                    printErrorMsg(response.error)
                }
            },
            error: function(err) {
                if (err.status == 403) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Not allowed!'
                    })
                }
            }
        })
    })

    // delete
    $('body').on("click", ".btn-delete", function() {
        var id = $(this).attr("id")
        Swal.fire({
            title: 'Yakin hapus data ini?',
            // text: "You won't be able to revert",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/tagihan/" + id,
                    method: "DELETE",
                    success: function(response) {
                        $('#dataTable2').DataTable().ajax.reload()
                        Swal.fire(
                            '',
                            response.message,
                            'success'
                        )
                    },
                    error: function(err) {
                        if (err.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Not allowed!'
                            })
                        }
                    }
                })
            }
        })
    });
</script>
