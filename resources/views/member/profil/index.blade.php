@extends('layouts.member.app')

@section('title', 'Profil')

@section('content')
<!-- About-->
<section class="resume-section" id="about">
    <div class="resume-section-content">
        <h1 class="mb-0">
            {{ auth()->user()->name }}
            {{-- <span class="text-primary">{{ auth()->user()->email }}</span> --}}
        </h1>
        <div class="subheading mb-1">
            Email: {{ auth()->user()->email ?? '-' }} | NIM: {{ auth()->user()->nim ?? '-' }}
        </div>
        <div class="subheading mb-1">
            Level: {{ auth()->user()->current_level }} | Poin: {{ auth()->user()->current_level }}
        </div>
        <div class="subheading mb-5">
            Musim: Musim Pertama | Periode: 01 Juni 2025 - 30 Juni 2025
        </div>
        {{-- <p class="lead mb-5">I am experienced in leveraging agile frameworks to provide a robust synopsis for
            high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to
            further the overall value proposition.</p>
        <div class="social-icons">
            <a class="social-icon" href="#!"><i class="fab fa-linkedin-in"></i></a>
            <a class="social-icon" href="#!"><i class="fab fa-github"></i></a>
            <a class="social-icon" href="#!"><i class="fab fa-twitter"></i></a>
            <a class="social-icon" href="#!"><i class="fab fa-facebook-f"></i></a>
        </div> --}}
    </div>
</section>
@endsection