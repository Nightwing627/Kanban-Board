@extends('layouts.app')

@section('content')

<div class="kanban-container">
    <main class="main-content">
        <!-- Our Kanban Vue component will go here -->
        <kanban-board :initial-data="{{ $statusList }}"></kanban-board>
    </main>
</div>

@endsection
