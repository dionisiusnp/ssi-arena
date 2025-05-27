@extends('layouts.member.app')

@section('title', 'Materi')

@section('content')
<!-- Education-->
<section class="resume-section" id="education">
    <div class="resume-section-content">
        <h2 class="mb-5">Education</h2>
        <div class="d-flex flex-column flex-md-row justify-content-between mb-5">
            <div class="flex-grow-1">
                <h3 class="mb-0">University of Colorado Boulder</h3>
                <div class="subheading mb-3">Bachelor of Science</div>
                <div>Computer Science - Web Development Track</div>
                <p>GPA: 3.23</p>
            </div>
            <div class="flex-shrink-0"><span class="text-primary">August 2006 - May 2010</span></div>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between">
            <div class="flex-grow-1">
                <h3 class="mb-0">James Buchanan High School</h3>
                <div class="subheading mb-3">Technology Magnet Program</div>
                <p>GPA: 3.56</p>
            </div>
            <div class="flex-shrink-0"><span class="text-primary">August 2002 - May 2006</span></div>
        </div>
    </div>
</section>
<hr class="m-0" />
<!-- Skills-->
<section class="resume-section" id="skills">
    <div class="resume-section-content">
        <h2 class="mb-5">Skills</h2>
        <div class="subheading mb-3">Programming Languages & Tools</div>
        <ul class="list-inline dev-icons">
            <li class="list-inline-item"><i class="fab fa-html5"></i></li>
            <li class="list-inline-item"><i class="fab fa-css3-alt"></i></li>
            <li class="list-inline-item"><i class="fab fa-js-square"></i></li>
            <li class="list-inline-item"><i class="fab fa-angular"></i></li>
            <li class="list-inline-item"><i class="fab fa-react"></i></li>
            <li class="list-inline-item"><i class="fab fa-node-js"></i></li>
            <li class="list-inline-item"><i class="fab fa-sass"></i></li>
            <li class="list-inline-item"><i class="fab fa-less"></i></li>
            <li class="list-inline-item"><i class="fab fa-wordpress"></i></li>
            <li class="list-inline-item"><i class="fab fa-gulp"></i></li>
            <li class="list-inline-item"><i class="fab fa-grunt"></i></li>
            <li class="list-inline-item"><i class="fab fa-npm"></i></li>
        </ul>
        <div class="subheading mb-3">Workflow</div>
        <ul class="fa-ul mb-0">
            <li>
                <span class="fa-li"><i class="fas fa-check"></i></span>
                Mobile-First, Responsive Design
            </li>
            <li>
                <span class="fa-li"><i class="fas fa-check"></i></span>
                Cross Browser Testing & Debugging
            </li>
            <li>
                <span class="fa-li"><i class="fas fa-check"></i></span>
                Cross Functional Teams
            </li>
            <li>
                <span class="fa-li"><i class="fas fa-check"></i></span>
                Agile Development & Scrum
            </li>
        </ul>
    </div>
</section>
@endsection