@extends('layouts.master')

@section('title', $title ?? 'Formulário')
@section('style')
      <!-- Datatables css -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('assets/plugins/jquery/App.js') }}"></script>
@endsection
@section('content')

    @yield( 'list')  
@endsection
@section('scripts')
        <!-- Datatables js -->
    
    <script src="{{ asset('assets/plugins/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
     <script src="{{ asset('assets/js/pages/datatables-export-data.js') }}"></script>

@endsection
