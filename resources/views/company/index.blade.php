<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body { background: #f9fafb; }
        .company-card { border:1px solid #e5e7eb; border-radius:10px; background:#fff; transition:0.2s; margin-bottom:10px; }
        .company-card:hover { border-color:#d1d5db; }
        .icon-box { background:#dbeafe; padding:12px; border-radius:8px; display:inline-block; }
        .company-title { font-size:18px; font-weight:600; color:#111827; }
        .company-sub { color:#6b7280; }
        .btn-blue { background:#2563eb; color:#fff; }
        .btn-blue:hover { background:#1e40af; color:#fff; }
    </style>
</head>
<body>

<div class="container py-5">

    <!-- Heading -->
    <div class="text-center mb-5">
        <h2 class="mb-1">Select Company</h2>
        <p class="text-muted">Choose a company to manage or add a new one</p>
    </div>

    <!-- Company List -->
    <div id="companyList">
        @include('company.components.company_table', ['companies' => $companies])
    </div>

    <!-- Add New Company Button -->
    <button class="btn btn-blue w-100 mt-3" data-bs-toggle="modal" data-bs-target="#companyModal">
        <i class="fas fa-plus"></i> Add New Company
    </button>

</div>

<!-- Modal -->
@include('company.components.company_form')

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });

    // CREATE
    $('#createCompanyBtn').click(function(e){
        e.preventDefault();
        let form = $('#companyForm');
        $.ajax({
            url: "{{ url('/company') }}",
            type: "POST",
            data: form.serialize(),
            success: function(response){
                form[0].reset();
                $('#companyModal').modal('hide');
                $('#companyList').load(location.href + " #companyList>*","");
                toastr.success(response.success);
            },
            error: function(xhr){
                $('.error-text').text('');
                $.each(xhr.responseJSON.errors, function(key,value){
                    $('#error_'+key).text(value[0]);
                });
            }
        });
    });

    // EDIT
    $(document).on('click', '.editCompanyBtn', function(){
        let id = $(this).data('id');
        $.get('/company/'+id+'/edit', function(data){
            $('#company_id').val(data.id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#createCompanyBtn').hide();
            $('#updateCompanyBtn').show();
            $('#companyModal').modal('show');
        });
    });

    // UPDATE
    $('#updateCompanyBtn').click(function(e){
        e.preventDefault();
        let id = $('#company_id').val();
        let form = $('#companyForm');
        $.ajax({
            url: '/company/'+id,
            type: 'PUT',
            data: form.serialize(),
            success: function(response){
                form[0].reset();
                $('#companyModal').modal('hide');
                $('#companyList').load(location.href + " #companyList>*","");
                toastr.success(response.success);
                $('#createCompanyBtn').show();
                $('#updateCompanyBtn').hide();
            },
            error: function(xhr){
                $('.error-text').text('');
                $.each(xhr.responseJSON.errors, function(key,value){
                    $('#error_'+key).text(value[0]);
                });
            }
        });
    });

    // DELETE
    $(document).on('click', '.deleteCompanyBtn', function(){
        if(confirm('Are you sure you want to delete this company?')){
            let id = $(this).data('id');
            $.ajax({
                url: '/company/'+id,
                type: 'DELETE',
                success: function(response){
                    $('#companyList').load(location.href + " #companyList>*","");
                    toastr.success(response.success);
                }
            });
        }
    });

    // Reset modal on close
    $('#companyModal').on('hidden.bs.modal', function () {
        $('#companyForm')[0].reset();
        $('.error-text').text('');
        $('#createCompanyBtn').show();
        $('#updateCompanyBtn').hide();
    });
</script>

</body>
</html>
